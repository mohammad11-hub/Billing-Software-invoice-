<?php 
session_start();
include('header.php');
include 'Invoice.php';
include 'Ledger.php';
$companyNameError = false;
$invoice = new Invoice();
$ledger = new Ledger();
$invoice->checkLoggedIn();
?>
<script src="js/invoice.js"></script>
<?php include('container.php');?>

<!-- Welcome Section -->
<div class="container-fluid py-5">
  <div class="row">
    <div class="col-12">
      <div class="text-center mb-5">
        <h1 class="display-4 fw-bold text-primary mb-3">
          <i class="bi bi-speedometer2"></i> Welcome to Billing System
        </h1>
        <p class="lead text-muted">Manage your invoices, customers, and payments efficiently</p>
        <div class="alert alert-info d-inline-block">
          <i class="bi bi-info-circle"></i> Use the navigation menu above to access all features
        </div>
      </div>
    </div>
  </div>

  <!-- Quick Stats -->
  <div class="row g-4 mb-5">
    <div class="col-md-3">
      <div class="card border-0 shadow-sm h-100">
        <div class="card-body text-center">
          <div class="bg-primary bg-gradient text-white rounded-circle d-inline-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
            <i class="bi bi-receipt fs-2"></i>
          </div>
          <h5 class="mt-3 mb-1">Invoices</h5>
          <p class="text-muted mb-0">Manage all invoices</p>
        </div>
      </div>
    </div>
    <div class="col-md-3">
      <div class="card border-0 shadow-sm h-100">
        <div class="card-body text-center">
          <div class="bg-success bg-gradient text-white rounded-circle d-inline-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
            <i class="bi bi-people fs-2"></i>
          </div>
          <h5 class="mt-3 mb-1">Customers</h5>
          <p class="text-muted mb-0">Customer database</p>
        </div>
      </div>
    </div>
    <div class="col-md-3">
      <div class="card border-0 shadow-sm h-100">
        <div class="card-body text-center">
          <div class="bg-warning bg-gradient text-white rounded-circle d-inline-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
            <i class="bi bi-box fs-2"></i>
          </div>
          <h5 class="mt-3 mb-1">Items</h5>
          <p class="text-muted mb-0">Product catalog</p>
        </div>
      </div>
    </div>
    <div class="col-md-3">
      <div class="card border-0 shadow-sm h-100">
        <div class="card-body text-center">
          <div class="bg-info bg-gradient text-white rounded-circle d-inline-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
            <i class="bi bi-cash-coin fs-2"></i>
          </div>
          <h5 class="mt-3 mb-1">Payments</h5>
          <p class="text-muted mb-0">Payment tracking</p>
        </div>
      </div>
    </div>
  </div>

  <!-- Notice Board Section -->
  <div class="row">
    <div class="col-lg-8 mx-auto">
      <div class="card border-0 shadow-sm">
        <div class="card-header bg-dark text-white">
          <h5 class="mb-0"><i class="bi bi-megaphone"></i> Notice Board</h5>
        </div>
        <div class="card-body">
          <div class="list-group list-group-flush">
            <?php include('notice_board.php'); ?>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<?php include('footer.php');?>