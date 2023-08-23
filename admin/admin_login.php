<?php
include '../components/connect.php';

session_start();

if (isset($_POST['submit'])) {
    $name = $_POST['name'];
    $pass = $_POST['pass'];

    $message = '';

    if (empty(trim($name)) || empty(trim($pass))) {
        $message = 'Please enter your Username and Password';
    } else {
        $select_admin = $conn->prepare("SELECT * FROM `admins` WHERE name = ? AND password = ?");
        $select_admin->execute([$name, $pass]);
        $row = $select_admin->fetch(PDO::FETCH_ASSOC);

        if ($select_admin->rowCount() > 0) {
            $_SESSION['admin_id'] = $row['id'];
            header('location: dashboard.php');
            exit();
        } else {
            $message = 'Incorrect Username or Password';
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
        <title>Admin Login</title>

        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

        <link rel="stylesheet" href="../css/admin_style.css">
    </head>

    <body>
        <?php
    if (!empty($message)) {
        echo '<div class="message">' . $message . '</div>';
    }
    ?>

        <section class="form-container">
            <form action="" method="post">
                <h3>Admin Login</h3>
                <input type="text" name="name" required placeholder="Enter your username" maxlength="20" class="box"
                    oninput="this.value = this.value.replace(/\s/g, '')">
                <input type="password" name="pass" required placeholder="Enter your password" maxlength="20" class="box"
                    oninput="this.value = this.value.replace(/\s/g, '')">
                <input type="submit" value="Login now" class="btn" name="submit">
            </form>
        </section>
    </body>

</html>