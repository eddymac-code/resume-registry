<?php
require_once("pdo.php");
session_start();

if (! isset($_SESSION['name'])) {
	die("ACCESS DENIED");
}

if (isset($_POST['cancel'])) {
	header("Location: index.php");
	return;
}

if (isset($_POST['first_name']) && isset($_POST['last_name']) && isset($_POST['email']) &&
isset($_POST['headline']) && isset($_POST['summary'])) {
	
	if (strlen($_POST['first_name']) == 0 || strlen($_POST['last_name']) == 0 || strlen($_POST['email']) == 0 ||
    strlen($_POST['headline']) == 0 || strlen($_POST['summary']) == 0) {
		$_SESSION['error'] = "All fields are required";
		header("Location: add.php");
		return;
	}
	elseif (strpos($_POST['email'], '@') === false) {
		$_SESSION['error'] = "Email address must contain @";
		header("Location: add.php");
		return;
	}
	else {


		$query = "INSERT INTO profile (user_id, first_name, last_name, email,
		 headline, summary) VALUES (:uid, :fn, :ln, :em, :he, :su)";
		$stmt = $pdo->prepare($query);
		$stmt->execute(array(
			':uid' => $_SESSION['user_id'],
			':fn' => $_POST['first_name'],
			':ln' => $_POST['last_name'],
			':em' => $_POST['email'],
			':he' => $_POST['headline'],
			':su' => $_POST['summary']
		));
		$_SESSION['success'] = "Profile added";
		header("Location: index.php");
		return;
	}
}
?>

<!DOCTYPE html>
<html>
<head>
	<title>Add Resume</title>
	<?php require_once("bootstrap.php"); ?>
</head>
<body>
<div class="container">
	<?php
	echo '<h1>Adding profile for '.$_SESSION['name']."</h1>\n";

	if (isset($_SESSION['error'])) {
		echo '<p style="color:red">'.$_SESSION['error']."</p>\n";
		unset($_SESSION['error']);
	}
	?>
	<form method="post">
	<p>
	First Name: <input type="text" name="first_name" size="40">
	</p>
	<p>
	Last Name: <input type="text" name="last_name" size="40">
	</p>
	<p>
	Email: <input type="text" name="email" size="25">
	</p>
	<p>
	Headline:<br>
	<input type="text" name="headline" size="55">
	</p>
	<p>
	Summary:<br>
	<textarea name="summary"></textarea>
	</p>
	<input type="submit" value="Add">
	<input type="submit" name="cancel" value="Cancel">
	</form>
</div>
</body>
</html>