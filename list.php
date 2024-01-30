<?php
    require 'userClass.php';

    session_start();
    if (isset($_SESSION['id'])) {
        echo '<p> Welcome ' . $_SESSION['email'] . '<a href="logout.php">Logout</a></p>';
    } else {
        header('Location: login.php');
        exit;
    }

    $user = new User();
    $users = $user->getUsers();
    // connect to MYSQL
    // $conn = mysqli_connect('localhost', 'ahmed', 'ahmed', 'itiProject');
    // if (!$conn) {
    //     echo mysqli_connect_error();
    //     exit;
    // }

    // // Select all users
    // $sql = "SELECT * FROM `users`";

    // search by name or email
    if (isset($_GET['search'])) {
        $users = $user->searchUsers($_GET['search']);
        // $search = filter_input(INPUT_GET, 'search', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        // $sql .= " WHERE `users`.`name` LIKE '%".$search."%' OR `users`.`email` LIKE '%".$search."%'";
    }

    // $result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin :: List Users</title>
</head>
<body>
    <h1>List Users</h1>
    <form method="GET">
        <input type="text" name="search" placeholder="Enter {Name} or {Email} to search">
        <input type="submit" value="search">
    </form>
    <table>
        <thead>
            <tr>
                <th>id</th>
                <th>name</th>
                <th>email</th>
                <th>admin</th>
                <th>actions</th>
            </tr>
        </thead>

        <tbody>
            <?php 
                // loop on the rowset
                foreach($users as $row) {
            ?>
                <tr>
                    <td><?php echo $row['id']?></td>
                    <td><?php echo $row['name']?></td>
                    <td><?php echo $row['email']?></td>
                    <td><?php echo $row['admin']? 'Yes': 'No'?></td>
                    <td><a href="edit.php?id=<?php echo $row['id']?>">Edit</a> |
                        <a href="delete.php?id=<?php echo $row['id']?>">Delete</a></td>
                </tr>
            <?php }?>
        </tbody>

        <tfoot>
            <tr>
                <td colspan="20" style="text-align: center;"><?php echo count($users)?>:users</td>
                <td colspan="30" style="text-align: center;"><a href="add.php">Add user</a></td>
            </tr>
        </tfoot>
    </table>
</body>
</html>

<?php 
    mysqli_free_result($result);
    mysqli_close($conn);
?>