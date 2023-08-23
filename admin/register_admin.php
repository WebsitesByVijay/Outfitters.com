<?php

include '../components/connect.php';

session_start();

$admin_id = $_SESSION['admin_id'];

if (!isset($admin_id)) {
    header('location:admin_login.php');
    exit();
}

if (isset($_POST['submit'])) {
    $name = $_POST['name'];
    $pass = $_POST['pass'];
    $cpass = $_POST['cpass'];

    $message = [];

    // Check if name is empty or contains only whitespace
    if (empty(trim($name))) {
        $message[] = 'Please enter your username';
    }

    // Check if password is empty or contains only whitespace
    if (empty(trim($pass))) {
        $message[] = 'Please enter your password';
    } elseif (strlen($pass) < 8) {
        $message[] = 'Password must be at least 8 characters long';
    }

    // Check if confirm password is empty or contains only whitespace
    if (empty(trim($cpass))) {
        $message[] = 'Please confirm your password';
    }

    // Check if passwords match
    if ($pass !== $cpass) {
        $message[] = 'Passwords do not match';
    }

    if (empty($message)) {
        $select_admin = $conn->prepare("SELECT * FROM `admins` WHERE name = ?");
        $select_admin->execute([$name]);

        if ($select_admin->rowCount() > 0) {
            $message[] = 'Username already exists';
        } else {
            $insert_admin = $conn->prepare("INSERT INTO `admins` (name, password) VALUES (?, ?)");
            $insert_admin->execute([$name, $pass]);
            $message[] = 'New admin registered successfully';
        }
    }
}

?>

<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Register Admin</title>

        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

        <link rel="stylesheet" href="../css/admin_style.css">

    </head>

    <body>

        <?php include '../components/admin_header.php'; ?>

        <section class="form-container">

            <form action="" method="post">
                <h3>New Admin Register</h3>

                <input type="text" name="name" required placeholder="Enter your username" maxlength="20" class="box"
                    oninput="this.value = this.value.replace(/\s/g, '')">
                <input type="password" name="pass" required placeholder="Enter your password" minlength="8"
                    maxlength="20" class="box" oninput="this.value = this.value.replace(/\s/g, '')">
                <input type="password" name="cpass" required placeholder="Confirm your password" minlength="8"
                    maxlength="20" class="box" oninput="this.value = this.value.replace(/\s/g, '')">
                <input type="submit" value="Register now" class="btn" name="submit">
            </form>

        </section>

        <script src="../js/admin_script.js"></script>

    </body>

</html>