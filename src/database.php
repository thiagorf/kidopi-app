<?php

class Database
{
	private $host = "mysql";
	private $user = "php";
	private $password = "php";
	private $database = "db";
	private $port = 3306;

	public function init()
	{
		try {
			$conn = new PDO("mysql:host=" . $this->host . ";dbname=" . $this->database . ";port=" . $this->port, $this->user, $this->password);
			$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

			$conn->query("CREATE DATABASE IF NOT EXISTS " . $this->database);
			$conn->query("CREATE TABLE IF NOT EXISTS todo (
				id INT AUTO_INCREMENT PRIMARY KEY,
				name VARCHAR(60) NOT NULL
			)");

			return $conn;
		} catch (PDOException $e) {
			//$conn->rollBack();
			die($e->getMessage());
		}
	}
}

$conn = (new Database())->init();
