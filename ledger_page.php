<?php
session_start();
include('header.php');
include 'ledger.php';
include 'Invoice.php';

$ledger = new Ledger();
$invoice = new Invoice();
$ledger->checkLoggedIn();

// Get customer list
$customerList = $ledger->getCustomerList();

// Get selected customer if provided
$selectedCustomerId = isset($_GET['customer_id']) ? $_GET['customer_id'] : null;
$selectedCustomer = null;
$customerTransactions = array();
$customerSummary = array();

if ($selectedCustomerId) {
    $selectedCustomer = $ledger->getCustomer($selectedCustomerId);
    if ($selectedCustomer) {
        $customerTransactions = $ledger->getCustomerTransactions($selectedCustomerId);
        $customerSummary = $ledger->getCustomerSummary($selectedCustomerId);
    }
}

// Debug information (remove in production)
if (isset($_GET['debug']) && $_GET['debug'] == 1) {
    echo "<pre>";
    echo "Customer List Count: " . count($customerList) . "\n";
    echo "Selected Customer ID: " . $selectedCustomerId . "\n";
    if ($selectedCustomer) {
        echo "Selected Customer: " . print_r($selectedCustomer, true) . "\n";
        echo "Customer Summary: " . print_r($customerSummary, true) . "\n";
        echo "Customer Transactions Count: " . count($customerTransactions) . "\n";
    }
    echo "</pre>";
}
?>

<?php include('container.php');?>

<div class="container-fluid py-5">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="title mb-0">
                    <i class="bi bi-journal-text"></i> Ledger Management
                </h2>
                <a class="btn btn-outline-secondary" href="dashboard.php">
                    <i class="bi bi-arrow-left"></i> Back to Dashboard
                </a>
            </div>

            <!-- Customer Selection -->
            <div class="row">
                <div class="col-lg-4">
                    <div class="card border-0 shadow-sm">
                        <div class="card-header bg-primary text-white">
                            <h5 class="mb-0"><i class="bi bi-people"></i> Select Customer</h5>
                        </div>
                        <div class="card-body">
                            <form method="GET" action="ledger_page.php">
                                <div class="mb-3">
                                    <label for="customer_id" class="form-label">Choose Customer:</label>
                                    <select class="form-select" name="customer_id" id="customer_id" onchange="this.form.submit()">
                                        <option value="">Select a customer...</option>
                                        <?php if (!empty($customerList)): ?>
                                            <?php foreach($customerList as $customer): ?>
                                                <option value="<?php echo $customer['customer_id']; ?>" 
                                                        <?php echo ($selectedCustomerId == $customer['customer_id']) ? 'selected' : ''; ?>>
                                                    <?php echo htmlspecialchars($customer['customer_name']); ?> - 
                                                    <?php echo htmlspecialchars($customer['customer_address']); ?>
                                                </option>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                    </select>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Customer Summary -->
                <?php if ($selectedCustomer): ?>
                <div class="col-lg-8">
                    <div class="card border-0 shadow-sm">
                        <div class="card-header bg-success text-white">
                            <h5 class="mb-0">
                                <i class="bi bi-person-circle"></i> Customer Summary - <?php echo htmlspecialchars($selectedCustomer['customer_name']); ?>
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="text-center">
                                        <div class="bg-primary bg-gradient text-white rounded-circle d-inline-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                                            <i class="bi bi-receipt fs-2"></i>
                                        </div>
                                        <h6 class="mt-2">Total Invoiced</h6>
                                        <h4 class="text-primary">₹<?php echo number_format($customerSummary['total_invoiced'], 2); ?></h4>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="text-center">
                                        <div class="bg-success bg-gradient text-white rounded-circle d-inline-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                                            <i class="bi bi-cash-coin fs-2"></i>
                                        </div>
                                        <h6 class="mt-2">Total Paid</h6>
                                        <h4 class="text-success">₹<?php echo number_format($customerSummary['total_paid'], 2); ?></h4>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="text-center">
                                        <div class="bg-danger bg-gradient text-white rounded-circle d-inline-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                                            <i class="bi bi-exclamation-triangle fs-2"></i>
                                        </div>
                                        <h6 class="mt-2">Total Due</h6>
                                        <h4 class="text-danger">₹<?php echo number_format($customerSummary['total_due'], 2); ?></h4>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endif; ?>
            </div>

            <!-- Customer Details -->
            <?php if ($selectedCustomer): ?>
            <div class="row mt-4">
                <div class="col-12">
                    <div class="card border-0 shadow-sm">
                        <div class="card-header bg-info text-white">
                            <h5 class="mb-0">
                                <i class="bi bi-person-badge"></i> Customer Information
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="d-flex align-items-center">
                                        <i class="bi bi-person fs-1 text-primary me-3"></i>
                                        <div>
                                            <h6 class="mb-1">Customer Name</h6>
                                            <strong><?php echo htmlspecialchars($selectedCustomer['customer_name']); ?></strong>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="d-flex align-items-center">
                                        <i class="bi bi-telephone fs-1 text-success me-3"></i>
                                        <div>
                                            <h6 class="mb-1">Contact Number</h6>
                                            <strong><?php echo htmlspecialchars($selectedCustomer['customer_number']); ?></strong>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="d-flex align-items-center">
                                        <i class="bi bi-geo-alt fs-1 text-warning me-3"></i>
                                        <div>
                                            <h6 class="mb-1">Address</h6>
                                            <strong><?php echo htmlspecialchars($selectedCustomer['customer_address']); ?></strong>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="d-flex align-items-center">
                                        <i class="bi bi-card-text fs-1 text-info me-3"></i>
                                        <div>
                                            <h6 class="mb-1">PAN Number</h6>
                                            <strong><?php echo htmlspecialchars($selectedCustomer['customer_pan'] ?: 'N/A'); ?></strong>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Transaction History -->
            <div class="row mt-4">
                <div class="col-12">
                    <div class="card border-0 shadow-sm">
                        <div class="card-header bg-dark text-white">
                            <h5 class="mb-0">
                                <i class="bi bi-clock-history"></i> Transaction History
                            </h5>
                        </div>
                        <div class="card-body">
                            <?php if (!empty($customerTransactions)): ?>
                                <div class="table-responsive">
                                    <table class="table table-hover">
                                        <thead class="table-dark">
                                            <tr>
                                                <th>Date</th>
                                                <th>Type</th>
                                                <th>ID</th>
                                                <th>Amount</th>
                                                <th>Due Amount</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach($customerTransactions as $transaction): ?>
                                                <tr>
                                                    <td>
                                                        <i class="bi bi-calendar3"></i> 
                                                        <?php echo htmlspecialchars($transaction['date']); ?>
                                                    </td>
                                                    <td>
                                                        <?php
                                                        $typeClass = $transaction['type'] == 'Invoice' ? 'bg-primary' : 'bg-success';
                                                        $typeIcon = $transaction['type'] == 'Invoice' ? 'bi-receipt' : 'bi-cash-coin';
                                                        ?>
                                                        <span class="badge <?php echo $typeClass; ?>">
                                                            <i class="bi <?php echo $typeIcon; ?>"></i> 
                                                            <?php echo htmlspecialchars($transaction['type']); ?>
                                                        </span>
                                                    </td>
                                                    <td>
                                                        <span class="badge bg-secondary">#<?php echo htmlspecialchars($transaction['id']); ?></span>
                                                    </td>
                                                    <td>
                                                        <span class="badge bg-<?php echo $transaction['type'] == 'Invoice' ? 'primary' : 'success'; ?> fs-6">
                                                            ₹<?php echo number_format($transaction['amount'], 2); ?>
                                                        </span>
                                                    </td>
                                                    <td>
                                                        <?php if ($transaction['due_amount'] > 0): ?>
                                                            <span class="badge bg-danger fs-6">
                                                                ₹<?php echo number_format($transaction['due_amount'], 2); ?>
                                                            </span>
                                                        <?php else: ?>
                                                            <span class="badge bg-success fs-6">Paid</span>
                                                        <?php endif; ?>
                                                    </td>
                                                    <td>
                                                        <?php if ($transaction['type'] == 'Invoice'): ?>
                                                            <a href="print_invoice.php?invoice_id=<?php echo $transaction['id']; ?>" 
                                                               class="btn btn-sm btn-outline-primary" 
                                                               target="_blank"
                                                               title="Print Invoice">
                                                                <i class="bi bi-printer"></i>
                                                            </a>
                                                            <a href="transactions.php?update_id=<?php echo $selectedCustomerId; ?>" 
                                                               class="btn btn-sm btn-outline-info"
                                                               title="View Transactions">
                                                                <i class="bi bi-eye"></i>
                                                            </a>
                                                        <?php endif; ?>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                            <?php else: ?>
                                <div class="alert alert-info text-center">
                                    <i class="bi bi-info-circle fs-1"></i>
                                    <h4 class="mt-2">No Transactions Found</h4>
                                    <p class="mb-0">This customer has no transaction history yet.</p>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
            <?php else: ?>
            <!-- No Customer Selected -->
            <div class="row mt-4">
                <div class="col-12">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body text-center">
                            <i class="bi bi-people fs-1 text-muted"></i>
                            <h4 class="mt-3">Select a Customer</h4>
                            <p class="text-muted">Choose a customer from the dropdown above to view their ledger information.</p>
                        </div>
                    </div>
                </div>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php include('footer.php');?>
