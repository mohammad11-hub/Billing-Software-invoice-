<?php 
session_start();
$emptyError = '';
include('header.php');
include 'ledger.php';
$ledger = new Ledger();
$ledger->checkLoggedIn();

if(!empty($_POST)) {
    if(!empty($_POST['companyName']) && !empty($_POST['companyAddress']) && !empty($_POST['companyPhone'])) {
        $ledger->insertCustomer($_POST);
        header("Location:customer_list.php");
        exit;
    } else {
        $emptyError = "You cannot leave mandatory field empty!";
    }
}
?>

<?php include('container.php');?>

<div class="container-fluid py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8 col-md-10">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="title mb-0">
                    <i class="bi bi-person-plus"></i> Add New Customer
                </h2>
                <a class="btn btn-outline-secondary" href="customer_list.php">
                    <i class="bi bi-arrow-left"></i> Back to Customer List
                </a>
            </div>

            <div class="card border-0 shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">
                        <i class="bi bi-person-badge"></i> Customer Information
                    </h5>
                </div>
                <div class="card-body">
                    <?php if ($emptyError): ?>
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="bi bi-exclamation-triangle"></i> <?php echo htmlspecialchars($emptyError); ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    <?php endif; ?>

                    <form action="" method="post" class="customer-form" role="form" novalidate>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="companyName" class="form-label">
                                        <i class="bi bi-person"></i> Customer Name <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" 
                                           class="form-control" 
                                           name="companyName" 
                                           id="companyName" 
                                           placeholder="Enter customer name" 
                                           autocomplete="off" 
                                           required
                                           value="<?php echo isset($_POST['companyName']) ? htmlspecialchars($_POST['companyName']) : ''; ?>">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="companyPhone" class="form-label">
                                        <i class="bi bi-telephone"></i> Phone Number <span class="text-danger">*</span>
                                    </label>
                                    <input type="tel" 
                                           class="form-control" 
                                           name="companyPhone" 
                                           id="companyPhone" 
                                           placeholder="Enter phone number" 
                                           autocomplete="off" 
                                           required
                                           value="<?php echo isset($_POST['companyPhone']) ? htmlspecialchars($_POST['companyPhone']) : ''; ?>">
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="companyAddress" class="form-label">
                                <i class="bi bi-geo-alt"></i> Address <span class="text-danger">*</span>
                            </label>
                            <textarea class="form-control" 
                                      rows="3" 
                                      name="companyAddress" 
                                      id="companyAddress" 
                                      placeholder="Enter customer address" 
                                      required><?php echo isset($_POST['companyAddress']) ? htmlspecialchars($_POST['companyAddress']) : ''; ?></textarea>
                        </div>

                        <div class="mb-3">
                            <label for="companyPan" class="form-label">
                                <i class="bi bi-card-text"></i> PAN Number <span class="text-muted">(Optional)</span>
                            </label>
                            <input type="text" 
                                   class="form-control" 
                                   name="companyPan" 
                                   id="companyPan" 
                                   placeholder="Enter PAN number" 
                                   autocomplete="off"
                                   value="<?php echo isset($_POST['companyPan']) ? htmlspecialchars($_POST['companyPan']) : ''; ?>">
                        </div>

                        <div class="mb-3">
                            <input type="hidden" value="<?php echo $_SESSION['userid']; ?>" name="userId">
                        </div>

                        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                            <a href="customer_list.php" class="btn btn-secondary me-md-2">
                                <i class="bi bi-x-circle"></i> Cancel
                            </a>
                            <button type="submit" name="customer_btn" class="btn btn-success">
                                <i class="bi bi-save"></i> Save Customer
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include('footer.php');?>