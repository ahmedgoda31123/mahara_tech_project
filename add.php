<?php
    $error_fields = array();
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        // input validation
        if (!isset($_POST['name'])) {
            $error_fields[] = 'name';
        }
        if (!isset($_POST['email']) || !filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL)) {
            $error_fields[] = 'email';
        }
        if (!isset($_POST['password']) || strlen($_POST['password']) < 5) {
            $error_fields[] = 'password';
        }
        

        if (!$error_fields) {

            // connect to the DB
            $conn = mysqli_connect('localhost', 'ahmed', 'ahmed', 'itiProject');
            if (!$conn) {
                echo mysqli_connect_error();
                exit;
            }

            // Upload the file
            $uploads_dir = $_SERVER['DOCUMENT_ROOT'].'/uploads';
            $avatar = '';
            if ($_FILES['avatar']['error'] == UPLOAD_ERR_OK) {
                $tmp_name = $_FILES['avatar']['tmp_name'];
                $avatar = basename($_FILES['avatar']['name']);
                $check = move_uploaded_file($tmp_name, "$uploads_dir/$avatar");
            } else {
                echo "File can't be uploaded";
                exit;
            }

            // avoiding SQL injection
            $name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $password = sha1($_POST['password']);
            $admin = isset($_POST['admin']) ? 1 : 0;
            // insert the data
            $sql_query = "INSERT INTO users (`name`, `email`, `password`, `admin`) VALUES ('". $name . "', '" . $email . "', '" . $password . "', '" . $admin . "');";
            if (mysqli_query($conn, $sql_query)) {
                header("Location: list.php");
            }
            else {
                echo mysqli_error($conn);
            }

            // close the connection
            mysqli_close($conn);
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin :: Add User</title>
</head>
<body>
    <form action="add.php" method="POST" enctype="multipart/form-data">
        <label for="name">Name</label>
        <input type="text" name="name" id="name" value="<?php echo isset($_POST['name']) ? $_POST['name']:''?>">
        <?php if (in_array('name', $error_fields)) echo "* Please enter your name";?> <br>
        <label for="email">Email</label>
        <input type="email" name="email" id="email" value="<?php echo isset($_POST['email']) ? $_POST['email']:''?>">
        <?php if (in_array('email', $error_fields)) echo "* Please enter a valid email";?> <br>
        <label for="password">Password</label>
        <input type="password" name="password" id="password">
        <?php if (in_array("password", $error_fields)) echo "* Please enter a password that is not less than 6 characters";?> <br>
        <input type="checkbox" name="admin" <?php echo isset($_POST['admin'])? 'checked' : ''?>>Admin <br>
        <input type="file" name="avatar" id="avatar"> <br>
        <input type="submit" name="submit" value="Add User">
    </form>
</body>
</html>