<?php
	function jry_wb_connect_database()
	{
		try{ $dbh = new PDO("mysql:host=".constant('jry_wb_database_addr').";",constant('jry_wb_database_user_name'),constant('jry_wb_database_user_password')); } 
		catch (PDOException $e) {die ("Error!: " . $e->getMessage() . "<br/>"); }
		$dbh->query("SET NAMES utf8");
		$dbh->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
		return $dbh;
	}
	
?>
