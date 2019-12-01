<?php
include_once 'db_connect.php';

session_start();

if (!$_SESSION['loginStatus']) {
    header('Location: login.php');
    exit;
}

if (isset($_POST) and !empty($_POST)) {
	if ($_POST['type'] == 'new') {
        $sql = "INSERT INTO members (firstname, lastname, birthday, phone, email)
	            VALUES(:firstname, :lastname, :birthday, :phone, :email)";
        $result = $conn->prepare($sql);
        $res = $result->execute(
            array(
                ':firstname' => $_POST['firstname']
                ':lastname' => $_POST['lastname'],
                ':birthday' => $_POST['birthday'],
                ':phone' => $_POST['phone'],
                ':email' => $_POST['email'],
            )
        );
    } elseif ($_POST['type'] == 'delete') {
        $sql = "DELETE from members WHERE id=:id";
        $row = [
            ':id' => $_POST['id']
        ];
        $res = $conn->prepare($sql)->execute($row);
    }
}

if ($_GET['sortBy'] == 'idAsc') {
    $sql = "SELECT * FROM members ORDER BY id ASC";
} elseif ($_GET['sortBy'] == 'idDesc') {
    $sql = "SELECT * FROM members ORDER BY id DESC";
} elseif ($_GET['sortBy'] == 'nameAsc') {
    $sql = "SELECT * FROM members ORDER BY firstname ASC";
} elseif ($_GET['sortBy'] == 'nameDesc') {
    $sql = "SELECT * FROM members ORDER BY firstname DESC";
} else {
    $sql = "SELECT * FROM members";
}

$stmt = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <title>CRUD - Övning</title>
        <link rel="stylesheet" href="style.css" type="text/css">
    </head>
    <body>
    <ul>
	    <li>Logged in as: <?php echo $_SESSION['user']?></li>
	    <li><a href="logout.php">Log out</a></li>
    </ul>
    <form action="index.php" method="GET" id="sorts">
	    <br>
	    Sort by id asc: <input type="radio" name="sortBy" value="idAsc">
	    <br>
	    Sort by id desc: <input type="radio" name="sortBy" value="idDesc">
	    <br>
	    Sort by name asc: <input type="radio" name="sortBy" value="nameAsc">
	    <br>
	    Sort by name desc: <input type="radio" name="sortBy" value="nameDesc">
	    <br>
	    <input type="submit" value="Go!">
    </form>
    <h2>Members:</h2>
    <table>
        <tr>
            <th>Id</th>
            <th>Name</th>
            <th>Last name</th>
            <th>Birthday</th>
            <th>Phone</th>
            <th>Email</th>
        </tr>
        <?php while($row = $stmt->fetch(PDO::FETCH_ASSOC)) { ?>
            <tr>
                <td onclick="openUser('<?php echo $row['id']?>', '<?php echo $row['firstname']?>', '<?php echo $row['lastname']?>', '<?php echo $row['birthday']?>', '<?php echo $row['phone']?>', '<?php echo $row['email']?>')">
                    <a href="#update"><?php echo $row['id']?></a>
                </td>
                <td>
                    <?php echo $row['firstname']?>
                </td>
                <td>
                    <?php echo $row['lastname']?>
                </td>
                <td>
                    <?php echo $row['birthday']?>
                </td>
                <td>
                    <?php echo $row['phone']?>
                </td>
                <td>
                    <?php echo $row['email']?>
                </td>
            </tr>
        <?php } ?>
    </table>
    <div style="<?php
        if ($_SESSION['user'][1] == 'admin') {
        	echo 'display: block';
        } else {
            echo 'display: none';
        }
    ?>">
	    <h2>New User:</h2>
	    <form action="index.php" method="POST">
	        <label>Name: <input type="name" name="firstname" required></label>
	        <label>Last Name: <input type="name" name="lastname" required></label>
	        <label>Birthday: <input type="date" name="birthday" required></label>
	        <label>Phone: <input type="phone" name="phone" required></label>
	        <label>Email: <input type="email" name="email" required></label>
		    <input type="hidden" value="new" name="type">
	        <label>Add: <input type="submit" value="Add" required></label>
	    </form>
	    <h2 id="update">Update User:</h2>
	    <form action="index.php" method="POST">
		    <label>Name: <input type="name" name="firstname" required id="firstnameField"></label>
		    <label>Last Name: <input type="name" name="lastname" required id="lastnameField"></label>
		    <label>Birthday: <input type="date" name="birthday" required id="birthdayField"></label>
		    <label>Phone: <input type="phone" name="phone" required id="phoneField"></label>
		    <label>Email: <input type="email" name="email" required id="emailField"></label>
		    <input type="hidden" value="update" name="type">
		    <input type="hidden" name="id" id="idField">
		    <label>Update: <input type="submit" value="Update"></label>
	    </form>
	    <form action="index.php" method="POST">
		    <input type="hidden" value="delete" name="type">
		    <input type="hidden" name="id" id="idDeleteField">
		    <label>Delete: <input type="submit" value="Delete"></label>
	    </form>
    </div>

    </body>

	<script>
		function openUser(id, firstname, lastname, birthday, phone, email) {
			document.querySelector('#idField').value = id;
			document.querySelector('#idDeleteField').value = id;
			document.querySelector('#firstnameField').value = firstname;
			document.querySelector('#lastnameField').value = lastname;
			document.querySelector('#birthdayField').value = birthday;
			document.querySelector('#phoneField').value = phone;
			document.querySelector('#emailField').value = email;
		}
	</script>
</html>