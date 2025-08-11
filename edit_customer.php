<?php 
session_start();
include('header.php');
include 'ledger.php';
$ledger = new Ledger();
$ledger->checkLoggedIn();

if(!empty($_POST['companyName']) && $_POST['companyName']) {	
	$ledger->updateCustomer($_POST);
	header("Location:customer_list.php");	
}

if(!empty($_GET['customer_id'])){
	$customerId = $_GET['customer_id'];
	$customerValue = $ledger->getCustomer($customerId);
} else {
	header("Location:customer_list.php");
	exit;
}
?>

<?php include('container.php');?>

<div class="container-fluid py-5">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="title mb-0">
                    <i class="bi bi-person-gear"></i> Edit Customer
                </h2>
                <a class="btn btn-outline-secondary" href="javascript:history.go(-1)">
                    <i class="bi bi-arrow-left"></i> Go Back
                </a>
            </div>

            <!-- Customer Edit Form -->
            <div class="row justify-content-center">
                <div class="col-lg-8 col-md-10">
                    <div class="card border-0 shadow-sm">
                        <div class="card-header bg-primary text-white">
                            <h5 class="mb-0">
                                <i class="bi bi-person-circle"></i> Customer Information
                            </h5>
                        </div>
                        <div class="card-body">
                            <form action="" id="invoice-form" method="post" class="invoice-form" role="form" novalidate="">
                                <div class="load-animate animated fadeInUp">
                                    
                                    <!-- Customer Name -->
                                    <div class="mb-4">
                                        <label for="companyName" class="form-label">
                                            <i class="bi bi-person"></i> Customer Name
                                        </label>
                                        <div class="input-group">
                                            <span class="input-group-text">
                                                <i class="bi bi-person-fill"></i>
                                            </span>
                                            <input type="text" 
                                                   value="<?php echo htmlspecialchars($customerValue['customer_name']); ?>" 
                                                   class="form-control" 
                                                   name="companyName" 
                                                   id="companyName" 
                                                   placeholder="Enter customer name" 
                                                   autocomplete="off" 
                                                   required>
                                        </div>
                                    </div>

                                    <!-- Customer Address -->
                                    <div class="mb-4">
                                        <label for="address" class="form-label">
                                            <i class="bi bi-geo-alt"></i> Address
                                        </label>
                                        <div class="input-group">
                                            <span class="input-group-text">
                                                <i class="bi bi-house"></i>
                                            </span>
                                            <textarea class="form-control" 
                                                      rows="3" 
                                                      name="companyAddress" 
                                                      id="address" 
                                                      placeholder="Enter customer address"><?php echo htmlspecialchars($customerValue['customer_address']); ?></textarea>
                                        </div>
                                    </div>

                                    <!-- Phone Number -->
                                    <div class="mb-4">
                                        <label for="companyPhone" class="form-label">
                                            <i class="bi bi-telephone"></i> Phone Number
                                        </label>
                                        <div class="input-group">
                                            <span class="input-group-text">
                                                <i class="bi bi-phone"></i>
                                            </span>
                                            <input type="tel" 
                                                   value="<?php echo htmlspecialchars($customerValue['customer_number']); ?>" 
                                                   class="form-control" 
                                                   name="companyPhone" 
                                                   id="companyPhone" 
                                                   placeholder="Enter phone number" 
                                                   autocomplete="off" 
                                                   required>
                                        </div>
                                    </div>

                                    <!-- PAN Number -->
                                    <div class="mb-4">
                                        <label for="companyPan" class="form-label">
                                            <i class="bi bi-card-text"></i> PAN Number (Optional)
                                        </label>
                                        <div class="input-group">
                                            <span class="input-group-text">
                                                <i class="bi bi-card-text"></i>
                                            </span>
                                            <input type="text" 
                                                   value="<?php echo htmlspecialchars($customerValue['customer_pan']); ?>" 
                                                   class="form-control" 
                                                   name="companyPan" 
                                                   id="companyPan" 
                                                   placeholder="Enter PAN number" 
                                                   autocomplete="off">
                                        </div>
                                    </div>

                                    <!-- Hidden Fields -->
                                    <input type="hidden" value="<?php echo $_SESSION['userid']; ?>" name="userId">
                                    <input type="hidden" value="<?php echo $customerId; ?>" name="customerId">
                                    
                                    <!-- Submit Button -->
                                    <div class="d-grid">
                                        <button type="submit" 
                                                name="customer_btn" 
                                                class="btn btn-success btn-lg" 
                                                data-loading-text="Saving Details...">
                                            <i class="bi bi-save"></i> Save Details
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