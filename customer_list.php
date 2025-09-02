<?php 
session_start();
include('header.php');
include 'Invoice.php';
include 'ledger.php';
require("nepali-date.php");
$invoice = new Invoice();
$ledger = new Ledger();
$nepali_date = new nepali_date();
$ledger->checkLoggedIn();
?>
<script src="js/invoice.js"></script>
<?php include('container.php');?>

<div class="container-fluid py-5">
    <div class="row">
        <div class="col-12 mb-4 z-index-1 position-relative ">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="title mb-0">
                    <i class="bi bi-people"></i> Customer List
                </h2>
                <a class="btn btn-success" href="create_customer.php">
		<i class="bi bi-person-plus"></i> Add New Customer
	</a>
            </div>

    <?php
    $ledgerList = $ledger->getCustomerList();
            if(empty($ledgerList)): ?>
                <div class="card border-0 shadow-sm">
                    <div class="card-body text-center">
                        <i class="bi bi-people fs-1 text-muted"></i>
                        <h4 class="mt-3">No Customers Found</h4>
                        <p class="text-muted">You haven't added any customers yet.</p>
                        <a href="create_customer.php" class="btn btn-primary">
                            <i class="bi bi-person-plus"></i> Add Your First Customer
                        </a>
                    </div>
                </div>
            <?php else: ?>
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-primary text-white  ">
                        <h5 class="mb-0 -flex align-items-center justify-content-center z-index-1 position-relative">
                            <i class="bi bi-list-ul p-3"></i> Customer Database
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="data-table" class="table table-hover">
                                <thead class="table-dark">
                                    <tr>
                                        <th><i class="bi bi-person"></i> Customer Name</th>
                                        <th><i class="bi bi-calendar3"></i> Created Date</th>
                                        <th><i class="bi bi-geo-alt"></i> Address</th>
                                        <th><i class="bi bi-telephone"></i> Phone Number</th>
                                        <th><i class="bi bi-cash-coin"></i> Total Due Balance</th>
                                        <th><i class="bi bi-gear"></i> Actions</th>
          </tr>
        </thead>
        <tbody>
                                    <?php foreach($ledgerList as $ledgerDetails): 
                                        $totalDue = $ledger->getTotalBalance($ledgerDetails["customer_id"]);
          $ledgerDate = $ledgerDetails["created_date"];
                                        $date = $invoice->NepaliDate($ledgerDate, $nepali_date);
                                    ?>
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="bg-primary bg-gradient text-white rounded-circle d-inline-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px;">
                                                        <i class="bi bi-person"></i>
                                                    </div>
                                                    <div>
                                                        <strong><?php echo htmlspecialchars($ledgerDetails["customer_name"]); ?></strong>
                                                        <?php if (!empty($ledgerDetails["customer_pan"])): ?>
                                                            <br><small class="text-muted">PAN: <?php echo htmlspecialchars($ledgerDetails["customer_pan"]); ?></small>
                                                        <?php endif; ?>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <span class="badge bg-secondary">
                                                    <i class="bi bi-calendar3"></i> 
                                                    <?php echo $date['y'].'-'.$date['m'].'-'.$date['d']; ?>
                                                </span>
                                            </td>
                                            <td>
                                                <span class="text-muted">
                                                    <i class="bi bi-geo-alt"></i> 
                                                    <?php echo htmlspecialchars($ledgerDetails["customer_address"]); ?>
                                                </span>
                                            </td>
                                            <td>
                                                <span class="badge bg-info">
                                                    <i class="bi bi-telephone"></i> 
                                                    <?php echo htmlspecialchars($ledgerDetails["customer_number"]); ?>
                                                </span>
                                            </td>
                                            <td>
                                                <?php if ($totalDue > 0): ?>
                                                    <span class="badge bg-danger fs-6">
                                                        <i class="bi bi-exclamation-triangle"></i> 
                                                        ₹<?php echo number_format($totalDue, 2); ?>
                                                    </span>
                                                <?php else: ?>
                                                    <span class="badge bg-success fs-6">
                                                        <i class="bi bi-check-circle"></i> 
                                                        ₹0.00
                                                    </span>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <div class="btn-group" role="group">
                                                    <a href="edit_customer.php?customer_id=<?php echo $ledgerDetails["customer_id"]; ?>" 
                                                       class="btn btn-sm btn-outline-primary" 
                                                       title="Edit Customer Details">
                                                        <i class="bi bi-pencil"></i>
                                                    </a>
                                                    <a href="transactions.php?update_id=<?php echo $ledgerDetails["customer_id"]; ?>" 
                                                       class="btn btn-sm btn-outline-info" 
                                                       title="View Transactions">
                                                        <i class="bi bi-file-text"></i>
                                                    </a>
                                                    <a href="ledger_page.php?customer_id=<?php echo $ledgerDetails["customer_id"]; ?>" 
                                                       class="btn btn-sm btn-outline-success" 
                                                       title="View Ledger">
                                                        <i class="bi bi-journal-text"></i>
                                                    </a>
                                                    <button type="button" 
                                                            class="btn btn-sm btn-outline-danger deleteCustomer" 
                                                            id="<?php echo $ledgerDetails["customer_id"]; ?>" 
                                                            title="Delete Customer">
                                                        <i class="bi bi-trash"></i>
                                                    </button>
                                                </div>
                                            </td>
                </tr>
                                    <?php endforeach; ?>
        </tbody>
      </table>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        </div>
</div>
</div>	

<script>
$(document).ready(function() {
    // Initialize DataTable if available
    if ($.fn.DataTable) {
        $('#data-table').DataTable({
            "order": [[1, "desc"]],
            "pageLength": 25,
            "language": {
                "search": "Search customers:",
                "lengthMenu": "Show _MENU_ customers per page",
                "info": "Showing _START_ to _END_ of _TOTAL_ customers"
            }
        });
    }

    // Enhanced delete functionality
    $('.deleteCustomer').on('click', function() {
        var customerId = $(this).attr('id');
        var customerName = $(this).closest('tr').find('td:first strong').text();
        
        if (confirm('Are you sure you want to delete customer "' + customerName + '"?\n\nThis action cannot be undone and will also delete all associated invoices and transactions.')) {
            $.ajax({
                url: 'action.php',
                method: 'POST',
                data: {
                    action: 'delete_customer',
                    id: customerId
                },
                dataType: 'json',
                success: function(response) {
                    if (response.status == 1) {
                        // Show success message
                        $('<div class="alert alert-success alert-dismissible fade show position-fixed" style="top: 20px; right: 20px; z-index: 9999;">' +
                          '<i class="bi bi-check-circle"></i> Customer deleted successfully!' +
                          '<button type="button" class="btn-close" data-bs-dismiss="alert"></button>' +
                          '</div>').appendTo('body');
                        
                        // Remove the row with animation
                        $('#' + customerId).closest('tr').fadeOut(500, function() {
                            $(this).remove();
                        });
                    } else {
                        alert('Error: Failed to delete customer');
                    }
                },
                error: function() {
                    alert('Error: Failed to delete customer. Please try again.');
                }
            });
        }
    });
});
</script>

<?php include('footer.php');?>