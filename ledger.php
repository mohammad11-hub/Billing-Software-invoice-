<?php
class Ledger {
    private $host = 'localhost';
    private $user = 'root';
    private $password = "";
    private $database = "billing_software";   
    private $CustomerTable = 'customers';	
    private $ReceiptTable = 'receipt';
    private $InvoiceTable = 'invoice_order';
    private $invoiceOrderItemTable = 'invoice_order_item';
    /** @var mysqli|false */
    private $dbConnect = false;
    
    public function __construct() {
        if(!$this->dbConnect) { 
            $conn = new mysqli($this->host, $this->user, $this->password, $this->database);
            if($conn->connect_error) {
                die("Error failed to connect to MySQL: " . $conn->connect_error);
            } else {
                $this->dbConnect = $conn;
            }
        }
    }
    
    private function getData($sqlQuery) {
        if (!$this->dbConnect || !($this->dbConnect instanceof mysqli)) {
            return array();
        }
        $result = mysqli_query($this->dbConnect, $sqlQuery);
        if(!$result) {
            die('Error in query: '. mysqli_error($this->dbConnect));
        }
        $data = array();
        while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
            $data[] = $row;            
        }
        return $data;
    }
    
    private function getSingleData($sqlQuery) {
        if (!$this->dbConnect || !($this->dbConnect instanceof mysqli)) {
            return array();
        }
        $result = mysqli_query($this->dbConnect, $sqlQuery);
        if(!$result) {
            die('Error in query: '. mysqli_error($this->dbConnect));
        }
        $data = mysqli_fetch_assoc($result);
        return $data ? $data : array();
    }

    private function getField($sqlQuery) {
        if (!$this->dbConnect || !($this->dbConnect instanceof mysqli)) {
            return null;
        }
        $result = mysqli_query($this->dbConnect, $sqlQuery);
        if(!$result) {
            die('Error in query: '. mysqli_error($this->dbConnect));
        }
        $data = mysqli_fetch_field($result);            
        return $data;
    }

    private function getNumRows($sqlQuery) {
        if (!$this->dbConnect || !($this->dbConnect instanceof mysqli)) {
            return 0;
        }
        $result = mysqli_query($this->dbConnect, $sqlQuery);
        if(!$result) {
            die('Error in query: '. mysqli_error($this->dbConnect));
        }
        $numRows = mysqli_num_rows($result);
        return $numRows;
    }
    
    public function viewCustomers($limit = 0) {
        $limitClause = $limit > 0 ? " LIMIT $limit" : "";
        $sqlQuery = "
            SELECT customer_id, created_date, customer_name, customer_address, customer_number, customer_pan 
            FROM ".$this->CustomerTable." 
            WHERE user_id = '".$_SESSION['userid']."'
            ORDER BY customer_name ASC".$limitClause;
        return $this->getData($sqlQuery);
    }	
    
    public function checkLoggedIn() {
        if(!isset($_SESSION['userid']) || empty($_SESSION['userid'])) {
            header("Location:index.php");
            exit;
        }
    }
    
    public function createReceipt($POST) {
        if (!$this->dbConnect || !($this->dbConnect instanceof mysqli)) {
            return false;
        }
        $userId = mysqli_real_escape_string($this->dbConnect, $POST['userId']);
        $customerId = mysqli_real_escape_string($this->dbConnect, $POST['customerId']);
        $invoiceId = mysqli_real_escape_string($this->dbConnect, $POST['invoiceNumber']);
        $amountPaid = mysqli_real_escape_string($this->dbConnect, $POST['paidAmount']);
        
        $sqlInsert = "
            INSERT INTO ".$this->ReceiptTable."(user_id, customer_id, invoice_id, amount_paid, created_date) 
            VALUES ('$userId', '$customerId', '$invoiceId', '$amountPaid', NOW())";		
        return mysqli_query($this->dbConnect, $sqlInsert);    	
    }		
    
    public function insertCustomer($POST) {		
        if (!$this->dbConnect || !($this->dbConnect instanceof mysqli)) {
            return false;
        }
        $userId = mysqli_real_escape_string($this->dbConnect, $POST['userId']);
        $companyName = mysqli_real_escape_string($this->dbConnect, $POST['companyName']);
        $companyAddress = mysqli_real_escape_string($this->dbConnect, $POST['companyAddress']);
        $companyPhone = mysqli_real_escape_string($this->dbConnect, $POST['companyPhone']);
        $companyPan = mysqli_real_escape_string($this->dbConnect, $POST['companyPan']);
        
        $sqlInsert = "
            INSERT INTO ".$this->CustomerTable."(user_id, customer_name, customer_address, customer_number, customer_pan, created_date) 
            VALUES ('$userId', '$companyName', '$companyAddress', '$companyPhone', '$companyPan', NOW())";		
        return mysqli_query($this->dbConnect, $sqlInsert);    	
    }	
    
    public function updateCustomer($POST) {
        if (!$this->dbConnect || !($this->dbConnect instanceof mysqli)) {
            return false;
        }
        if(isset($POST['customerId']) && !empty($POST['customerId'])) {	
            $userId = mysqli_real_escape_string($this->dbConnect, $POST['userId']);
            $customerId = mysqli_real_escape_string($this->dbConnect, $POST['customerId']);
            $companyName = mysqli_real_escape_string($this->dbConnect, $POST['companyName']);
            $companyAddress = mysqli_real_escape_string($this->dbConnect, $POST['companyAddress']);
            $companyPhone = mysqli_real_escape_string($this->dbConnect, $POST['companyPhone']);
            $companyPan = mysqli_real_escape_string($this->dbConnect, $POST['companyPan']);
            
            $sqlUpdate = "
                UPDATE ".$this->CustomerTable." 
                SET customer_name = '$companyName', 
                    customer_address = '$companyAddress', 
                    customer_number = '$companyPhone', 
                    customer_pan = '$companyPan' 
                WHERE user_id = '$userId' AND customer_id = '$customerId'";		
            return mysqli_query($this->dbConnect, $sqlUpdate);	
        }
        return false;
    }	
    
    public function getCustomerList() {
        $sqlQuery = "
            SELECT * FROM ".$this->CustomerTable." 
            WHERE user_id = '".$_SESSION['userid']."'
            ORDER BY customer_name ASC";
        return $this->getData($sqlQuery);
    }	
    
    public function getCustomer($customerId) {
        if (!$this->dbConnect || !($this->dbConnect instanceof mysqli)) {
            return false;
        }
        $customerId = mysqli_real_escape_string($this->dbConnect, $customerId);
        $sqlQuery = "
            SELECT * FROM ".$this->CustomerTable." 
            WHERE user_id = '".$_SESSION['userid']."' AND customer_id = '$customerId'";
        $result = mysqli_query($this->dbConnect, $sqlQuery);	
        if($result) {
            $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
            return $row;
        }
        return false;
    }	
	
    public function deleteCustomer($customerId) {
        if (!$this->dbConnect || !($this->dbConnect instanceof mysqli)) {
            return false;
        }
        $customerId = mysqli_real_escape_string($this->dbConnect, $customerId);
        $sqlQuery = "
            DELETE FROM ".$this->CustomerTable." 
            WHERE customer_id = '$customerId' AND user_id = '".$_SESSION['userid']."'";
        return mysqli_query($this->dbConnect, $sqlQuery);		
    }

    public function getTotalBalance($customerId) {
        if (!$this->dbConnect || !($this->dbConnect instanceof mysqli)) {
            return 0;
        }
        $customerId = mysqli_real_escape_string($this->dbConnect, $customerId);
        $sqlQuery = "
            SELECT COALESCE(SUM(order_total_amount_due), 0) as sum 
            FROM ".$this->InvoiceTable."
            WHERE customer_id = '$customerId' AND user_id = '".$_SESSION['userid']."'";
        $result = $this->getSingleData($sqlQuery);
        return $result['sum'];
    }

    public function initialPayment($invoiceId) {
        if (!$this->dbConnect || !($this->dbConnect instanceof mysqli)) {
            return array('paid' => 0, 'date' => '');
        }
        $invoiceId = mysqli_real_escape_string($this->dbConnect, $invoiceId);
        $sqlQuery = "
            SELECT COALESCE(order_amount_paid, 0) as paid, order_date as date 
            FROM ".$this->InvoiceTable."
            WHERE order_id = '$invoiceId' AND user_id = '".$_SESSION['userid']."'";
        return $this->getSingleData($sqlQuery);
    }

    public function getAllTransaction($customerId) {
        $customerId = mysqli_real_escape_string($this->dbConnect, $customerId);
        $sqlQuery = "
            SELECT order_id FROM ".$this->InvoiceTable."
            WHERE customer_id = '$customerId' AND user_id = '".$_SESSION['userid']."'
            ORDER BY order_date DESC";
        return $this->getData($sqlQuery);
    }

    public function TransactionDetails($invoiceId) {
        $invoiceId = mysqli_real_escape_string($this->dbConnect, $invoiceId);
        $sqlQuery = "
            SELECT 
                order_id as 'Id', 
                order_total_after_tax as 'Amount', 
                order_date as 'Date', 
                'Credit' as 'Type'
            FROM ".$this->InvoiceTable." 
            WHERE order_id = '$invoiceId' AND user_id = '".$_SESSION['userid']."'
            UNION
            SELECT 
                r.receipt_id as 'Id', 
                r.amount_paid as 'Amount', 
                r.created_date as 'Date', 
                'Debit' as 'Type'
            FROM ".$this->ReceiptTable." r
            JOIN ".$this->InvoiceTable." o ON o.order_id = r.invoice_id 
            WHERE o.order_id = '$invoiceId' AND o.user_id = '".$_SESSION['userid']."'
            ORDER BY Date ASC";
        return $this->getData($sqlQuery);
    }
    
    public function getCustomerTransactions($customerId) {
        $customerId = mysqli_real_escape_string($this->dbConnect, $customerId);
        $sqlQuery = "
            SELECT 
                'Invoice' as type,
                order_id as id,
                order_total_after_tax as amount,
                order_date as date,
                order_total_amount_due as due_amount
            FROM ".$this->InvoiceTable."
            WHERE customer_id = '$customerId' AND user_id = '".$_SESSION['userid']."'
            UNION ALL
            SELECT 
                'Payment' as type,
                receipt_id as id,
                amount_paid as amount,
                created_date as date,
                0 as due_amount
            FROM ".$this->ReceiptTable." r
            JOIN ".$this->InvoiceTable." o ON o.order_id = r.invoice_id
            WHERE o.customer_id = '$customerId' AND o.user_id = '".$_SESSION['userid']."'
            ORDER BY date DESC";
        return $this->getData($sqlQuery);
    }
    
    public function getCustomerSummary($customerId) {
        $customerId = mysqli_real_escape_string($this->dbConnect, $customerId);
        
        // Get total invoiced amount
        $sqlInvoiced = "
            SELECT COALESCE(SUM(order_total_after_tax), 0) as total_invoiced
            FROM ".$this->InvoiceTable."
            WHERE customer_id = '$customerId' AND user_id = '".$_SESSION['userid']."'";
        $invoiced = $this->getSingleData($sqlInvoiced);
        
        // Get total paid amount
        $sqlPaid = "
            SELECT COALESCE(SUM(r.amount_paid), 0) as total_paid
            FROM ".$this->ReceiptTable." r
            JOIN ".$this->InvoiceTable." o ON o.order_id = r.invoice_id
            WHERE o.customer_id = '$customerId' AND o.user_id = '".$_SESSION['userid']."'";
        $paid = $this->getSingleData($sqlPaid);
        
        // Get total due amount
        $sqlDue = "
            SELECT COALESCE(SUM(order_total_amount_due), 0) as total_due
            FROM ".$this->InvoiceTable."
            WHERE customer_id = '$customerId' AND user_id = '".$_SESSION['userid']."'";
        $due = $this->getSingleData($sqlDue);
        
        return array(
            'total_invoiced' => $invoiced['total_invoiced'],
            'total_paid' => $paid['total_paid'],
            'total_due' => $due['total_due']
        );
    }
}
?>
