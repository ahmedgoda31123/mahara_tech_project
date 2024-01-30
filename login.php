<?php
    session_start();
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $conn = mysqli_connect('localhost', 'ahmed', 'ahmed', 'itiProject');
        if (!$conn) {
            echo mysqli_connect_error();
            exit;
        }
        $email = htmlspecialchars($_POST['email']);
        $password = sha1($_POST['password']);

        $sql = "SELECT * FROM `users` WHERE `email` = '" . $email . "' and `password` = '" . $password . "' LIMIT 1;";
        $result = mysqli_query($conn, $sql);
        if ($row = mysqli_fetch_assoc($result)) {   
            $_SESSION['id'] = $row['id'];
            $_SESSION['email'] = $row['email'];
            header("Location: list.php");
            exit;
        } else {
            $error = "Invalid email or password";
        }

        mysqli_free_result($result);
        mysqli_close($conn);
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
</head>
<body>
    <?php if (isset($error)) echo $error;?>
    <form method="POST">
        <label for="email">Email</label>
        <input type="email" name="email" id="email" value="<?php echo isset($_POST['email'])? $_POST['email']:'';?>"> <br>
        <label for="password">Password</label>
        <input type="password" name="password" id="password"> <br>
        <br>
        <input type="submit" name="submit" value="login">
    </form>
</body>
</html>