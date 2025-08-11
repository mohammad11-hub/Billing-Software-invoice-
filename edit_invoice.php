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
		$invoice->updateInvoice($_POST);
		header("Location:invoice_list.php");	
	}
	else {
		$companyNameError = true;
	}
}
$customerList = $ledger->getCustomerList();
if(!empty($_GET['update_id'])){
	$invoiceId = $_GET['update_id'];
	$invoiceValue = $invoice->getInvoice($invoiceId);
	$invoiceItem = $invoice->getInvoiceItems($invoiceId);
}
?>
<script src="js/invoice.js"></script>
<link href="css/style.css" rel="stylesheet">
<?php include('container.php');?>
<div class="container content-invoice">
	<div><a class="btn btn-outline-secondary btn-sm" href="javascript:history.go(-1)"><i class="bi bi-arrow-left"></i> Go Back</a></div>
	<form action="" id="invoice-form" method="post" class="invoice-form" role="form" novalidate=""> 
		<div class="load-animate animated fadeInUp">
			<div class="row">
				<div class="col-8 col-sm-8 col-md-8 col-lg-8">
					<h2 class="title">Edit Invoice</h2>	
				</div>		    		
			</div>
			<input id="currency" type="hidden" value="$">
			<div class="row">
				<?php
					if($companyNameError)
					{
						echo '<div class="alert alert-danger" role="alert">Please select customer first!</div>';
					}
				?>
				<div class="col-12 col-sm-4 col-md-4 col-lg-4">
					<h3>From,</h3>
					<?php echo $_SESSION['user']; ?><br>	
					<?php echo $_SESSION['address']; ?><br>	
					<?php echo $_SESSION['mobile']; ?><br>
					<?php echo $_SESSION['email']; ?><br>	
				</div>      		
				<div class="col-12 col-sm-4 col-md-4 col-lg-4 ms-auto">
					<h3>To,</h3>
					<div class="mb-3">
						<select class="form-control" name="customerId">
							<option value="0">Select Customer</option>
							<?php
							foreach($customerList as $customerValue)
							{
								$selected = '';
								if($customerValue['customer_id'] == $invoiceValue['customer_id']) {
									$selected = 'selected="selected"';
								}
								echo '
								<option value="'.$customerValue["customer_id"].'" '.$selected.'>'.$customerValue["customer_name"].', '.$customerValue["customer_address"].'</option>';
							}
							 ?>
						
						</select>
					</div>
				</div>
			</div>
			<br/>
			<div class="row">
				<div class="col-12 col-sm-12 col-md-12 col-lg-12">
					<table class="table table-bordered table-hover" id="invoiceItem">	
						<tr>
							<th width="2%"><input id="checkAll" class="form-control" type="checkbox"></th>
							<th width="15%">Item No</th>
							<th width="38%">Item Name</th>
							<th width="15%">Quantity</th>
							<th width="15%">Price</th>								
							<th width="15%">Total</th>
						</tr>							
						<?php
						foreach($invoiceItem as $invoiceItemValue) {
							echo '
							<tr>
								<td><input class="itemRow" type="checkbox"></td>
								<td><input type="text" name="productCode[]" id="productCode_1" class="form-control" value="'.$invoiceItemValue["item_code"].'" autocomplete="off"></td>
								<td><input type="text" name="productName[]" id="productName_1" class="form-control" value="'.$invoiceItemValue["item_name"].'" autocomplete="off"></td>			
								<td><input type="number" name="quantity[]" id="quantity_1" class="form-control quantity" value="'.$invoiceItemValue["order_item_quantity"].'" autocomplete="off"></td>
								<td><input type="number" name="price[]" id="price_1" class="form-control price" value="'.$invoiceItemValue["order_item_price"].'" autocomplete="off"></td>
								<td><input type="number" name="total[]" id="total_1" class="form-control total" value="'.$invoiceItemValue["order_item_final_amount"].'" autocomplete="off"></td>
							</tr>';
						}
						?>
					</table>
				</div>
			</div>
			<div class="row">
				<div class="col-12 col-sm-3 col-md-3 col-lg-3">
					<button class="btn btn-outline-danger btn-sm me-2" id="removeRows" type="button">
						<i class="bi bi-trash"></i> Delete
					</button>
					<button class="btn btn-outline-success btn-sm" id="addRows" type="button">
						<i class="bi bi-plus-circle"></i> Add More
					</button>
				</div>
			</div>
			<div class="row">	
				<div class="col-12 col-sm-8 col-md-8 col-lg-8">
					<h3>Notes: </h3>
					<div class="mb-3">
						<textarea class="form-control txt" rows="5" name="notes" id="notes" placeholder="Your Notes"><?php echo $invoiceValue['note']; ?></textarea>
					</div>
					<br>
					<div class="mb-3">
						<input type="hidden" value="<?php echo $_SESSION['userid']; ?>" class="form-control" name="userId">
						<input type="hidden" value="<?php echo $invoiceValue['order_id']; ?>" class="form-control" name="invoiceId">
						<button type="submit" name="invoice_btn" class="btn btn-success btn-lg w-100" data-loading-text="Updating Invoice...">
							<i class="bi bi-save"></i> Save Invoice
						</button>						
					</div>
					
				</div>
		      		<div class="col-xs-12 col-sm-4 col-md-4 col-lg-4">
						<span class="form-inline">
							<div class="form-group">
								<label>Subtotal: &nbsp;</label>
								<div class="input-group">
									<div class="input-group-addon currency">$</div>
									<input value="<?php echo $invoiceValues['order_total_before_tax']; ?>" type="number" class="form-control" name="subTotal" id="subTotal" placeholder="Subtotal">
								</div>
							</div>
							<div class="form-group">
								<label>Tax Rate: &nbsp;</label>
								<div class="input-group">
									<input value="<?php echo $invoiceValues['order_tax_per']; ?>" type="number" class="form-control" name="taxRate" id="taxRate" placeholder="Tax Rate">
									<div class="input-group-addon">%</div>
								</div>
							</div>
							<div class="form-group">
								<label>Tax Amount: &nbsp;</label>
								<div class="input-group">
									<div class="input-group-addon currency">$</div>
									<input value="<?php echo $invoiceValues['order_total_tax']; ?>" type="number" class="form-control" name="taxAmount" id="taxAmount" placeholder="Tax Amount">
								</div>
							</div>							
							<div class="form-group">
								<label>Total: &nbsp;</label>
								<div class="input-group">
									<div class="input-group-addon currency">$</div>
									<input value="<?php echo $invoiceValues['order_total_after_tax']; ?>" type="number" class="form-control" name="totalAftertax" id="totalAftertax" placeholder="Total">
								</div>
							</div>
							<div class="form-group">
								<label>Amount Paid: &nbsp;</label>
								<div class="input-group">
									<div class="input-group-addon currency">$</div>
									<input value="<?php echo $invoiceValues['order_amount_paid']; ?>" type="number" class="form-control" name="amountPaid" id="amountPaid" placeholder="Amount Paid">
								</div>
							</div>
							<div class="form-group">
								<label>Amount Due: &nbsp;</label>
								<div class="input-group">
									<div class="input-group-addon currency">$</div>
									<input value="<?php echo $invoiceValues['order_total_amount_due']; ?>" type="number" class="form-control" name="amountDue" id="amountDue" placeholder="Amount Due">
								</div>
							</div>
						</span>
					</div>
		      	</div>
		      	<div class="clearfix"></div>		      	
	      	</div>
		</form>			
    </div>
</div>	
<?php include('footer.php');?>