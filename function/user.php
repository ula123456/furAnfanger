<?php
function getCurrentUserId(){
	$userId = random_int(0, time());

	if (isset($_COOKIE['userId'])) {
		$userId = (int)$_COOKIE['userId'];
	}
	if (isset($_SESSION['userId'])) {
		$userId = (int)$_SESSION['userId'];
	}
	return $userId;
}
function getUserDataForUsername(string $username):array{
	$sql= "SELECT id,password
	FROM user
	WHERE username=:username";
	$statment = getDB()->prepare($sql);
	if (false === $statment) {
		return [];
	}
	$statment->execute([
		':username' =>$username
	]);
	if (0 === $statment->rowCount()) {
		return [];
	}
	$row = $statment->fetch();
	return $row;
}
function isLoggedIn():bool{
	return isset($_SESSION['userId']);

}