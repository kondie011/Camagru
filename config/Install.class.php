<?php

	include_once "database.php";

    class Config
	{
		private $servername;
		private $username;
		private $password;
		private $dbname;
		private $charset;

		public function connect()
		{
            $this->servername = "localhost";
            $this->username = "root";
            $this->password = "654321";
			$this->dbname = "camagru";
			$this->charset = "utf8mb4";

			try
			{
				$dns = "mysql:host=".$this->servername.";dbname=".$this->dbname.";charset=".$this->charset;
				$conn = new PDO($dns, $this->username, $this->password);
				$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
				return $conn;
			}
			catch(PDOException $e)
			{
                //echo $e->getMessage();
                return (null);
			}
		}
	}

?>