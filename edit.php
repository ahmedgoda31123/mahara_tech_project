<?php 
    $error_fields = array();
    // connect to the DB
    $conn = mysqli_connect('localhost', 'ahmed', 'ahmed', 'itiProject');
    if (!$conn) {
        echo mysqli_connect_error();
        exit;
    }
    // select the user
    $id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);
    $select = "SELECT * FROM users WHERE users.id = '" . $id . "' LIMIT 1";
    $result = mysqli_query($conn, $select);
    $row = mysqli_fetch_assoc($result);

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        // input validation
        if (!isset($_POST['name'])) {
            $error_fields[] = 'name';
        }
        if (!isset($_POST['email']) || !filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL)) {
            $error_fields[] = 'email';
        }

        if (!$error_fields) {
            // avoid SQL injection
            $id = filter_input(INPUT_POST, 'id', FILTER_SANITIZE_NUMBER_INT);
            $name = filter_input(INPUT_POST, $_POST['name'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $email = filter_input(INPUT_POST, $_POST['email'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $password = empty($_POST['password']) ? $row['password']:sha1($_POST['password']);
            $admin = isset($_POST['admin']) ? 1 : 0;
            // Update the Data
            $sql = "UPDATE users SET `name` = '" . $name . "', `email` = '" . $email . "', `password` = '" . sha1($password) . "' , `admin` = '" . $admin . "' WHERE users.id = '" . $id . "';";
            if (mysqli_query($conn, $sql)) {
                header("Location: list.php");
                exit;
            } else {
                echo mysqli_error($conn);
            }
        }
    }

    // close the connection
    mysqli_free_result($result);
    mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin :: Edit User</title>
</head>
<body>
    <form action="edit.php" method="POST">
        <label for="name">Name</label>
        <input type="hidden" name="id" id="id" value="<?php echo isset($row['id']) ? $row['id']:''; ?>">
        <input type="text" name="name" id="name" value="<?php echo isset($row['name']) ? $row['name']:''; ?>">
        <?php if(in_array("name", $error_fields)) echo "* Please enter your name";?> <br>
        <label for="email">Email</label>
        <input type="email" name="email" id="email" value="<?php isset($row['email']) ? $row['email']:'' ?>">
        <?php if(in_array("email", $error_fields)) echo "* Please enter a valid email";?> <br>
        <label for="password">Password</label>
        <input type="password" name="password" id="password">
        <?php if(in_array("password", $error_fields)) echo "* Please enter a password not less than 6 characters";?> <br>
        <input type="checkbox" name="admin" <?php echo $row['admin']? 'checked':''?>>Admin <br>
        <input type="submit" name="submit" value="Edit User">
    </form>
</body>
</html>