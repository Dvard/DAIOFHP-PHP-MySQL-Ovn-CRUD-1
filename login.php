<?php
session_start();

include_once 'db_connect.php';

function attempt_login($conn) {
    $sql = "SELECT * FROM login WHERE usrname=:usrname AND password=:password";
    $row = [
        'usrname' => $_POST['usrname'],
        'password' => $_POST['password']
    ];
    $res = $conn->prepare($sql);
    $res->execute($row);

    if ($res->fetchColumn() < 1) {
    	return 0;
    } else {
    	return 1;
    }
}

function register($conn) {
    if ($_POST['password'] == $_POST['password_repeat']) {
        $sql = "INSERT INTO login (usrname, password)
            VALUES(:usrname, :password)";
        $row = [
            ':usrname' => $_POST['usrname'],
            ':password' => $_POST['password']
        ];

        $res = $conn->prepare($sql);

        return $res->execute($row);
    }
    return 0;
}

function login() {
    $_SESSION['loginStatus'] = true;
    $_SESSION['user'] = $_POST['usrname'];
    header("Refresh:0");
}

if ($_SESSION['loginStatus'] == true) {
    header('Location: '.'index.php');
    exit;
}

if (isset($_POST['type'])) {
    if ($_POST['type'] == 'login') {
        if (attempt_login($conn) > 0) {
            login();
        } else {
            $_SESSION['loginStatus'] = false;
        }

    } elseif ($_POST['type'] == 'register') {
    	if (register($conn) > 0) {
    		login();
	    }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <title>Login/Register</title>
    </head>
    <body>
    <h2>Sign In</h2>
        <form action="login.php" method="POST">
            <label>Username: <input type="text" name="usrname" required></label>
            <br>
            <label>Password: <input type="password" name="password" required></label>
            <br>
            <input name="type" value="login" type="hidden">
            <input type="submit" value="Sign In">
        </form>
        <h2>Register:</h2>
        <form action="login.php" method="POST">
            <label>Username: <input type="text" name="usrname" required></label>
            <br>
            <label>Password: <input type="password" name="password" required></label>
            <br>
            <label>Repeat Password: <input type="password" name="password_repeat" required></label>
            <br>
            <input name="type" value="register" type="hidden">
            <label>Register: <input type="submit" value="Register"></label>
        </form>
    </body>
</html>