<?php
require_once("pdo.php");
session_start();

if (! isset($_SESSION['name'])) {
	die("ACCESS DENIED");
}

if (isset($_POST['cancel'])) {
	header("Location: view.php");
	return;
}

if (isset($_POST['delete']) && isset($_POST['profile_id'])) {

$sql = "DELETE FROM profile WHERE profile_id = :profid";
$stmt = $pdo->prepare($sql);
$res = $stmt->execute(array(
	':profid' => $_POST['profile_id']
));

$_SESSION['success'] = "Record deleted";
header("Location: index.php");
return;
}

// Guardian - Make sure profile_id is present
if (! isset($_GET['profile_id'])) {
	$_SESSION['error'] = "Missing profile_id";
	header("Location: index.php");
	return;
}

$stmt = $pdo->prepare("SELECT first_name, last_name, profile_id FROM profile WHERE
	profile_id = :profid");

$stmt->execute(array('profid' => $_GET['profile_id']));
$row = $stmt->fetch(PDO::FETCH_ASSOC);
if ($row === false) {
	$_SESSION['error'] = "Missing profile_id";
	header("Location: index.php");
	return;
}

?>

<!DOCTYPE html>
<html>
<head>
	<title>Delete Resume</title>
	<?php require_once("head.php"); ?>
</head>
<body>
<div class="container">
<h1>Confirm:</h1>

<p>First Name: <?= htmlentities($row['first_name']); ?></p>
<p>Last Name: <?= htmlentities($row['last_name']); ?></p>

<form method="post">
	<input type="hidden" name="profile_id" value="<?= $row['profile_id'] ?>">
	<input type="submit" name="delete" value="Delete">
	<input type="submit" name="cancel" value="Cancel">		
</form>
</div>
</body>
</html>