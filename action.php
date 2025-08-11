<?php
session_start();
include 'ledger.php';
include 'Invoice.php';
$ledger = new Ledger();
$invoice = new Invoice();

	
if($_POST['action'] == 'delete_invoice' && $_POST['id']) {
	$invoice->deleteInvoice($_POST['id']);	
	$jsonResponse = array(
		"status" => 1	
	);
	echo json_encode($jsonResponse);	
}

if($_POST['action'] == 'delete_customer' && $_POST['id']) {
	$ledger->deleteCustomer($_POST['id']);	
	$jsonResponse = array(
		"status" => 1	
	);
	echo json_encode($jsonResponse);	
}

if($_POST['action'] == 'fill_fields' && $_POST['id']) {
	$details = $invoice->getItemDetails($_POST['id']);	
	$jsonResponse = array(
		"name" => $details->item_name,
		"price" => $details->item_price
	);
	echo json_encode($jsonResponse);	
}

if($_POST['action'] == 'import_excel_items') {
	$response = array();
	
	if (isset($_SESSION['excel_data']) && !empty($_SESSION['excel_data'])) {
		$result = $invoice->importExcelItems($_SESSION['excel_data']);
		
		if ($result['imported'] > 0) {
			$response['status'] = 1;
			$response['message'] = "Successfully imported " . $result['imported'] . " items.";
			if (!empty($result['errors'])) {
				$response['message'] .= " Errors: " . implode(", ", $result['errors']);
			}
			
			// Clear the session data after successful import
			unset($_SESSION['excel_data']);
			unset($_SESSION['imported_file']);
			unset($_SESSION['imported_filename']);
		} else {
			$response['status'] = 0;
			$response['message'] = "No items were imported. Errors: " . implode(", ", $result['errors']);
		}
	} else {
		$response['status'] = 0;
		$response['message'] = "No Excel data found to import.";
	}
	
	echo json_encode($response);
	exit;
}

if($_POST['action'] == 'import_single_row') {
	$response = array();
	$rowIndex = $_POST['row_index'];
	
	if (isset($_SESSION['excel_data']) && isset($_SESSION['excel_data'][$rowIndex])) {
		$row = $_SESSION['excel_data'][$rowIndex];
		
		// Validate and import single row
		$itemName = isset($row[0]) ? trim($row[0]) : '';
		$itemPrice = isset($row[1]) ? trim($row[1]) : '';
		
		// If we have 3 columns, assume: Item Name, Item Code, Price
		if (count($row) >= 3 && !empty($row[1]) && is_numeric($row[2])) {
			$itemName = trim($row[0]);
			$itemPrice = trim($row[2]);
		}
		
		if (empty($itemName)) {
			$response['status'] = 0;
			$response['message'] = "Item name is required";
		} elseif (!is_numeric($itemPrice) || $itemPrice <= 0) {
			$response['status'] = 0;
			$response['message'] = "Valid price is required";
		} else {
			// Check if item already exists using the Invoice class method
			$existingItems = $invoice->getAllItems();
			$itemExists = false;
			foreach ($existingItems as $item) {
				if (strtolower($item['item_name']) === strtolower($itemName)) {
					$itemExists = true;
					break;
				}
			}
			
			if ($itemExists) {
				$response['status'] = 0;
				$response['message'] = "Item '$itemName' already exists";
			} else {
				// Use the existing insertItems method
				$postData = array(
					'userId' => $_SESSION['userid'],
					'productName' => array($itemName),
					'price' => array($itemPrice)
				);
				
				$invoice->insertItems($postData);
				$response['status'] = 1;
				$response['message'] = "Item '$itemName' imported successfully";
			}
		}
	} else {
		$response['status'] = 0;
		$response['message'] = "Row data not found";
	}
	
	echo json_encode($response);
	exit;
}
?>



