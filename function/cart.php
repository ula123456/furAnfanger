<?php 
function addProductToCart(int $userId, int $productId, int $qoantity = 1)
{   $sql = "INSERT INTO cart 
	SET  quantity =1, user_id = :userId, product_id = :productId 
	ON DUPLICATE KEY UPDATE quantity  = quantity +:quantity";

	$statment = getDB()->prepare($sql);
	$data =[
		':userId'=> $userId, 
		':productId'=>$productId,
		':quantity'=>$qoantity
	];
	echo str_replace(array_keys($data), array_values($data), $sql);
	$statment->execute($data);
	
}
function countProductInCart(int $userId){
	$sql = "SELECT COUNT(id) FROM cart WHERE user_id =".$userId;
	$cartResult = getDB()->query($sql);
	if ($cartResult === false) {
		var_dump(printDBErrorMessage());
		return 0;
	}

	$cartItems = $cartResult->fetchColumn();
	return $cartItems;
}

function getCartItemsForUserId(int $userId):array{
	$sql = "SELECT product_id, title,description,price,quantity 
		FROM cart
		JOIN products ON (cart.product_id = products.id)
		WHERE user_id = :userId";

	$statment = getDB()->prepare($sql);
	if (false === $statment) {
		return [];
	}
	$data = [
		':userId'=>$userId
	];
	$statment->execute($data);

	echo str_replace(array_keys($data),array_values($data),$sql);
	$found = [];

	while($row = $statment->fetch()){
		$found[]=$row;
	}
	return $found;
}
function getcartSumForUserid(int $userId): int{
	$sql = "SELECT SUM(price * quantity) 
		FROM cart
		JOIN products ON (cart.product_id = products.id)
		WHERE user_id =".$userId;
	$result = getDB()->query($sql);
	if ($result === false) {
		return 0;
	}
	return (int)$result->fetchColumn();
}
function deleteProductInCartorUserId(int $userId, int $productId):int{
	$sql ="DELETE FROM `cart` 
		   WHERE user_id = :userId
		   AND product_id = :productId";
		   
	$statment = getDB()->prepare($sql);
	if (false === $statment) {
		return 0;
	}
	$data =[
		':userId'=> $userId, 
		':productId'=>$productId
		];
	echo str_replace(array_keys($data), array_values($data), $sql);
	return $statment->execute($data	);

}

function moveCartProductToAnotherUser(int $sourceUserId, int $targetUserId):int{
	
	$oldCartItems = getCartItemsForUserId($sourceUserId);
	if (count($oldCartItems) === 0) {
		return 0;
	}
	$moveProducts = 0;
		foreach ($oldCartItems as $oldCartItem) {
		addProductToCart($targetUserId, 
					(int)$oldCartItem['product_id'], 
					(int)$oldCartItem['quantity']);										
		$moveProducts += deleteProductInCartorUserId($sourceUserId, (int)$oldCartItem['poroduct_id']);
		}
		
		return $moveProducts;
	
}