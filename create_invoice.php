<?php 
session_start();
include('header.php');
include 'Invoice.php';
include 'ledger.php';
$companyNameError = false;
$invoice = new Invoice();
$ledger = new Ledger();
$invoice->checkLoggedIn();
if(isset($_POST['invoice_btn']))
{
	if(!empty($_POST['customerId']) && $_POST['customerId']) {	
		$invoice->saveInvoice($_POST);
		header("Location:invoice_list.php");	
	}
	else {
		$companyNameError = true;
	}
}
$customerList = $ledger->getCustomerList();
?>
<script src="js/invoice.js"></script>
<?php include('container.php');?>

<div class="container-fluid py-5">
  <div class="row">
    <div class="col-12">
      <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="title mb-0">
          <i class="bi bi-receipt"></i> Create Invoice
        </h2>
        <a class="btn btn-outline-secondary" href="javascript:history.go(-1)">
          <i class="bi bi-arrow-left"></i> Go Back
        </a>
      </div>

      <!-- Error Messages -->
      <?php if($companyNameError): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
          <i class="bi bi-exclamation-triangle"></i> Please select customer first!
          <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
      <?php endif; ?>

      <form action="" id="invoice-form" method="post" class="invoice-form" role="form" novalidate=""> 
        <div class="load-animate animated fadeInUp">
          <input id="currency" type="hidden" value="$">
          
          <!-- Customer Information Section -->
          <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-primary text-white">
              <h5 class="mb-0"><i class="bi bi-people"></i> Customer Information</h5>
            </div>
            <div class="card-body">
              <div class="row">
                <div class="col-12 col-sm-6 col-md-6 col-lg-6">
                  <h5 class="text-primary mb-3">
                    <i class="bi bi-person"></i> From (Company)
                  </h5>
                  <div class="p-3 bg-light rounded">
                    <strong><?php echo $_SESSION['user']; ?></strong><br>	
                    <small class="text-muted"><?php echo $_SESSION['address']; ?></small><br>	
                    <small class="text-muted"><?php echo $_SESSION['mobile']; ?></small><br>
                    <small class="text-muted"><?php echo $_SESSION['email']; ?></small>
                  </div>
                </div>      		
                <div class="col-12 col-sm-6 col-md-6 col-lg-6">
                  <h5 class="text-primary mb-3">
                    <i class="bi bi-person-check"></i> To (Customer)
                  </h5>
                  <div class="mb-3">
                    <select class="form-select" name="customerId" required>
                      <option value="">Select Customer</option>
                      <?php
                      foreach($customerList as $customerValue)
                      {
                        echo '<option value="'.$customerValue["customer_id"].'">'.$customerValue["customer_name"].' - '.$customerValue["customer_address"].'</option>';
                      }
                      ?>
                    </select>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- Invoice Items Section -->
          <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-success text-white">
              <h5 class="mb-0"><i class="bi bi-box"></i> Invoice Items</h5>
            </div>
            <div class="card-body">
              <div class="table-responsive">
                <table class="table table-bordered table-hover" id="invoiceItem">	
                  <thead class="table-dark">
                    <tr>
                      <th width="2%"><input id="checkAll" class="form-check-input" type="checkbox"></th>
                      <th width="15%">Item No</th>
                      <th width="38%">Item Name</th>
                      <th width="15%">Quantity</th>
                      <th width="15%">Price</th>								
                      <th width="15%">Total</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr>
                      <td><input class="itemRow form-check-input" type="checkbox"></td>
                      <td><input type="text" name="productCode[]" id="productCode_1" class="form-control" placeholder="Item code" autocomplete="off"></td>
                      <td><input type="text" name="productName[]" id="productName_1" class="form-control" placeholder="Item name" autocomplete="off"></td>			
                      <td><input type="number" name="quantity[]" id="quantity_1" class="form-control quantity" placeholder="Qty" autocomplete="off"></td>
                      <td><input type="number" name="price[]" id="price_1" class="form-control price" placeholder="Price" autocomplete="off"></td>
                      <td><input type="number" name="total[]" id="total_1" class="form-control total" placeholder="Total" autocomplete="off"></td>
                    </tr>						
                  </tbody>
                </table>
              </div>
              
              <div class="row mt-3">
                <div class="col-12">
                  <button class="btn btn-outline-danger btn-sm me-2" id="removeRows" type="button">
                    <i class="bi bi-trash"></i> Delete Selected
                  </button>
                  <button class="btn btn-outline-success btn-sm" id="addRows" type="button">
                    <i class="bi bi-plus-circle"></i> Add More Items
                  </button>
                </div>
              </div>
            </div>
          </div>

          <!-- Invoice Summary Section -->
          <div class="row">
            <div class="col-12 col-sm-8 col-md-8 col-lg-8">
              <div class="card border-0 shadow-sm">
                <div class="card-header bg-info text-white">
                  <h5 class="mb-0"><i class="bi bi-chat-text"></i> Notes</h5>
                </div>
                <div class="card-body">
                  <div class="mb-3">
                    <textarea class="form-control" rows="5" name="notes" id="notes" placeholder="Enter any additional notes or terms..."></textarea>
                  </div>
                  <div class="mb-3">
                    <input type="hidden" value="<?php echo $_SESSION['userid']; ?>" class="form-control" name="userId">
                    <button type="submit" name="invoice_btn" class="btn btn-success btn-lg w-100" data-loading-text="Saving Invoice...">
                      <i class="bi bi-save"></i> Save Invoice
                    </button>						
                  </div>
                </div>
              </div>
            </div>
            
            <div class="col-12 col-sm-4 col-md-4 col-lg-4">
              <div class="card border-0 shadow-sm">
                <div class="card-header bg-warning text-dark">
                  <h5 class="mb-0"><i class="bi bi-calculator"></i> Invoice Summary</h5>
                </div>
                <div class="card-body">
                  <div class="mb-3">
                    <label class="form-label">Subtotal</label>
                    <div class="input-group">
                      <span class="input-group-text">₹</span>
                      <input value="" type="number" class="form-control" name="subTotal" id="subTotal" placeholder="0.00" readonly>
                    </div>
                  </div>
                  <div class="mb-3">
                    <label class="form-label">Tax Rate (%)</label>
                    <div class="input-group">
                      <input value="" type="number" class="form-control" name="taxRate" id="taxRate" placeholder="0" step="0.01">
                      <span class="input-group-text">%</span>
                    </div>
                  </div>
                  <div class="mb-3">
                    <label class="form-label">Tax Amount</label>
                    <div class="input-group">
                      <span class="input-group-text">₹</span>
                      <input value="" type="number" class="form-control" name="taxAmount" id="taxAmount" placeholder="0.00" readonly>
                    </div>
                  </div>							
                  <div class="mb-3">
                    <label class="form-label">Total Amount</label>
                    <div class="input-group">
                      <span class="input-group-text">₹</span>
                      <input value="" type="number" class="form-control" name="totalAftertax" id="totalAftertax" placeholder="0.00" readonly>
                    </div>
                  </div>
                  <div class="mb-3">
                    <label class="form-label">Amount Paid</label>
                    <div class="input-group">
                      <span class="input-group-text">₹</span>
                      <input value="" type="number" class="form-control" name="amountPaid" id="amountPaid" placeholder="0.00">
                    </div>
                  </div>
                  <div class="mb-3">
                    <label class="form-label">Amount Due</label>
                    <div class="input-group">
                      <span class="input-group-text">₹</span>
                      <input value="" type="number" class="form-control" name="amountDue" id="amountDue" placeholder="0.00" readonly>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </form>			
    </div>
  </div>
</div>

<?php include('footer.php');?>