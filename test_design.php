<?php
session_start();
include('header.php');
?>
<?php include('container.php');?>
<div class="container-fluid dashboard">
  <!-- Dashboard Header -->
  <div class="row mb-4">
    <div class="col-12">
      <div class="d-flex justify-content-between align-items-center">
        <h1 class="text-white mb-0">
          <i class="bi bi-speedometer2"></i> Professional Dashboard
        </h1>
        <div class="text-white">
          <small>Welcome to the new design!</small>
        </div>
      </div>
    </div>
  </div>

  <!-- Main Navigation Grid -->
  <div class="row">
    <div class="col-lg-8">
      <div class="row g-4">
        <!-- Item Management Section -->
        <div class="col-md-6">
          <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-primary text-white">
              <h5 class="mb-0"><i class="bi bi-box"></i> Item Management</h5>
            </div>
            <div class="card-body">
              <div class="d-grid gap-2">
                <a href="#" class="btn btn-outline-primary btn-lg">
                  <i class="bi bi-plus-circle"></i> Insert Item
                </a>
                <a href="#" class="btn btn-outline-info btn-lg">
                  <i class="bi bi-list-ul"></i> Item List
                </a>
              </div>
            </div>
          </div>
        </div>

        <!-- Invoice Management Section -->
        <div class="col-md-6">
          <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-success text-white">
              <h5 class="mb-0"><i class="bi bi-receipt"></i> Invoice Management</h5>
            </div>
            <div class="card-body">
              <div class="d-grid gap-2">
                <a href="#" class="btn btn-outline-success btn-lg">
                  <i class="bi bi-plus-circle"></i> Create Invoice
                </a>
                <a href="#" class="btn btn-outline-secondary btn-lg">
                  <i class="bi bi-list-ul"></i> Invoice List
                </a>
              </div>
            </div>
          </div>
        </div>

        <!-- Customer Management Section -->
        <div class="col-md-6">
          <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-warning text-dark">
              <h5 class="mb-0"><i class="bi bi-people"></i> Customer Management</h5>
            </div>
            <div class="card-body">
              <div class="d-grid gap-2">
                <a href="#" class="btn btn-outline-warning btn-lg">
                  <i class="bi bi-person-plus"></i> Add Customer
                </a>
                <a href="#" class="btn btn-outline-secondary btn-lg">
                  <i class="bi bi-people-fill"></i> Customer List
                </a>
              </div>
            </div>
          </div>
        </div>

        <!-- Payment Management Section -->
        <div class="col-md-6">
          <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-info text-white">
              <h5 class="mb-0"><i class="bi bi-credit-card"></i> Payment Management</h5>
            </div>
            <div class="card-body">
              <div class="d-grid gap-2">
                <a href="#" class="btn btn-outline-info btn-lg">
                  <i class="bi bi-cash-coin"></i> Make Payment
                </a>
                <a href="#" class="btn btn-outline-secondary btn-lg">
                  <i class="bi bi-journal-text"></i> View Ledger
                </a>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Notice Board Section -->
    <div class="col-lg-4">
      <div class="card border-0 shadow-sm h-100">
        <div class="card-header bg-dark text-white">
          <h5 class="mb-0"><i class="bi bi-megaphone"></i> Notice Board</h5>
        </div>
        <div class="card-body">
          <div class="list-group list-group-flush">
            <div class="list-group-item">
              <i class="bi bi-check-circle text-success"></i> Dashboard redesigned successfully!
            </div>
            <div class="list-group-item">
              <i class="bi bi-check-circle text-success"></i> Bootstrap 5 integration complete
            </div>
            <div class="list-group-item">
              <i class="bi bi-check-circle text-success"></i> Professional styling applied
            </div>
            <div class="list-group-item">
              <i class="bi bi-check-circle text-success"></i> Modern button design implemented
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Quick Stats Row -->
  <div class="row mt-4">
    <div class="col-12">
      <div class="row g-3">
        <div class="col-md-3">
          <div class="card border-0 bg-primary text-white">
            <div class="card-body text-center">
              <i class="bi bi-receipt fs-1"></i>
              <h4 class="mt-2">Invoices</h4>
              <p class="mb-0">Manage all invoices</p>
            </div>
          </div>
        </div>
        <div class="col-md-3">
          <div class="card border-0 bg-success text-white">
            <div class="card-body text-center">
              <i class="bi bi-people fs-1"></i>
              <h4 class="mt-2">Customers</h4>
              <p class="mb-0">Customer database</p>
            </div>
          </div>
        </div>
        <div class="col-md-3">
          <div class="card border-0 bg-warning text-dark">
            <div class="card-body text-center">
              <i class="bi bi-box fs-1"></i>
              <h4 class="mt-2">Items</h4>
              <p class="mb-0">Product catalog</p>
            </div>
          </div>
        </div>
        <div class="col-md-3">
          <div class="card border-0 bg-info text-white">
            <div class="card-body text-center">
              <i class="bi bi-cash-coin fs-1"></i>
              <h4 class="mt-2">Payments</h4>
              <p class="mb-0">Payment tracking</p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Design Features Showcase -->
  <div class="row mt-4">
    <div class="col-12">
      <div class="card border-0 shadow-sm">
        <div class="card-header bg-gradient text-white">
          <h5 class="mb-0"><i class="bi bi-star"></i> Design Features</h5>
        </div>
        <div class="card-body">
          <div class="row g-3">
            <div class="col-md-4">
              <div class="text-center">
                <i class="bi bi-palette text-primary fs-1"></i>
                <h5 class="mt-2">Modern Design</h5>
                <p class="text-muted">Professional gradient backgrounds and modern card layouts</p>
              </div>
            </div>
            <div class="col-md-4">
              <div class="text-center">
                <i class="bi bi-phone text-success fs-1"></i>
                <h5 class="mt-2">Responsive</h5>
                <p class="text-muted">Fully responsive design that works on all devices</p>
              </div>
            </div>
            <div class="col-md-4">
              <div class="text-center">
                <i class="bi bi-lightning text-warning fs-1"></i>
                <h5 class="mt-2">Fast & Smooth</h5>
                <p class="text-muted">Smooth animations and hover effects for better UX</p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Navigation Test -->
  <div class="row mt-4">
    <div class="col-12 text-center">
      <a href="index.php" class="btn btn-outline-light btn-lg me-3">
        <i class="bi bi-house"></i> Back to Login
      </a>
      <a href="dashboard.php" class="btn btn-success btn-lg">
        <i class="bi bi-speedometer2"></i> Go to Dashboard
      </a>
    </div>
  </div>
</div>

<?php include('footer.php');?> 