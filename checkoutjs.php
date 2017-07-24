<?
	session_start();
	$_SESSION['POST']	= $_POST;
	
?>

<!doctype html>
<html class="no-js" dir="ltr" lang="en">
  <head>
    <meta charset="utf-8" />
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
	
	<!-- favicon -->
	<link rel="apple-touch-icon" sizes="57x57" href="lib/images/favicon/apple-icon-57x57.png">
	<link rel="apple-touch-icon" sizes="60x60" href="lib/images/favicon/apple-icon-60x60.png">
	<link rel="apple-touch-icon" sizes="72x72" href="lib/images/favicon/apple-icon-72x72.png">
	<link rel="apple-touch-icon" sizes="76x76" href="lib/images/favicon/apple-icon-76x76.png">
	<link rel="apple-touch-icon" sizes="114x114" href="lib/images/favicon/apple-icon-114x114.png">
	<link rel="apple-touch-icon" sizes="120x120" href="lib/images/favicon/apple-icon-120x120.png">
	<link rel="apple-touch-icon" sizes="144x144" href="lib/images/favicon/apple-icon-144x144.png">
	<link rel="apple-touch-icon" sizes="152x152" href="lib/images/favicon/apple-icon-152x152.png">
	<link rel="apple-touch-icon" sizes="180x180" href="lib/images/favicon/apple-icon-180x180.png">
	<link rel="icon" type="image/png" sizes="192x192"  href="lib/images/favicon/android-icon-192x192.png">
	<link rel="icon" type="image/png" sizes="32x32" href="lib/images/favicon/favicon-32x32.png">
	<link rel="icon" type="image/png" sizes="96x96" href="lib/images/favicon/favicon-96x96.png">
	<link rel="icon" type="image/png" sizes="16x16" href="lib/images/favicon/favicon-16x16.png">
	<link rel="manifest" href="lib/images/favicon/manifest.json">
	<meta name="msapplication-TileColor" content="#ffffff">
	<meta name="msapplication-TileImage" content="lib/images/favicon/ms-icon-144x144.png">
	<meta name="theme-color" content="#ffffff">
	
    <title>GEORGE VI | Your Travel Agency </title>
    <link rel="stylesheet" href="../../css/foundation.css" />
	<link rel="stylesheet" href="../../css/foundation-icons/foundation-icons.css" />
	<!-- Important Owl stylesheet -->
	<link rel="stylesheet" href="../../../../js/vendor/owl-carousel/owl.carousel.css">
	<link rel="stylesheet" href="../../js/vendor/owl-carousel/owl.transitions.css">

	<!-- countdown css -->
	<link rel="stylesheet" href="../../js/vendor/flipclock/css/flipclock.css">
	
	<!-- Default Theme -->
	<link rel="stylesheet" href="../../js/vendor/owl-carousel/owl.theme.css">
    <link rel="stylesheet" href="../../css/app.css" />
  </head>
  <body class="Lilac">

	<!-- mobile menu -->
	<div class="title-bar" data-responsive-toggle="site-menu" data-hide-for="medium">
		<button class="menu-icon" type="button" data-toggle></button>
		<div class="title-bar-title">Menu</div>
	</div>

	
	<!-- logo -->	

	<div class="row large-12 column Lilac">
		<div class="small-12 large-4 column "><img src="../../img/logo.jpg" alt="logo"></div>
		<div class="small-12 large-8 column small-centered">
			<h1>Hotel & Airlines Reservations 
			</h1>
		</div>
	</div>	

	<!-- top bar -->
    <div class="top-bar"> 
      <div class="row">
		  <div class="large-12 medium-10 columns"  style="overflow: hidden; padding-right: 0px">      
			<div class="top-bar-left " id="site-menu">
				<ul class="vertical medium-horizontal dropdown menu" data-dropdown-menu >
				<li class="active"><a href="index.html">Home</a></li>
				<li><a href="management.html">Customize A Package</a></li>
				
				<li><a href="faq.html">F.A.Q</a></li>
				<li><a href="contact.html">Contact us</a></li>
				</ul>
			</div>
		  </div>		
	  </div>
	</div>
	
	
	<!-- body -->
    <div class="row"> 
      <div class="large-12 medium-10 columns"  style="overflow: hidden; padding-right: 0px">      
		<div class="body" >
		
			<!-- details -->
			<div class="small-12 large-6 columns">
				
				<div class="small-6 columns">
					<label>Check-in Date: </label>
					<?= $_POST['date_in'] ?>
				</div>
				
				<div class="small-6 columns">
					<label>Check-out Date: </label>
					<?= $_POST['date_out'] ?>
				</div>
				
				<div class="small-3 columns">
					Adults: <?= $_POST['adults'] ?>
				</div>
				
				<div class="small-3 columns end">
					Children: <?= $_POST['children'] ?>
				</div>

				<div class="small-3 columns end">
					Rooms: <?= $_POST['rooms'] ?>
				</div>
				
				<div class="small-6 columns">
					<label>First Name: </label>
					<?= $_POST['first_name'] ?> 
				</div>
				
				<div class="small-6 columns">
					<label>Family Name:</label>
					<?= $_POST['family_name'] ?>
				</div>
								
				<div class="small-6 columns">
					<label>Mobile Number: </label>
					<?= $_POST['mobile'] ?>
				</div>
								
				<div class="small-6 columns">
					<label>Email address: </label>
					<?= $_POST['email'] ?>
				</div>
				
				
			</div>
			
			<div class="small-12 large-6 columns">
				<!-- Button Code for PayTabs Express Checkout -->
				<div class="PT_express_checkout" style="min-height: 800px; height: 800px"></div>
				&nbsp;
			</div>
			
		</div>
      </div>

    </div>

		
	<!-- footer -->
	<footer class="footer fixed-bottom">
		<nav class="top-bar bottom-bar">
			<div class="row">
				<div class="medium-4 column">
					&copy; 2016 GEORGE VI. All rights reserved. <br>
					Kingdom of Saudi Arabia, Dammam 31911
    			</div>
				<div class="medium-6 column">
					<ul class="menu vertical medium-horizontal">
						<li> <a href="terms.html">Terms and Condition</a></li>
						<li> <a href="privacy.html">Privacy </a></li>
						<li> <a href="policy.html">Cancellation Policy</a></li>
						<li> <a href="about.html">About </a></li>
					<ul>
    			</div>
			</div>
		</nav>
	</footer>	

    <script src="../../js/vendor/jquery.min.js"></script>
    <script src="../../js/vendor/what-input.min.js"></script>
    <script src="../../js/foundation.min.js"></script>
    <script src="../../js/app.js"></script>
  
	<!-- PayTabs code  -->
	<link rel="stylesheet" href="https://www.paytabs.com/express/express.css">
	<script src="https://www.paytabs.com/express/express_checkout_v3.js"></script>


<script type="text/javascript">
    Paytabs("#express_checkout").expresscheckout({
        settings:{
			secret_key:					"gqFjMlfPkiZk38H3ufVhafkIWOPTU2lbt1ufmK5G2516eI9IX508klQg9F93HveawJqSuwzxCADQiNzgSzocUet3UD7B8mMF6dyA",
			merchant_id:				"10009888",
            amount: 					"<?= ($_POST['amount'] *  $_POST['rooms']) ?>",
            currency: 					"<?= $_POST['currency'] ?>",
			title: 						"<?= $_POST['offer'] ?>",
			product_names: 				"<?= $_POST['product'] ?>",
            order_id: 					100101,
            url_redirect: 				"http://127.0.0.1/nawrs/result.php",
			display_billing_fields: 	1,
            display_shipping_fields: 	1,
            display_customer_info: 		1,
            language: 					"ar",
            redirect_on_reject: 		1,
            is_iframe:{
                load: 					"onbodyload",
                show: 					1,
            },
        },
        customer_info:{
            first_name: 				"<?= $_POST['first_name'] ?>",
            last_name: 					"<?= $_POST['family_name'] ?>",
            phone_number: 				"<?= $_POST['mobile'] ?>",
            country_code: 				"966",
            email_address: 				"<?= $_POST['email'] ?>"            
        }
    });
</script>
 
  </body>
</html>