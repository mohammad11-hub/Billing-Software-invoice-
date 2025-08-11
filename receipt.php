<?php 
session_start();
include('header.php');
include 'Invoice.php';
include 'ledger.php';
$success = false;
$error = '';
$invoice = new Invoice();
$ledger = new Ledger();
$invoice->checkLoggedIn();
$customerList = $ledger->getCustomerList();

if(isset($_POST['invoice_btn'])) {
	$customerId = $_POST['customerId'];
	if(empty($customerId)) {
		$error = "Please Select Customer First.";
	}
	else if(empty($_POST['invoiceNumber'])) {
		$error = "Please Enter Invoice Number.";
	}
	else if(!$invoice->validateInvoice($_POST['invoiceNumber'], $customerId)) {
		$error = "Invoice Not found for the current user!.";
	}
	else if(empty($_POST['paidAmount'])) {
		$error = "Please enter amount to make payment.";
	}
	else {
		$due_amount = $invoice->getTotalDue($_POST['invoiceNumber']);
		$updatedAmount = $due_amount - $_POST['paidAmount'];
		if($updatedAmount < 0) {
			$error = "Hey! It seems the due amount is less than this value. Please recheck from invoice list.";
		}
		else {
			$ledger->createReceipt($_POST);
			$invoice->updateDueAmount($updatedAmount, $_POST['invoiceNumber']);
			$success = true;
		}
	}
}
?>
<script src="js/invoice.js"></script>
<?php include('container.php');?>

<div class="container-fluid py-5">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="title mb-0">
                    <i class="bi bi-credit-card"></i> Make Payment
                </h2>
                <a class="btn btn-outline-secondary" href="dashboard.php">
                    <i class="bi bi-arrow-left"></i> Go Back
                </a>
            </div>

            <!-- Success/Error Messages -->
            <?php if($success): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="bi bi-check-circle"></i> Payment saved successfully!
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>
            
            <?php if(!empty($error)): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="bi bi-exclamation-triangle"></i> <?php echo $error; ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <!-- Payment Form -->
            <div class="row justify-content-center">
                <div class="col-lg-6 col-md-8">
                    <div class="card border-0 shadow-sm">
                        <div class="card-header bg-primary text-white">
                            <h5 class="mb-0"><i class="bi bi-credit-card"></i> Payment Details</h5>
                        </div>
                        <div class="card-body">
                            <form action="" id="invoice-form" method="post" class="invoice-form" role="form" novalidate="">
                                <div class="load-animate animated fadeInUp">
                                    <input id="currency" type="hidden" value="₹">
                                    
                                    <!-- Customer Selection -->
                                    <div class="mb-4">
                                        <label for="customerId" class="form-label">
                                            <i class="bi bi-person"></i> Select Customer
                                        </label>
                                        <select class="form-select" name="customerId" id="customerId" required>
                                            <option value="">Choose a customer...</option>
                                            <?php foreach($customerList as $customerValue): ?>
                                                <option value="<?php echo $customerValue["customer_id"]; ?>">
                                                    <?php echo htmlspecialchars($customerValue["customer_name"]); ?> - 
                                                    <?php echo htmlspecialchars($customerValue["customer_address"]); ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>

                                    <!-- Invoice Number -->
                                    <div class="mb-4">
                                        <label for="invoiceNumber" class="form-label">
                                            <i class="bi bi-receipt"></i> Invoice Number
                                        </label>
                                        <div class="input-group">
                                            <span class="input-group-text">
                                                <i class="bi bi-hash"></i>
                                            </span>
                                            <input type="number" 
                                                   class="form-control" 
                                                   name="invoiceNumber" 
                                                   id="invoiceNumber" 
                                                   placeholder="Enter invoice number"
                                                   required>
                                        </div>
                                    </div>

                                    <!-- Payment Amount -->
                                    <div class="mb-4">
                                        <label for="paidAmount" class="form-label">
                                            <i class="bi bi-cash-coin"></i> Payment Amount
                                        </label>
                                        <div class="input-group">
                                            <span class="input-group-text">₹</span>
                                            <input type="number" 
                                                   class="form-control" 
                                                   name="paidAmount" 
                                                   id="paidAmount" 
                                                   placeholder="Enter payment amount"
                                                   step="0.01"
                                                   min="0"
                                                   required>
                                        </div>
                                    </div>

                                    <!-- Hidden Fields -->
                                    <input type="hidden" value="<?php echo $_SESSION['userid']; ?>" name="userId">
                                    
                                    <!-- Submit Button -->
                                    <div class="d-grid">
                                        <button type="submit" 
                                                name="invoice_btn" 
                                                class="btn btn-success btn-lg" 
                                                data-loading-text="Saving Payment...">
                                            <i class="bi bi-credit-card"></i> Save Payment
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include('footer.php');?>