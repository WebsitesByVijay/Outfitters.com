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
    $name = filter_var($name, FILTER_SANITIZE_STRING);
    $email = $_POST['email'];
    $email = filter_var($email, FILTER_SANITIZE_STRING);

    $update_profile = $conn->prepare("UPDATE `users` SET name = ?, email = ? WHERE id = ?");
    $update_profile->execute([$name, $email, $user_id]);

    $empty_pass = 'da39a3ee5e6b4b0d3255bfef95601890afd80709';
    $prev_pass = $_POST['prev_pass'];
    $old_pass = $_POST['old_pass'];
    $new_pass = $_POST['new_pass'];
    $cpass = $_POST['cpass'];

    // Retrieve the hashed password for the current user from the database
    $select_user = $conn->prepare("SELECT password FROM `users` WHERE id = ?");
    $select_user->execute([$user_id]);
    $fetch_user = $select_user->fetch(PDO::FETCH_ASSOC);
    $hashed_password = $fetch_user['password'];
// Use password_verify to compare the user-provided old password with the hashed password
if (empty($old_pass)) {
    $message[] = 'Please Enter Old Password!';
} elseif (!password_verify($old_pass, $hashed_password)) {
    $message[] = 'Old Password Not Matched!';
} elseif ($new_pass != $cpass) {
    $message[] = 'Confirm Password Not Matched!';
} else {
    if ($new_pass != $empty_pass) {
        if (strlen($new_pass) < 8) {
            $message[] = 'New Password must be at least 8 characters long!';
        } else {
            // Hash the new password
            $hashed_new_password = password_hash($new_pass, PASSWORD_DEFAULT);
            
            // Update the new hashed password in the database
            $update_admin_pass = $conn->prepare("UPDATE `users` SET password = ? WHERE id = ?");
            $update_admin_pass->execute([$hashed_new_password, $user_id]);
            $message[] = 'Password Updated Successfully!';
        }
    } else {
        $message[] = 'Please Enter A New Password!';
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
        <title>register</title>

        <!-- font awesome cdn link  -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

        <!-- custom css file link  -->
        <link rel="stylesheet" href="css/style.css">

    </head>

    <body>

        <?php include 'components/user_header.php'; ?>

        <section class="form-container">

            <form action="" method="post">
                <h3>update now</h3>
                <input type="hidden" name="prev_pass" value="<?= $fetch_profile["password"]; ?>">
                <input type="text" name="name" required placeholder="Enter your username" maxlength="20" class="box"
                    value="<?= $fetch_profile["name"]; ?>">
                <input type="email" name="email" required placeholder="Enter your email" maxlength="50" class="box"
                    oninput="this.value = this.value.replace(/\s/g, '')" value="<?= $fetch_profile["email"]; ?>">
                <input type="password" name="old_pass" placeholder="Enter your old password" maxlength="20" class="box"
                    oninput="this.value = this.value.replace(/\s/g, '')">
                <input type="password" name="new_pass" placeholder="Enter your new password" maxlength="20" class="box"
                    oninput="this.value = this.value.replace(/\s/g, '')">
                <input type="password" name="cpass" placeholder="Confirm your new password" maxlength="20" class="box"
                    oninput="this.value = this.value.replace(/\s/g, '')">
                <input type="submit" value="update now" class="btn" name="submit">
            </form>

        </section>













        <?php include 'components/footer.php'; ?>

        <script src="js/script.js"></script>

    </body>

</html>