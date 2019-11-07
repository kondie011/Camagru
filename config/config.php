<?php
	include_once 'Install.class.php';
	include_once "database.php";

	session_start();
	$obj = new Config();
	if (!($conn = $obj->connect()))
	{
		$createDbQuery = "CREATE DATABASE `$dbname`";
		
		try {
			$dbh = new PDO("mysql:host=$servername", $username, $password);
			$dbh->exec($createDbQuery) or die("something went wrong");

			$conn = $obj->connect();
			$conn->exec("CREATE TABLE `user`(`username` varchar(20) not null, `password` varchar(255) not null, `email` varchar(50), `notif` BOOLEAN NOT NULL DEFAULT TRUE)");
			$conn->exec("CREATE TABLE `post`(`id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY, `username` varchar(20) not null, `caption` varchar(500), `image_path` varchar(500) not null, `date_created` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP)");
			$conn->exec("CREATE TABLE `post_like`(`id` INT not null, `username` varchar(20) not null)");
			$conn->exec("CREATE TABLE `post_comment`(`id` INT not null, `username` varchar(20) not null, `comment` VARCHAR(200) not null)");

			$conn->exec("ALTER DATABASE `$dbname` CHARACTER SET utf8mb4 COLLATE utf8mb4_bin");
			$conn->exec("ALTER TABLE `user` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_bin");
			$conn->exec("ALTER TABLE `post` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_bin");
			$conn->exec("ALTER TABLE `post_comment` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_bin");
			$conn->exec("COMMIT");

		} catch (PDOException $e) {
			die("DB ERROR: ". $e->getMessage());
		}
	}
	
	if(!$conn)
	{
		die("something went wrong".mysqli_connect_error());
	}
?>
