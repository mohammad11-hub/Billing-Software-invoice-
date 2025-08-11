<?php 
  session_start();
  include('header.php');
  include 'Invoice.php';
  $invoice = new Invoice();
  $invoice->checkLoggedIn();

  // Check if PHPSpreadsheet is available
  $excelEnabled = false;
  if (file_exists('vendor/autoload.php')) {
      require 'vendor/autoload.php'; // PHPSpreadsheet autoload
      $excelEnabled = true;
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
          <i class="bi bi-box"></i> Item List
        </h2>
        <a class="btn btn-outline-secondary" href="javascript:history.go(-1)">
          <i class="bi bi-arrow-left"></i> Go Back
        </a>
      </div>

      <!-- Import Status Messages -->
      <?php if (isset($_SESSION['import_success'])): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
          <i class="bi bi-check-circle"></i> <?php echo $_SESSION['import_success']; ?>
          <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php unset($_SESSION['import_success']); ?>
      <?php endif; ?>

      <?php if (isset($_SESSION['import_error'])): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
          <i class="bi bi-exclamation-triangle"></i> <?php echo $_SESSION['import_error']; ?>
          <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php unset($_SESSION['import_error']); ?>
      <?php endif; ?>

      <!-- Import Excel Section -->
      <div class="card border-0 shadow-sm mb-4">
        <div class="card-header bg-info text-white">
          <h5 class="mb-0"><i class="bi bi-file-earmark-excel"></i> Import Excel File</h5>
        </div>
        <div class="card-body">
          <form action="import_excel.php" method="post" enctype="multipart/form-data">
            <div class="row">
              <div class="col-md-8">
                <div class="mb-3">
                  <label for="excel_file" class="form-label">Select Excel/CSV File:</label>
                  <input type="file" class="form-control" name="excel_file" id="excel_file" accept=".xlsx,.xls,.csv" required>
                  <div class="form-text">Supported formats: .xlsx, .xls, .csv (Max size: 5MB)</div>
                </div>
              </div>
              <div class="col-md-4 d-flex align-items-end">
                <button type="submit" class="btn btn-info w-100">
                  <i class="bi bi-upload"></i> Import Items
                </button>
              </div>
            </div>
          </form>
        </div>
      </div>

      <!-- Items Table -->
      <div class="card border-0 shadow-sm">
        <div class="card-header bg-primary text-white">
          <h5 class="mb-0"><i class="bi bi-list-ul"></i> Current Items</h5>
        </div>
        <div class="card-body">
          <?php 
          $ItemList = $invoice->getAllItems();
          if(empty($ItemList)){
            echo '<div class="alert alert-info" role="alert">
                    <i class="bi bi-info-circle"></i> No Items Added Yet! 
                    <a href="insert_item.php" class="alert-link">Add your first item</a>
                  </div>';	
          } else {  
          ?>	  
            <div class="table-responsive">
              <table id="data-table" class="table table-hover">
                <thead class="table-dark">
                  <tr>
                    <th>Item No.</th>
                    <th>Item Name</th>
                    <th>Price</th>
                    <th>Actions</th>
                  </tr>
                </thead>
                <tbody>
                  <?php		
                  foreach($ItemList as $ItemDetails){
                      echo '
                        <tr>
                          <td>'.$ItemDetails["item_number"].'</td>
                          <td>'.$ItemDetails["item_name"].'</td>
                          <td>₹'.$ItemDetails["item_price"].'</td>
                          <td>
                            <a href="edit_item.php?id='.$ItemDetails["item_number"].'" class="btn btn-sm btn-outline-primary">
                              <i class="bi bi-pencil"></i> Edit
                            </a>
                          </td>
                        </tr>
                      ';
                  }
                  ?>
                </tbody>
              </table>
            </div>
          <?php } ?>
        </div>
      </div>

      <!-- Imported Excel Data Display -->
      <?php
      // Show imported Excel data if available
      if (isset($_SESSION['excel_data']) && !empty($_SESSION['excel_data'])) {
          $excelData = $_SESSION['excel_data'];
          
          if (!empty($excelData) && count($excelData) > 1) {
              echo '<div class="card border-0 shadow-sm mt-4">
                      <div class="card-header bg-success text-white">
                        <h5 class="mb-0"><i class="bi bi-file-earmark-excel"></i> Imported Excel Data</h5>
                      </div>
                      <div class="card-body">
                        <div class="alert alert-info">
                          <i class="bi bi-info-circle"></i> 
                          <strong>' . (count($excelData) - 1) . ' items</strong> found in the Excel file. 
                          You can import individual items or all items at once.
                        </div>
                        <div class="table-responsive">
                          <table class="table table-bordered table-striped">
                            <thead class="table-success">';
              
              // Headers
              echo '<tr>';
              foreach ($excelData[0] as $header) {
                  echo '<th>' . htmlspecialchars($header) . '</th>';
              }
              echo '<th>Actions</th>';
              echo '</tr></thead><tbody>';
              
              // Data rows
              for ($i = 1; $i < count($excelData); $i++) {
                  if (!empty(array_filter($excelData[$i]))) { // Skip empty rows
                      echo '<tr>';
                      foreach ($excelData[$i] as $cell) {
                          echo '<td>' . htmlspecialchars($cell) . '</td>';
                      }
                      echo '<td>
                              <button class="btn btn-sm btn-success" onclick="importRow('.$i.')">
                                <i class="bi bi-plus-circle"></i> Import
                              </button>
                            </td>';
                      echo '</tr>';
                  }
              }
              
              echo '</tbody></table>
                    </div>
                    <div class="mt-3">
                      <button class="btn btn-success" onclick="importAllRows()">
                        <i class="bi bi-download"></i> Import All Items
                      </button>
                      <a href="item_list.php?clear_import=1" class="btn btn-secondary">
                        <i class="bi bi-trash"></i> Clear Import
                      </a>
                    </div>
                  </div></div>';
          }
      }
      ?>
    </div>
  </div>
</div>

<script>
// Function to import a single row
function importRow(rowIndex) {
    if (confirm('Are you sure you want to import this item?')) {
        $.ajax({
            url: 'action.php',
            method: 'POST',
            data: {
                action: 'import_single_row',
                row_index: rowIndex
            },
            dataType: 'json',
            success: function(response) {
                if (response.status == 1) {
                    alert('Success: ' + response.message);
                    location.reload(); // Reload to show the new item
                } else {
                    alert('Error: ' + response.message);
                }
            },
            error: function() {
                alert('Error: Failed to import item. Please try again.');
            }
        });
    }
}

// Function to import all rows
function importAllRows() {
    if (confirm('Are you sure you want to import all items? This will import all items from the Excel file.')) {
        $.ajax({
            url: 'action.php',
            method: 'POST',
            data: {
                action: 'import_excel_items'
            },
            dataType: 'json',
            success: function(response) {
                if (response.status == 1) {
                    alert('Success: ' + response.message);
                    location.reload(); // Reload to show the new items
                } else {
                    alert('Error: ' + response.message);
                }
            },
            error: function() {
                alert('Error: Failed to import items. Please try again.');
            }
        });
    }
}
</script>

<?php 
// Clear imported file session if requested
if (isset($_GET['clear_import']) && $_GET['clear_import'] == 1) {
    if (isset($_SESSION['imported_file']) && file_exists($_SESSION['imported_file'])) {
        unlink($_SESSION['imported_file']);
    }
    unset($_SESSION['imported_file']);
    unset($_SESSION['imported_filename']);
    unset($_SESSION['excel_data']);
    header('Location: item_list.php');
    exit;
}

include('footer.php');?>