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

// Check if customer ID is provided
if(!empty($_GET['update_id'])){
	$customerId = $_GET['update_id'];
	$totalDue = $ledger->getTotalBalance($customerId);
	$companyDetails = $ledger->getCustomer($customerId);
	$transactions = $ledger->getAllTransaction($customerId);
} else {
    // Redirect if no customer ID provided
    header("Location: customer_list.php");
    exit;
}
?>
<script src="js/invoice.js"></script>
<link href="css/style.css" rel="stylesheet">
<?php include('container.php');?>

<div class="container-fluid py-5">
  <div class="row">
    <div class="col-12">
      <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="title mb-0">
          <i class="bi bi-arrow-left-right"></i> Transaction History
        </h2>
        <a class="btn btn-outline-secondary" href="javascript:history.go(-1)">
          <i class="bi bi-arrow-left"></i> Go Back
        </a>
      </div>

      <!-- Customer Information Card -->
      <div class="card border-0 shadow-sm mb-4">
        <div class="card-header bg-primary text-white">
          <h5 class="mb-0"><i class="bi bi-person-circle"></i> Customer Information</h5>
        </div>
        <div class="card-body">
          <div class="row">
            <div class="col-md-3">
              <div class="d-flex align-items-center">
                <i class="bi bi-person fs-1 text-primary me-3"></i>
                <div>
                  <h6 class="mb-1">Customer Name</h6>
                  <strong><?php echo htmlspecialchars($companyDetails["customer_name"]); ?></strong>
                </div>
              </div>
            </div>
            <div class="col-md-3">
              <div class="d-flex align-items-center">
                <i class="bi bi-telephone fs-1 text-success me-3"></i>
                <div>
                  <h6 class="mb-1">Contact Number</h6>
                  <strong><?php echo htmlspecialchars($companyDetails["customer_number"]); ?></strong>
                </div>
              </div>
            </div>
            <div class="col-md-3">
              <div class="d-flex align-items-center">
                <i class="bi bi-geo-alt fs-1 text-warning me-3"></i>
                <div>
                  <h6 class="mb-1">Address</h6>
                  <strong><?php echo htmlspecialchars($companyDetails["customer_address"]); ?></strong>
                </div>
              </div>
            </div>
            <div class="col-md-3">
              <div class="d-flex align-items-center">
                <i class="bi bi-card-text fs-1 text-info me-3"></i>
                <div>
                  <h6 class="mb-1">PAN Number</h6>
                  <strong><?php echo htmlspecialchars($companyDetails["customer_pan"]); ?></strong>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Total Due Balance -->
      <div class="alert alert-info text-center mb-4">
        <h4 class="mb-0">
          <i class="bi bi-cash-coin"></i> Total Due Balance: 
          <span class="badge bg-danger fs-5">₹<?php echo number_format($totalDue, 2); ?></span>
        </h4>
      </div>

      <!-- Transactions -->
      <?php if(!empty($transactions)): ?>
        <?php foreach($transactions as $transaction): ?>
          <?php
          $invoiceId = $transaction["order_id"];
          $initialPayment = $ledger->initialPayment($invoiceId);
          $transactionDetails = $ledger->TransactionDetails($invoiceId);
          $InvoiceDue = $invoice->getTotalDue($invoiceId);

          if($initialPayment['paid'] > 0){
            $paymentDetails = array();
            $paymentDetails['Id'] = 0;
            $paymentDetails['Date'] = $initialPayment['date'];
            $paymentDetails['Amount'] = $initialPayment['paid'];
            $paymentDetails['Type'] = "Initial Payment";
            array_push($transactionDetails, $paymentDetails);
          }
          ?>
          
          <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-success text-white">
              <h5 class="mb-0">
                <i class="bi bi-receipt"></i> Invoice #<?php echo $invoiceId; ?> - Transaction Details
              </h5>
            </div>
            <div class="card-body">
              <div class="table-responsive">
                <table class="table table-hover">
                  <thead class="table-dark">
                    <tr>
                      <th>Transaction ID</th>
                      <th>Transaction Date</th>
                      <th>Transaction Type</th>
                      <th>Amount</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php foreach($transactionDetails as $detail): ?>
                      <tr>
                        <td>
                          <span class="badge bg-secondary">#<?php echo htmlspecialchars($detail['Id']); ?></span>
                        </td>
                        <td>
                          <i class="bi bi-calendar3"></i> <?php echo htmlspecialchars($detail['Date']); ?>
                        </td>
                        <td>
                          <?php
                          $typeClass = '';
                          $typeIcon = '';
                          switch($detail['Type']) {
                            case 'Initial Payment':
                              $typeClass = 'bg-success';
                              $typeIcon = 'bi-cash-coin';
                              break;
                            case 'Payment':
                              $typeClass = 'bg-primary';
                              $typeIcon = 'bi-credit-card';
                              break;
                            default:
                              $typeClass = 'bg-info';
                              $typeIcon = 'bi-receipt';
                          }
                          ?>
                          <span class="badge <?php echo $typeClass; ?>">
                            <i class="bi <?php echo $typeIcon; ?>"></i> <?php echo htmlspecialchars($detail['Type']); ?>
                          </span>
                        </td>
                        <td>
                          <span class="badge bg-<?php echo $detail['Type'] == 'Payment' ? 'success' : 'primary'; ?> fs-6">
                            ₹<?php echo number_format($detail['Amount'], 2); ?>
                          </span>
                        </td>
                      </tr>
                    <?php endforeach; ?>
                  </tbody>
                </table>
              </div>
            </div>
            <div class="card-footer bg-light">
              <div class="d-flex justify-content-between align-items-center">
                <span class="text-muted">
                  <i class="bi bi-info-circle"></i> Invoice Due Amount
                </span>
                <span class="badge bg-<?php echo $InvoiceDue > 0 ? 'danger' : 'success'; ?> fs-6">
                  ₹<?php echo number_format($InvoiceDue, 2); ?>
                </span>
              </div>
            </div>
          </div>
        <?php endforeach; ?>
      <?php else: ?>
        <div class="alert alert-info text-center">
          <i class="bi bi-info-circle fs-1"></i>
          <h4 class="mt-2">No Transactions Found</h4>
          <p class="mb-0">This customer has no transaction history yet.</p>
        </div>
      <?php endif; ?>

      <!-- Information Note -->
      <div class="alert alert-warning">
        <i class="bi bi-exclamation-triangle"></i>
        <strong>Note:</strong> Transaction ID equals invoice ID and receipt ID for credit and debit transactions respectively.
      </div>
    </div>
  </div>
</div>

<?php include('footer.php');?>