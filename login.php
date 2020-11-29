<?php
require_once("pdo.php");
session_start();

if (isset($_POST['cancel'])) {
	header("Location: index.php");
	return;
}

$salt = 'XyZzy12*_';

if (isset($_POST['name']) && isset($_POST['email']) && isset($_POST['pass'])) {

	if (strlen($_POST['name']) < 1 || strlen($_POST['email']) < 1 || 
		strlen($_POST['pass']) < 1) {
		$_SESSION['error'] = "All values required";
		header("Location: login.php");
		return;
	}
	elseif (strpos($_POST['email'], '@') === false) {
		$_SESSION['error'] = "Invalid email";
		header("Location: login.php");
		return;
	}
	else {
		$check = hash('md5', $salt.$_POST['pass']);

		$sql = "SELECT user_id, name FROM users WHERE email = :em
		AND password = :pw";
		$stmt = $pdo->prepare($sql);
		$stmt->execute(array(
			':em' => $_POST['email'],
			':pw' => $check
		));

		$row = $stmt->fetch(PDO::FETCH_ASSOC);

		if ($row !== false) {
			$_SESSION['name'] = $row['name'];
			$_SESSION['user_id'] = $row['user_id'];
			header("Location: index.php");
			return;
		}
		else {
			$_SESSION['error'] = "Incorrect password";
			header("Location: login.php");
			return;
		}
	}
}
?>
<!DOCTYPE html>
<html>
<head>
	<title>Login</title>
	<?php require_once("bootstrap.php"); ?>
</head>
<body>
<div class="container">
<h1>Please Log in</h1>
<?php
if (isset($_SESSION['error'])) {
	echo '<p style="color:red">'.$_SESSION['error']."</p>\n";
	unset($_SESSION['error']);
}
?>
<form method="post">
<p>
Name: <input type="text" name="name">
</p>
<p>
Email: <input type="text" name="email" id="email">
</p>
<p>
Password: <input type="password" name="pass" id="id_1723">
</p>
<input type="submit" onclick="validateForm()" name="login" value="Log In">
<input type="submit" name="cancel" value="Cancel">
</form>
</div>

<script type="text/javascript">
function validateForm() {
	console.log('Validating...');
	try {
		addr = document.getElementById('email').value;
		pw = document.getElementById('id_1723').value;
		console.log("Validating addr="+addr+" pw="+pw);
		if (addr == null || addr == "" || pw == null || pw == "") {
			alert("Please input all values");
			return false;
		} 
		if (addr.indexOf('@') == -1) {
			alert("Invalid email address");
			return false;
		}
		return true;
	} catch(e) {
		return false;
	}
	return false;
}	
</script>
</body>
</html>