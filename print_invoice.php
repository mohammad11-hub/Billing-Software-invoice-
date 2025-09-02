<?php
session_start();
include('header.php');
include 'ledger.php';
include 'Invoice.php';
require("nepali-date.php");
$ledger = new Ledger();
$invoice = new Invoice();
$nepali_date = new nepali_date();
$invoice->checkLoggedIn();

// Check if invoice_id is provided
if(!empty($_GET['invoice_id']) && $_GET['invoice_id']) {
	$invoiceValues = $invoice->getInvoice($_GET['invoice_id']);		
	$invoiceItems = $invoice->getInvoiceItems($_GET['invoice_id']);
	$customerDetails = $ledger->getCustomer($invoiceValues['customer_id']);		
} else {
    // If no invoice_id, show a form to select invoice
    $invoiceList = $invoice->getInvoiceList();
}
$invoiceDate = isset($invoiceValues['order_date']) ? $invoiceValues['order_date'] : '';
$date = $invoiceDate ? $invoice->NepaliDate($invoiceDate, $nepali_date) : '';

// If no invoice selected, show selection form
if(empty($_GET['invoice_id'])) {
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Print Invoice - Billing System</title>
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link href="css/style.css" rel="stylesheet">
</head>

<body>
    <?php include('container.php');?>

    <div class="container-fluid py-5">
        <div class="row">
            <div class="col-lg-8 mx-auto">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0"><i class="bi bi-printer"></i> Print Invoice</h5>
                    </div>
                    <div class="card-body">
                        <form method="GET" action="print_invoice.php">
                            <div class="mb-3">
                                <label for="invoice_id" class="form-label">Select Invoice to Print:</label>
                                <select class="form-select" name="invoice_id" id="invoice_id" required>
                                    <option value="">Choose an invoice...</option>
                                    <?php foreach($invoiceList as $inv): ?>
                                    <option value="<?php echo $inv['order_id']; ?>">
                                        Invoice #<?php echo $inv['order_id']; ?> -
                                        <?php echo $inv['order_date']; ?> -
                                        ₹<?php echo $inv['order_total_after_tax']; ?>
                                    </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                                <a href="dashboard.php" class="btn btn-secondary me-md-2">
                                    <i class="bi bi-arrow-left"></i> Back to Dashboard
                                </a>
                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-printer"></i> Print Invoice
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php include('footer.php');?>
</body>

</html>
<?php
    exit;
}

// Generate the invoice HTML
$output = '
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Invoice #'.$invoiceValues['order_id'].' - Billing System</title>
    <style>
        @media print {
            body { margin: 0; padding: 0; }
            .no-print { display: none !important; }
            .invoice-container { box-shadow: none; border: 1px solid #ddd; }
        }
        
        body { 
            font-family: Arial, sans-serif; 
            margin: 0; 
            padding: 20px; 
            background: #f8f9fa; 
            font-size: 12px;
            line-height: 1.4;
        }
        .invoice-container { 
            max-width: 800px; 
            margin: 0 auto; 
            background: white; 
            padding: 30px; 
            border-radius: 10px; 
            box-shadow: 0 4px 12px rgba(0,0,0,0.1); 
        }
        .header { 
            text-align: center; 
            margin-bottom: 30px; 
            border-bottom: 2px solid #667eea; 
            padding-bottom: 20px; 
        }
        .company-info { 
            display: flex; 
            justify-content: space-between; 
            align-items: flex-start; 
            margin-bottom: 30px; 
            flex-wrap: wrap;
        }
        .company-details h2 { 
            color: #667eea; 
            margin: 0 0 10px 0; 
            font-size: 24px; 
        }
        .company-details p { 
            margin: 5px 0; 
            color: #666; 
        }
        .invoice-details { 
            background: #f8f9fa; 
            padding: 20px; 
            border-radius: 8px; 
            margin-bottom: 30px; 
        }
        .customer-info, .invoice-info { 
            flex: 1; 
            min-width: 300px;
        }
        .invoice-info { 
            text-align: right; 
        }
        .invoice-info h3 { 
            color: #667eea; 
            margin: 0 0 10px 0; 
        }
        table { 
            width: 100%; 
            border-collapse: collapse; 
            margin: 20px 0; 
        }
        th, td { 
            padding: 8px; 
            text-align: left; 
            border-bottom: 1px solid #ddd; 
        }
        th { 
            background: #667eea; 
            color: white; 
            font-weight: bold; 
        }
        tr:nth-child(even) { 
            background: #f8f9fa; 
        }
        .totals { 
            margin-top: 30px; 
            text-align: right; 
        }
        .totals table { 
            width: 300px; 
            margin-left: auto; 
        }
        .totals th { 
            background: #28a745; 
            color: white;
        }
        .amount-due { 
            background: #dc3545 !important; 
            color: white; 
            font-weight: bold; 
        }
        .print-btn { 
            position: fixed; 
            top: 20px; 
            right: 20px; 
            z-index: 1000; 
            background: #667eea;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            font-weight: bold;
        }
        .print-btn:hover {
            background: #5a6fd8;
        }
        @media print { 
            .print-btn { 
                display: none; 
            } 
        }
    </style>
</head>
<body>
    <a href="dashboard.php" class="print-btn no-print" style="position: fixed; top: 70px; right: 20px; z-index: 1000; text-decoration: none; background: #6c757d;">
    <i class="bi bi-arrow-left"></i> Back to Dashboard
</a>
    <button onclick="window.print()" class="print-btn no-print">
        <i class="bi bi-printer"></i> Print Invoice
    </button>
    
    <div class="invoice-container">
        <div class="header">
            <h1 style="color: #667eea; margin: 0;">INVOICE</h1>
        </div>
        
        <div class="company-info">
            <div class="company-details">
                <h2>PRABHAT AGROVET</h2>
                <p>Dumre, Tanahun, Nepal</p>
                <p>Phone: 065-580162, 9856080162</p>
                <p>PAN: 301522520</p>
            </div>
            <div class="invoice-info">
                <h3>Invoice Details</h3>
                <p><strong>Invoice No:</strong> '.htmlspecialchars($invoiceValues['order_id']).'</p>
                <p><strong>Date:</strong> '.htmlspecialchars($date['y'].'-'.$date['m'].'-'.$date['d']).'</p>
            </div>
        </div>
        
        <div class="invoice-details">
            <div style="display: flex; justify-content: space-between; flex-wrap: wrap;">
                <div class="customer-info">
                    <h4 style="color: #667eea; margin: 0 0 10px 0;">Bill To:</h4>
                    <p><strong>Name:</strong> '.htmlspecialchars($customerDetails['customer_name']).'</p>
                    <p><strong>Address:</strong> '.htmlspecialchars($customerDetails['customer_address']).'</p>
                    <p><strong>Phone:</strong> '.htmlspecialchars($customerDetails['customer_number']).'</p>
                </div>
            </div>
        </div>
        
        <table>
            <thead>
                <tr>
                    <th>Sr No.</th>
                    <th>Item Code</th>
                    <th>Item Name</th>
                    <th>Quantity</th>
                    <th>Price</th>
                    <th>Amount</th>
	</tr>
            </thead>
            <tbody>';

$count = 0;   
foreach($invoiceItems as $invoiceItem){
	$count++;
	$output .= '
	<tr>
                    <td>'.$count.'</td>
                    <td>'.htmlspecialchars($invoiceItem["item_code"]).'</td>
                    <td>'.htmlspecialchars($invoiceItem["item_name"]).'</td>
                    <td>'.htmlspecialchars($invoiceItem["order_item_quantity"]).'</td>
                    <td>₹'.htmlspecialchars($invoiceItem["order_item_price"]).'</td>
                    <td>₹'.htmlspecialchars($invoiceItem["order_item_final_amount"]).'</td>
	</tr>';
}

$output .= '
            </tbody>
        </table>
        
        <div class="totals">
            <table>
	<tr>
                    <th>Sub Total:</th>
                    <td>₹'.htmlspecialchars($invoiceValues['order_total_before_tax']).'</td>
	</tr>
	<tr>
                    <th>Tax Rate:</th>
                    <td>'.htmlspecialchars($invoiceValues['order_tax_per']).'%</td>
	</tr>
	<tr>
                    <th>Tax Amount:</th>
                    <td>₹'.htmlspecialchars($invoiceValues['order_total_tax']).'</td>
	</tr>
	<tr>
                    <th>Total:</th>
                    <td>₹'.htmlspecialchars($invoiceValues['order_total_after_tax']).'</td>
	</tr>
	<tr>
                    <th>Amount Paid:</th>
                    <td>₹'.htmlspecialchars($invoiceValues['order_amount_paid']).'</td>
	</tr>
                <tr class="amount-due">
                    <th>Amount Due:</th>
                    <td>₹'.htmlspecialchars($invoiceValues['order_total_amount_due']).'</td>
	</tr>
	</table>
        </div>
    </div>
    
    <script>
        // Auto-print when page loads (optional)
        // window.onload = function() { window.print(); }
    </script>
</body>
</html>';

// Check if user wants PDF or HTML
if (isset($_GET['format']) && $_GET['format'] === 'pdf') {
    // Try to create PDF using DOMPDF, but handle errors gracefully
$invoiceFileName = 'Invoice-'.$invoiceValues['order_id'].'.pdf';
    
    try {
        // Suppress deprecation warnings for DOMPDF
        error_reporting(E_ALL & ~E_DEPRECATED & ~E_STRICT);
        
        // Check if DOMPDF is available and compatible
        if (file_exists('dompdf/src/Autoloader.php')) {
require_once 'dompdf/src/Autoloader.php';
            
            // Check if the class exists before using it
            if (class_exists('Dompdf\Autoloader')) {
Dompdf\Autoloader::register();
                
                if (class_exists('Dompdf\Dompdf')) {
                    $dompdf = new Dompdf\Dompdf();
                    
                    // Set options to avoid deprecation warnings
                    $options = $dompdf->getOptions();
                    $options->setIsHtml5ParserEnabled(true);
                    $options->setIsPhpEnabled(true);
                    $options->setIsRemoteEnabled(true);
                    $options->setDefaultFont('Arial');
                    $dompdf->setOptions($options);
                    
                    // Load HTML content
$dompdf->loadHtml(html_entity_decode($output));
$dompdf->setPaper('A4', 'portrait');
                    
                    // Render PDF
$dompdf->render();
                    
                    // Output PDF
$dompdf->stream($invoiceFileName, array("Attachment" => false));
                    exit;
                }
            }
        }
        
        // If DOMPDF fails, redirect to HTML version
        header("Location: print_invoice.php?invoice_id=".$invoiceValues['order_id']."&format=html");
        exit;
        
    } catch (Exception $e) {
        // Log error and redirect to HTML version
        error_log("DOMPDF Error: " . $e->getMessage());
        header("Location: print_invoice.php?invoice_id=".$invoiceValues['order_id']."&format=html");
        exit;
    } catch (Error $e) {
        // Handle PHP 7+ errors
        error_log("DOMPDF Error: " . $e->getMessage());
        header("Location: print_invoice.php?invoice_id=".$invoiceValues['order_id']."&format=html");
        exit;
    } finally {
        // Restore error reporting
        error_reporting(E_ALL);
    }
} else {
    // Show HTML version (default)
    echo $output;
}
?>