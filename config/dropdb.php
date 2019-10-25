<?Php
	require 'database.php';
	$dsn = "mysql:host=".$DB_HOST;
	try {
		$db = new PDO($dsn, $DB_USER, $DB_PASSWORD);
	}
	catch (Exception $e) {
		print "Error message :\t" . $e->getMessage() . "\n";
		exit();
	}
	echo("connection to db ok!\n");
	$sql = "DROP DATABASE IF EXISTS " . "$DB_NAME";
	//Prepare the SQL query.
	$statement = $db->prepare($sql);
	//Execute the statement.
	$statement->execute();
?>