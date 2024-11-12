<!DOCTYPE html>
 <html lang="de">
 <head>
 	<title>vital photo</title>
   <base href="<?= $baseUrl ?>">
 	<meta charset="utf-8">
  	<link rel="stylesheet" href="assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/styles.css">
   
 </head>
 <body>
    <?php include __DIR__.'/navbar.php'?>
 	<header class="jumbotron">
 	 	<div class="container">
 			<h1>WELCOME our online shop</h1>
 		</div>
 	</header>

 	<section class="container" id="cartItems">
 		<div class="row">
      <h2>Cart</h2>
    </div>
    <div class="row cartItemHeader">
      <div class="col-12 text-right">Price</div>
    </div>
 <?php foreach ($cartItems as $cartItem):  ?>
 			<div class="row cartItem">
 <?php include __DIR__.'/cartItem.php'?>
 
 			</div>
 		<?php endforeach;?>
    <div class="row">
      <div class="col-12 text-right">
        total (<?= $countCartItemss ?>): <span class="price"><?= number_format( $cartSum/100,2,","," " )?>$</span>
      </div>
    </div>
    <div class="row">
      <a href="index.php/checkout" class="btn btn-primary col-12">total</a>
    </div>
 		</div>
 	</section>

 <script src="assets/js/bootstrap.bundle.js"></script>
 </body>
 </html>