<?php
include 'components/connect.php';
session_start();

if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
} else {
    $user_id = '';
}

if (isset($_POST['submit'])) {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $pass = $_POST['pass'];
    $cpass = $_POST['cpass'];

    // Server-side validation
    $message = [];

    // Check if name is empty or contains only whitespace
    if (empty(trim($name))) {
        $message[] = 'Please enter your username';
    }

    // Check if email is empty or contains only whitespace
    if (empty(trim($email))) {
        $message[] = 'Please enter your email';
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

    // If there are no messages, proceed with registration
    if (empty($message)) {
        $select_user = $conn->prepare("SELECT * FROM `users` WHERE email = ?");
        $select_user->execute([$email]);
        $row = $select_user->fetch(PDO::FETCH_ASSOC);

        if ($select_user->rowCount() > 0) {
            $message[] = 'Email already exists';
        } else {
            // Hash the password
            $hashedPassword = password_hash($pass, PASSWORD_DEFAULT);

            // Insert user data into the database
            $insert_user = $conn->prepare("INSERT INTO `users` (name, email, password) VALUES (?, ?, ?)");
            $insert_user->execute([$name, $email, $hashedPassword]);

            $message[] = 'Registered successfully! Please log in.';
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
        <title>Register</title>
        <!-- font awesome cdn link -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
        <!-- custom css file link -->
        <link rel="stylesheet" href="css/style.css">
    </head>

    <body>
        <?php include 'components/user_header.php'; ?>

        <section class="form-container">
            <form action="" method="post">
                <h3>Sign up now</h3>

                <input type="text" name="name" required placeholder="Enter your username" maxlength="20" class="box"
                    value="<?php echo isset($name) ? htmlspecialchars($name) : ''; ?>">
                <input type="email" name="email" required placeholder="Enter your email" maxlength="50" class="box"
                    value="<?php echo isset($email) ? htmlspecialchars($email) : ''; ?>">
                <input type="password" name="pass" required placeholder="Enter your password" minlength="8"
                    maxlength="20" class="box">
                <input type="password" name="cpass" required placeholder="Confirm your password" maxlength="20"
                    class="box">
                <input type="submit" value="Sign up now" class="btn" name="submit">
                <p>Already have an account? <a href="user_login.php" class="option-btn">Log in now</a></p>
            </form>
        </section>

        <?php include 'components/footer.php'; ?>
        <script src="js/script.js"></script>
    </body>

</html>