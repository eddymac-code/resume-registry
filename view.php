<?php
require_once("pdo.php");

$sql = "SELECT * FROM profile WHERE profile_id = :profid";

$stmt = $pdo->prepare($sql);
$stmt->execute(array(
	':profid' => $_REQUEST['profile_id']
));

$row = $stmt->fetch(PDO::FETCH_ASSOC);

$fn = htmlentities($row['first_name']);
$ln = htmlentities($row['last_name']);
$em = htmlentities($row['email']);
$he = htmlentities($row['headline']);
$su = htmlentities($row['summary']);

?>

<!DOCTYPE html>
<html>
<head>
	<title>View Resume</title>
	<?php require_once("bootstrap.php"); ?>
</head>
<body>
<div class="container">
<h1>Profile Information</h1>


<?php

echo '<p>First Name: '.$fn."</p>\n";
echo '<p>Last Name: '.$ln."</p>\n";
echo '<p>Email: '.$em."</p>\n";
echo '<p>Headline:<br>'.$he."</p>\n";
echo '<p>Summary:<br>'.$su."</p>\n";
?>

<a href="index.php">Done</a>
</div>
</body>
</html>