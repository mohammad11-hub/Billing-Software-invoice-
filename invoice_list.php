<?php 
session_start();
include('header.php');
include 'Invoice.php';
include 'ledger.php';
require("nepali-date.php");
$invoice = new Invoice();
$ledger = new Ledger();
$nepali_date = new nepali_date();
$invoice->checkLoggedIn();
?>
<script src="js/invoice.js"></script>
<link href="css/style.css" rel="stylesheet">
<?php include('container.php');?>

<div class="container-fluid py-5">
  <div class="row">
    <div class="col-12">
      <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="title mb-0">
          <i class="bi bi-receipt"></i> Invoice List
        </h2>
        <a class="btn btn-outline-secondary" href="javascript:history.go(-1)">
          <i class="bi bi-arrow-left"></i> Go Back
        </a>
      </div>

      <?php 
      $invoiceList = $invoice->getInvoiceList();
      if(empty($invoiceList)){
        echo '<div class="alert alert-info" role="alert">
                <i class="bi bi-info-circle"></i> No Invoices Found! 
                <a href="create_invoice.php" class="alert-link">Create your first invoice</a>
              </div>';	
      } else {  
      ?>	  
        <div class="card border-0 shadow-sm">
          <div class="card-header bg-primary text-white">
            <h5 class="mb-0"><i class="bi bi-list-ul"></i> All Invoices</h5>
          </div>
          <div class="card-body">
            <div class="table-responsive">
              <table id="data-table" class="table table-hover">
                <thead class="table-dark">
                  <tr>
                    <th>Invoice No.</th>
                    <th>Created Date</th>
                    <th>Customer Name</th>
                    <th>Invoice Total</th>
                    <th>Amount Due</th>
                    <th>Actions</th>
                  </tr>
                </thead>
                <tbody>
                  <?php		
                  foreach($invoiceList as $invoiceDetails){
                    $customerDetails = $ledger->getCustomer($invoiceDetails['customer_id']);
                    $invoiceDate = $invoiceDetails['order_date'];
                    $date = $invoice->NepaliDate($invoiceDate, $nepali_date);
                    
                    // Determine status color
                    $dueAmount = $invoiceDetails["order_total_amount_due"];
                    $statusClass = $dueAmount > 0 ? 'text-danger' : 'text-success';
                    $statusIcon = $dueAmount > 0 ? 'bi-exclamation-circle' : 'bi-check-circle';
                    
                    echo '
                      <tr>
                        <td>
                          <strong>#'.$invoiceDetails["order_id"].'</strong>
                        </td>
                        <td>
                          <i class="bi bi-calendar3"></i> '.$date['y'].'-'.$date['m'].'-'.$date['d'].'
                        </td>
                        <td>
                          <div class="d-flex align-items-center">
                            <i class="bi bi-person-circle me-2"></i>
                            <div>
                              <strong>'.$customerDetails["customer_name"].'</strong>
                              <br><small class="text-muted">'.$customerDetails["customer_address"].'</small>
                            </div>
                          </div>
                        </td>
                        <td>
                          <span class="badge bg-primary fs-6">₹'.$invoiceDetails["order_total_after_tax"].'</span>
                        </td>
                        <td>
                          <span class="'.$statusClass.'">
                            <i class="bi '.$statusIcon.'"></i> ₹'.$dueAmount.'
                          </span>
                        </td>
                        <td>
                          <div class="btn-group" role="group">
                            <a href="print_invoice.php?invoice_id='.$invoiceDetails["order_id"].'" 
                               class="btn btn-sm btn-outline-primary" 
                               title="Print Invoice" 
                               target="_blank">
                              <i class="bi bi-printer"></i>
                            </a>
                            <a href="edit_invoice.php?update_id='.$invoiceDetails["order_id"].'" 
                               class="btn btn-sm btn-outline-warning" 
                               title="Edit Invoice">
                              <i class="bi bi-pencil"></i>
                            </a>
                            <button type="button" 
                                    class="btn btn-sm btn-outline-danger deleteInvoice" 
                                    data-id="'.$invoiceDetails["order_id"].'" 
                                    title="Delete Invoice">
                              <i class="bi bi-trash"></i>
                            </button>
                          </div>
                        </td>
                      </tr>
                    ';
                  }
                  ?>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      <?php } ?>
    </div>
  </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
  // Initialize DataTable if available
  if (typeof $.fn.DataTable !== 'undefined') {
    $('#data-table').DataTable({
      "order": [[ 0, "desc" ]],
      "pageLength": 25,
      "responsive": true,
      "language": {
        "search": "Search invoices:",
        "lengthMenu": "Show _MENU_ invoices per page",
        "info": "Showing _START_ to _END_ of _TOTAL_ invoices"
      }
    });
  }

  // Delete invoice functionality
  document.querySelectorAll('.deleteInvoice').forEach(function(btn) {
    btn.addEventListener('click', function(e) {
      e.preventDefault();
      var invoiceId = this.getAttribute('data-id');
      
      if(confirm('Are you sure you want to delete invoice #' + invoiceId + '? This action cannot be undone.')) {
        // Show loading state
        this.innerHTML = '<i class="bi bi-hourglass-split"></i>';
        this.disabled = true;
        
        fetch('action.php', {
          method: 'POST',
          headers: { 
            'Content-Type': 'application/x-www-form-urlencoded',
            'X-Requested-With': 'XMLHttpRequest'
          },
          body: 'action=deleteInvoice&invoice_id=' + encodeURIComponent(invoiceId)
        })
        .then(response => response.text())
        .then(data => {
          if(data.trim() === '1') {
            // Show success message
            const alert = document.createElement('div');
            alert.className = 'alert alert-success alert-dismissible fade show position-fixed';
            alert.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 300px;';
            alert.innerHTML = `
              <i class="bi bi-check-circle"></i> Invoice #${invoiceId} deleted successfully!
              <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            `;
            document.body.appendChild(alert);
            
            // Remove the row after a short delay
            setTimeout(() => {
              const row = this.closest('tr');
              if (row) {
                row.style.transition = 'all 0.3s ease';
                row.style.opacity = '0';
                row.style.transform = 'translateX(-100%)';
                setTimeout(() => row.remove(), 300);
              }
              alert.remove();
            }, 1000);
          } else {
            // Show error message
            const alert = document.createElement('div');
            alert.className = 'alert alert-danger alert-dismissible fade show position-fixed';
            alert.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 300px;';
            alert.innerHTML = `
              <i class="bi bi-exclamation-triangle"></i> Failed to delete invoice. Please try again.
              <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            `;
            document.body.appendChild(alert);
            
            // Reset button
            this.innerHTML = '<i class="bi bi-trash"></i>';
            this.disabled = false;
            
            setTimeout(() => alert.remove(), 3000);
          }
        })
        .catch(error => {
          console.error('Error:', error);
          alert('An error occurred while deleting the invoice. Please try again.');
          
          // Reset button
          this.innerHTML = '<i class="bi bi-trash"></i>';
          this.disabled = false;
        });
      }
    });
  });
});
</script>

<?php include('footer.php');?>