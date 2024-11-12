<?php
$url = $_SERVER['REQUEST_URI'];
$indexPHPPosition = strpos($url,'index.php');
$route = substr($url, $indexPHPPosition);
$baseUrl = $url;
if (false !== $indexPHPPosition) {
	$baseUrl = substr($url,0, $indexPHPPosition);
}

if (substr($baseUrl,-1)!=='/') {
	$baseUrl .='/';
}
define('BASE_URL',$baseUrl);

$route =null;

if (false !==$indexPHPPosition) {
	$route = substr($url, $indexPHPPosition);
	$route = str_replace('index.php', '', $route);

}
//// берет айди юзера
$userId = getCurrentUserId();
$countCartItemss = countProductInCart($userId);

if (!$route) {
	$products = getAllProduct();
	require __DIR__.'/templates/main.php';
	exit();
}
//// добаляет в карзину
if (strpos($route, '/cart/add/') !== false) {
	$routeParts = explode('/', $route);
	$productId = (int)$routeParts[3];

	addproductToCart($userId,$productId);

	header("Location: " .$baseUrl."index.php");
	exit();
	
}
//// показывает карзину
if (strpos($route, '/cart') !== false) {

	$cartItems = getCartItemsForUserId($userId);
	$cartSum = getcartSumForUserid($userId);
	require __DIR__.'/templates/cartPage.php';
	exit();
}
//// авторизаия пользователя
if (strpos($route, '/login') !== false) {
	     $isPost = isPost();
	     $username ="";
	     $password = "";
		 $errors =[];
	     $hasErrors = false;
	if ($isPost) {
		$username =filter_input(INPUT_POST, 'username', FILTER_SANITIZE_SPECIAL_CHARS);
		$password = filter_input(INPUT_POST, 'password');

		if (false === (bool)$username) { $errors[]="Benutzername ist leer"; }
		if (false === (bool)$password) { $errors[]="password ist leer "; }
		$userData = getUserDataForUsername($username);

		if ((bool)$username && 0 === count($userData)) {
			$errors[]="Benutzername exestert nicht";
		}

		if ((bool)$password && isset($userData['password']) &&
		false === password_verify($password, $userData['password'])
	   ){			$errors[]="Password stimmt nicht";	

	   	}

		if (0 === count($errors)) {
			$_SESSION['userId'] = (int)$userData['id'];
			moveCartProductToAnotherUser($_COOKIE['userId'],(int)$userData['id']);

			setcookie('userId',(int)$userData['id'],strtotime('+30 days'),$baseUrl);
			$redirectTarget = $baseUrl.'index.php';
			if (isset($_SESSION['redirectTarget'])) {
				$redirectTarget = $_SESSION['redirectTarget'];
			}
			header("Location: ". $redirectTarget);
			exit();
		}

	}
	
	$hasErrors = count($errors) > 0;
		
	require __DIR__.'/templates/login.php';
	exit();
}

//// чекаут
if(strpos($route, '/checkout') !== false) 
{
		redirectIfNotLogged('/checkout');
		$recipient ="";
		$city = "";
		$street ="";
		$streetNumber ="";
		$zipCode ="";
		$cityIsValid = true;
		$recipientIsValid = true;
		$streetIsValid = true;
		$streetNumberIsValid = true;
		$zipCodeIsValid = true;
		$errors =[];
		$hasErrors = count($errors) > 0;
		$deliveryAdresses = getDeliveryAdressesForUser($userId);
		require __DIR__.'/templates/selectDeliveryAdress.php';
		exit();
}
//// заканчивает сессию
	if (strpos($route,'/logout') !== false) {
		
		$redirectTarget = $baseUrl.'index.php';
			if (isset($_SESSION['redirectTarget'])) {
				$redirectTarget = $_SESSION['redirectTarget'];
			}
		session_regenerate_id(true);
		session_destroy();
		header("Location: ". $redirectTarget);
		exit();
	}

if(strpos($route, '/selectDeliveryAddress') !== false){
		redirectIfNotLogged('/selectDeliveryAddress');
		
		$routeParts = explode('/', $route);
		$deliveryAddresId = (int)$routeParts[2];
		if (deliveryAddressBelongsToUser($deliveryAddresId, $userId)) {
		
		$_SESSION['deliveryAddresId'] = $deliveryAddresId;
		header("Location: ".$baseUrl."index.php/selectPayment");
		exit();
		}
		
		exit();
}
//// доставить по адресу
if (strpos($route,'/deliveryAdress/add') !== false) {
	redirectIfNotLogged('/deliveryAdress/add');
	$recipient ="";
	$city = "";
	$street ="";
	$streetNumber ="";
	$zipCode ="";
	$cityIsValid = true;
	$recipientIsValid = true;
	$streetIsValid = true;
	$streetNumberIsValid = true;
	$zipCodeIsValid = true;
	$isPost = isPost();
	$errors =[];
	$deliveryAdresses = getDeliveryAdressesForUser($userId);

	exit();
	if (isPost()) {
		$recipient = filter_input(INPUT_POST, 'recipient', FILTER_SANITIZE_SPECIAL_CHARS);
		$recipient =trim($recipient);
		$city = filter_input(INPUT_POST, 'city', FILTER_SANITIZE_SPECIAL_CHARS);
		$city = trim($city);
		$street = filter_input(INPUT_POST, 'street', FILTER_SANITIZE_SPECIAL_CHARS);
		$streetNumber = filter_input(INPUT_POST, 'streetNumber', FILTER_SANITIZE_SPECIAL_CHARS);
		$zipCode = filter_input(INPUT_POST, 'zipCode', FILTER_SANITIZE_SPECIAL_CHARS);
		$isPost = isPost();
	
		if (!$recipient)   {$errors[]="Bitte Empfranger einterance"; $recipientIsValid = false;}
		if (!$city)        {$errors[]="Bitte stadit einterance"; $cityValid = false;}
		if (!$street)      {$errors[]="Bitte Shtrasse einterance"; $streetIsValid = false;}
		if (!$streetNumber){$errors[]="Bitte Shtrassenummer einterance"; $streetNumberIsValid = false;}
		if (!$zipCode)     {$errors[]="Bitte PLZ einterance"; $zipCodeIsValid = false;}

		if (count($errors)=== 0) {

		$deliveryAdresId = saveDeliveryAddressForUser(
										$userId, 
										$recipient, 
										$city, 
										$zipCode, 
										$street, 
										$streetNumber);
			
						if ($deliveryAdresId >0) {
						
						$_SESSION['deliveryAddresId'] = $deliveryAddresId;
						header("Location: ".$baseUrl."index.php/selectPayment");
						exit();
						}
		$errors[] = "errors was saved in Liferadress";
		}
	
}
	
	$hasErrors = count($errors) > 0;

	require __DIR__.'/templates/selectDeliveryAdress.php';
	exit();
}

if(strpos($route,'/selectPayment') !== false){
  redirectIfNotLogged('/selectPayment');
  require __DIR__.'/templates/selectPayment.php';
  
  exit();

} 
