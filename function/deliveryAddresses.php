<?php
function saveDeliveryAddressForUser(
	   int $userId, 
	string $recipient, 
	string $city, 
	string $street,
	string $streetNumber,
	string $zipCode
		
):int{
	$sql = "INSERT INTO `delivery_adress`
	SET  user_id= :userId, 
		reciepient = :reciepient, 
		city = :city, 
		street = :street, 
		street_number = :street_number, 
		zip_code = :zip_code";


	$statment = getDB()->prepare($sql);

	if (false === $statment) {return 0;}

$statment->execute([
		 ':userId'=>$userId, 
		 ':reciepient'=>$recipient,
		 ':city'=>$city,
		 ':street' => $street,
		 ':street_number'=>$streetNumber,
		 ':zip_code'=>$zipCode	
		]);
return (int)getDB()->lastInsertId();
	

}
function getDeliveryAdressesForUser(int $userId):array{
	$sql ="SELECT 
	`id`, 
	`reciepient`, 
	`city`, 
	`street`, 
	`street_number`, 
	`zip_code` 
	FROM `delivery_adress` WHERE `user_id` =:userId";

	$statment = getDB()->prepare($sql);

	if (false === $statment) {return [];}

	$addresses = [];
	$statment->execute([':userId'=>$userId]); 

	while($row = $statment->fetch()){
		$addresses[]=$row;
	}
	return $addresses;
}
function deliveryAddressBelongsToUser(int $deliveryAddresId, int $userId):bool{

		$sql ="SELECT `id`
				FROM `delivery_adress` 
				WHERE `user_id` = :userId AND id= :deliveryAddresId";

		$statment = getDB()->prepare($sql);

		if (false === $statment) {return false;}

		$addresses = [];
		$statment->execute([':userId'=>$userId,
							':deliveryAddresId'=>$deliveryAddresId
						  ]); 
		return (bool)$statment->rowCount();

}