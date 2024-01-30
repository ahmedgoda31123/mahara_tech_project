<?php
    // establish the connectino with the DB
    $conn = mysqli_connect('localhost', 'ahmed', 'ahmed', 'itiProject');
    if (!$conn) {
        echo mysqli_connect_error();
        exit;
    }
    // get the id
    $id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);
    // delete the user
    $sql = "DELETE FROM users WHERE id = '" . $id . "' LIMIT 1;";
    if (mysqli_query($conn, $sql)) {
        header("Location: list.php");
        exit;
    }
    else {
        echo mysqli_error($conn);
    }

    // close the connection
    mysqli_close($conn);
?>