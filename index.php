<?php 
session_start();
include('header.php');
$loginError = '';
if (!empty($_POST['email']) && !empty($_POST['pwd'])) {
	include 'Invoice.php';
	$invoice = new Invoice();
	$user = $invoice->loginUsers($_POST['email'], $_POST['pwd']); 
	if(!empty($user)) {
		$_SESSION['user'] = $user[0]['first_name']."".$user[0]['last_name'];
		$_SESSION['userid'] = $user[0]['id'];
		$_SESSION['email'] = $user[0]['email'];		
		$_SESSION['address'] = $user[0]['address'];
		$_SESSION['mobile'] = $user[0]['mobile'];
		header("Location:dashboard.php");
	} else {
		$loginError = "Invalid email or password!";
	}
}
?>
<script src="js/invoice.js"></script>
</head>
<body>
<div class="container-fluid" style="min-height:100vh">
<div class="row d-flex justify-content-center align-items-center" style="min-height:;">	
	<div class="demo-heading">
		<h2 style="text-align: center ; margin-top:50px;">Welcome to Billing Software</h2>
	</div>
	<div class="login-form">		
		<h4>Admin Login:</h4>		
		<form method="post" action="">
			<div class="mb-3">
			<?php 
			if ($loginError) { ?>
				<div class="alert alert-warning"><?php echo $loginError; ?></div>
			<?php } ?>
			</div>
			<div class="mb-3">
				<input name="email" id="email" type="email" class="form-control" placeholder="Email address" autofocus="" required>
			</div>
			<div class="mb-3">
				<input type="password" class="form-control" name="pwd" placeholder="Password" required>
			</div>  
			<div class="mb-3">
				<button type="submit" name="login" class="btn btn-success btn-lg w-100">Login</button>
			</div>
		</form>		
	</div>		
</div>		
</div>
</div> 
<?php include('footer.php');?>