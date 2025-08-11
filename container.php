</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-gradient-primary">
  <div class="container-fluid">
    <a class="navbar-brand fw-bold" href="dashboard.php">
      <i class="bi bi-calculator"></i> Billing System
    </a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav me-auto">
        <li class="nav-item">
          <a class="nav-link active" aria-current="page" href="dashboard.php">
            <i class="bi bi-house-door"></i> Dashboard
          </a>
        </li>
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="#" id="invoiceDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
            <i class="bi bi-receipt"></i> Invoices
          </a>
          <ul class="dropdown-menu" aria-labelledby="invoiceDropdown">
            <li><a class="dropdown-item" href="create_invoice.php"><i class="bi bi-plus-circle"></i> Generate Invoice</a></li>
            <li><a class="dropdown-item" href="invoice_list.php"><i class="bi bi-list-ul"></i> Invoice List</a></li>
            <li><hr class="dropdown-divider"></li>
            <li><a class="dropdown-item" href="print_invoice.php" target="_blank"><i class="bi bi-printer"></i> Print Invoice</a></li>
          </ul>
        </li>
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="#" id="itemDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
            <i class="bi bi-box"></i> Items
          </a>
          <ul class="dropdown-menu" aria-labelledby="itemDropdown">
            <li><a class="dropdown-item" href="insert_item.php"><i class="bi bi-plus-circle"></i> Add Item</a></li>
            <li><a class="dropdown-item" href="item_list.php"><i class="bi bi-list-ul"></i> Item List</a></li>
            <li><hr class="dropdown-divider"></li>
            <li>
              <form action="import_excel.php" method="post" enctype="multipart/form-data" class="d-inline">
                <label class="dropdown-item mb-0" style="cursor: pointer;">
                  <i class="bi bi-file-earmark-excel"></i> Import Excel
                  <input type="file" name="excel_file" accept=".xlsx,.xls,.csv" style="display:none;" onchange="this.form.submit()">
                </label>
              </form>
            </li>
          </ul>
        </li>
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="#" id="customerDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
            <i class="bi bi-people"></i> Customers
          </a>
          <ul class="dropdown-menu" aria-labelledby="customerDropdown">
            <li><a class="dropdown-item" href="create_customer.php"><i class="bi bi-person-plus"></i> Add Customer</a></li>
            <li><a class="dropdown-item" href="customer_list.php"><i class="bi bi-people-fill"></i> Customer List</a></li>
          </ul>
        </li>
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="#" id="paymentDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
            <i class="bi bi-credit-card"></i> Payments
          </a>
          <ul class="dropdown-menu" aria-labelledby="paymentDropdown">
            <li><a class="dropdown-item" href="receipt.php"><i class="bi bi-cash-coin"></i> Make Payment</a></li>
            <li><a class="dropdown-item" href="ledger_page.php"><i class="bi bi-journal-text"></i> View Ledger</a></li>
            <li><a class="dropdown-item" href="transactions.php"><i class="bi bi-arrow-left-right"></i> Transactions</a></li>
          </ul>
        </li>
      </ul>
      <ul class="navbar-nav">
        <?php if($_SESSION['userid']) { ?>
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
              <i class="bi bi-person-circle"></i> <?php echo $_SESSION['user']; ?>
            </a>
            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
              <li><a class="dropdown-item" href="#"><i class="bi bi-gear"></i> Account Settings</a></li>
              <li><hr class="dropdown-divider"></li>
              <li><a class="dropdown-item" href="logout.php?action=logout"><i class="bi bi-box-arrow-right"></i> Logout</a></li>
            </ul>
          </li>
        <?php } ?>
      </ul>
    </div>
  </div>
</nav>

<div class="container-fluid" style="min-height:100vh;">