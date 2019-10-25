<?php
	try {
		$db = new PDO($DB_DSN, $DB_USER, $DB_PASSWORD);
		$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	}
	catch (Exception $e) {
		print "Error message :\t" . $e->getMessage() . "\n";
		exit();
	}
?>