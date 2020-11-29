<?php
require_once("pdo.php");
session_start();
?>

<!DOCTYPE html>
<html>
<head>
	<title>Edwin Oduor's Resume Registry</title>
	<?php require_once("bootstrap.php"); ?>
</head>
<body>
<div class="container">
<h1>Edwin Oduor's Resume Registry</h1>

<?php
if (! isset($_SESSION['name'])) {
	echo '<a href="login.php">Please log in'."</a>\n";
	echo "<br><br>";

	// Get the table headers first 
	$sql = "SELECT * FROM profile";
	echo '<table border="1">'."\n";
	$stmt = $pdo->query($sql);
	$fields = $stmt->fetch(PDO::FETCH_ASSOC);

	if (! $fields) {
		echo " ";
	}
	else
	{
	echo "<tr><th>";
	echo "<strong>Name</strong>";
	echo "</th><th>";
	echo "<strong>Headline</strong>";
	echo "</th></tr>";

	// Get the table data now using another query
	$data = $pdo->query($sql);
	$row = false;

	while ($row = $data->fetch(PDO::FETCH_ASSOC)) {
		echo "<tr><td>\n";
		echo ('<a href="view.php?profile_id='.$row['profile_id'].'">'.htmlentities($row['first_name'].
			" ".$row['last_name'])."</a>\n");
		echo "</td><td>";
		echo (htmlentities($row['headline']));
		echo "</td></tr>\n";
	}
	echo "</table>\n";
	}

	echo "<br>";
	// echo '<a href="add.php">Add New Entry'."</a>\n";	
}
else {
	if (isset($_SESSION['success'])) {
		echo '<p style="color:green">'.$_SESSION['success']."</p>";
		unset($_SESSION['success']);
	}
	if (isset($_SESSION['error'])) {
		echo '<p style="color:red">'.$_SESSION['error']."</p>\n";
		unset($_SESSION['error']);
	}

	echo '<a href="logout.php">Logout'."</a>\n";
	echo "<br>";
	// Get the table headers first 
	$sql = "SELECT * FROM profile";
	echo '<table border="1">'."\n";
	$stmt = $pdo->query($sql);
	$fields = $stmt->fetch(PDO::FETCH_ASSOC);

	if (! $fields) {
		echo " ";
	}
	else
	{
	echo "<tr><th>";
	echo "<strong>Name</strong>";
	echo "</th><th>";
	echo "<strong>Headline</strong>";
	echo "</th><th>";
	echo "<strong>Action</strong>";
	echo "</th></tr>";

	// Get the table data now using another query
	$data = $pdo->query($sql);
	$row = false;

	while ($row = $data->fetch(PDO::FETCH_ASSOC)) {
		echo "<tr><td>\n";
		echo ('<a href="view.php?profile_id='.$row['profile_id'].'">'.htmlentities($row['first_name'].
			" ".$row['last_name'])."</a>\n");
		echo "</td><td>";
		echo (htmlentities($row['headline']));
		echo "</td><td>";
		echo '<a href="edit.php?profile_id='.$row['profile_id'].'">Edit '."</a>";
		echo '<a href="delete.php?profile_id='.$row['profile_id'].'">Delete'."</a>";
		echo "</td></tr>\n";
	}
	echo "</table>\n";
	}

}

?>
</div>
</body>
</html>