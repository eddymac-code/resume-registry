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
	
	if (strlen($_POST['first_name']) == 0 || strlen($_POST['last_name']) == 0 ||
	 strlen($_POST['email']) == 0 || strlen($_POST['headline']) == 0 || 
	 strlen($_POST['summary']) == 0) {
		$_SESSION['error'] = "All fields are required";
		header("Location: edit.php?profile_id=".$_GET['profile_id']);
		return;
	}
	elseif (strpos($_POST['email'], '@') === false) {
		$_SESSION['error'] = "Email address must contain @";
		header("Location: edit.php?profile_id=".$_GET['profile_id']);
		return;
	}
	else {

		$query = "UPDATE profile SET first_name = :fn, last_name = :ln, email =
		 :em, headline = :he, summary = :su WHERE profile_id = :profid";
		$stmt = $pdo->prepare($query);
		$exec = $stmt->execute(array(
			':profid' => $_POST['profile_id'],
			':fn' => $_POST['first_name'],
			':ln' => $_POST['last_name'],
			':em' => $_POST['email'],
			':he' => $_POST['headline'],
			':su' => $_POST['summary']
		));
		$_SESSION['success'] = "Profile updated";
		header("Location: index.php");
		return;
	}
}

	if (! isset($_GET['profile_id'])) {
		$_SESSION['error'] = "Missing profile_id";
		header("Location: index.php");
		return;
	}

	// Get values to be reflected on the form
	$stmt = $pdo->prepare("SELECT * FROM profile WHERE profile_id = :profid");
	$stmt->execute(array(
		':profid' => $_GET['profile_id']
	));

	$row = $stmt->fetch(PDO::FETCH_ASSOC);

	if ($row === false) {
		$_SESSION['error'] = "Bad value for profile_id";
		header("Location: index.php");
		return;
	}

	$fn = htmlentities($row['first_name']);
	$ln = htmlentities($row['last_name']);
	$em = htmlentities($row['email']);
	$he = htmlentities($row['headline']);
	$su = htmlentities($row['summary']);
	$profile_id = $row['profile_id'];
?>

<!DOCTYPE html>
<html>
<head>
	<title>Edit Resume</title>
	<?php require_once("bootstrap.php"); ?>
</head>
<body>
<div class="container">
<h1>Update the profile</h1>
<?php
if (isset($_SESSION['error'])) {
	echo '<p style="color:red">'.$_SESSION['error']."</p>\n";
	unset($_SESSION['error']);
}


?>
<form method="post">
<p>
First Name: <input type="text" name="first_name" value="<?= $fn ?>" size="40">
</p>
<p>
Last Name: <input type="text" name="last_name" value="<?= $ln ?>" size="40">
</p>
<p>
Email: <input type="text" name="email" value="<?= $em ?>" size="25">
</p>
<p>
Headline:<br>
<input type="text" name="headline" value="<?= $he ?>" size="55">
</p>
<p>
Summary:<br>
<textarea name="summary"><?= $su ?></textarea>
</p>
<input type="hidden" name="profile_id" value="<?= $profile_id ?>">
<input type="submit" name="add" value="Save">
<input type="submit" name="cancel" value="Cancel">
</form>
</div>
</body>
</html>