<?php
include 'components/connect.php';
session_start();

if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
} else {
    $user_id = '';
}

if (isset($_POST['submit'])) {
    $email = $_POST['email'];
    $pass = $_POST['pass'];

    // Server-side validation
    $message = [];

    // Check if email is empty or contains only whitespace
    if (empty(trim($email))) {
        $message[] = 'Please enter your email';
    }

    // Check if password is empty or contains only whitespace
    if (empty(trim($pass))) {
        $message[] = 'Please enter your password';
    }

    // If there are no messages, proceed with login
    if (empty($message)) {
        $select_user = $conn->prepare("SELECT * FROM `users` WHERE email = ?");
        $select_user->execute([$email]);
        $row = $select_user->fetch(PDO::FETCH_ASSOC);

        if ($select_user->rowCount() > 0 && password_verify($pass, $row['password'])) {
            $_SESSION['user_id'] = $row['id'];
            header('Location: home.php');
            exit;
        } else {
            $message[] = 'Incorrect username or password';
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
        <title>Login</title>
        <!-- font awesome cdn link -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
        <!-- custom css file link -->
        <link rel="stylesheet" href="css/style.css">
    </head>

    <body>
        <?php include 'components/user_header.php'; ?>

        <section class="form-container">
            <form action="" method="post">
                <h3>Login now</h3>

                <input type="email" name="email" required placeholder="Enter your email" maxlength="50" class="box"
                    value="<?php echo isset($email) ? htmlspecialchars($email) : ''; ?>">
                <input type="password" name="pass" required placeholder="Enter your password" maxlength="20"
                    class="box">
                <input type="submit" value="Login now" class="btn" name="submit">
                <p>Don't have an account? <a href="user_register.php" class="option-btn">Sign up now</a></p>
            </form>
        </section>

        <?php include 'components/footer.php'; ?>
        <script src="js/script.js"></script>
    </body>

</html>