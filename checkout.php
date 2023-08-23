<?php

include 'components/connect.php';

session_start();

// Initialize the $message array
$message = [];

if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
} else {
    $user_id = '';
    header('location:user_login.php');
}
;

if (isset($_POST['order'])) {
    // Retrieve form data
    $name = $_POST['name'];
    $number = $_POST['number'];
    $email = $_POST['email'];
    $method = $_POST['method'];
    $address = $_POST['flat'] . ', ' . $_POST['street'] . ', ' . $_POST['city'] . ', ' . $_POST['state'] . ', ' . $_POST['country'] . ' - ' . $_POST['pin_code'];
    $total_products = $_POST['total_products'];
    $total_price = $_POST['total_price'];

    // Validate form data
    if (empty($name)) {
        $e[] = 'Please Enter Your Name.';
    }

    if (empty($number)) {
        $message[] = 'Please Enter your contact number.';
    } elseif (strlen($number) < 10 || !preg_match('/^[0-9]{10}$/', $number)) {
        $message[] = 'Please Enter a valid contact number with 10 digits.';
    }

    if (empty($email)) {
        $message[] = 'Please Enter your email.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $message[] = 'Please Enter a valid email address.';
    }

    if (empty($method)) {
        $message[] = 'Please select a payment method.';
    }

    if (empty($address)) {
        $message[] = 'Please Enter your address.';
    } elseif (strlen($address) < 10) {
        $message[] = 'Please Enter a valid address with at least 10 characters.';
    }
  

    if (empty($message)) {
        // All fields are valid, proceed with placing the order

        $check_cart = $conn->prepare("SELECT * FROM `cart` WHERE user_id = ?");
        $check_cart->execute([$user_id]);

        if ($check_cart->rowCount() > 0) {

            $insert_order = $conn->prepare("INSERT INTO `orders`(user_id, name, number, email, method, address, total_products, total_price) VALUES(?,?,?,?,?,?,?,?)");
            $insert_order->execute([$user_id, $name, $number, $email, $method, $address, $total_products, $total_price]);

            $delete_cart = $conn->prepare("DELETE FROM `cart` WHERE user_id = ?");
            $delete_cart->execute([$user_id]);

            $message[] = 'Order placed successfully!';
        } else {
            $message[] = 'Your cart is empty.';
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
        <title>Checkout</title>

        <!-- font awesome cdn link  -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

        <!-- custom css file link  -->
        <link rel="stylesheet" href="css/style.css">
    </head>

    <body>
        <?php include 'components/user_header.php'; ?>

        <!-- ... -->

        <section class="checkout-orders">
            <form action="" method="POST">
                <h3>your Items</h3>

                <div class="display-orders">
                    <?php
                    $grand_total = 0;
                    $cart_items[] = '';
                    $select_cart = $conn->prepare("SELECT * FROM `cart` WHERE user_id = ?");
                    $select_cart->execute([$user_id]);
                    if ($select_cart->rowCount() > 0) {
                        while ($fetch_cart = $select_cart->fetch(PDO::FETCH_ASSOC)) {
                            $cart_items[] = $fetch_cart['name'] . ' (' . $fetch_cart['price'] . ' x ' . $fetch_cart['quantity'] . ') - ';
                            $total_products = implode($cart_items);
                            $grand_total += ($fetch_cart['price'] * $fetch_cart['quantity']);
                            ?>
                    <p> <?= $fetch_cart['name']; ?>
                        <span>(<?= '$' . $fetch_cart['price'] . '/- x ' . $fetch_cart['quantity']; ?>)</span>
                    </p>
                    <?php
                        }
                    } else {
                        echo '<p class="empty">your cart is empty!</p>';
                    }
                    ?>
                    <input type="hidden" name="total_products" value="<?= $total_products; ?>">
                    <input type="hidden" name="total_price" value="<?= $grand_total; ?>" value="">
                    <div class="grand-total">Grand total : <span>$<?= $grand_total; ?>/-</span></div>
                </div>

                <h3>Place your orders</h3>

                <div class="flex">
                    <div class="inputBox">
                        <span>Your Name :</span>
                        <input type="text" name="name" placeholder="Enter your name" class="box" maxlength="20"
                            required>
                    </div>
                    <div class="inputBox">
                        <span>Your Contact Number :</span>
                        <input type="tel" name="number" placeholder="Enter your number" class="box" pattern="[0-9]{10}"
                            required>
                    </div>
                    <div class="inputBox">
                        <span>Your Email ID :</span>
                        <input type="email" name="email" placeholder="Enter your email" class="box" maxlength="50"
                            required>
                    </div>
                    <div class="inputBox">
                        <span>Payment Method :</span>
                        <select name="method" class="box" required>
                            <option value="">Select a method</option>
                            <option value="cash on delivery">Cash On Delivery</option>
                            <option value="credit card">Credit Card</option>
                            <option value="paytm">UPI</option>
                            <option value="paypal">Paypal</option>
                        </select>
                    </div>
                    <div class="inputBox">
                        <span>Address line 01 :</span>
                        <input type="text" name="flat" placeholder="e.g. Flat number" class="box" minlength="10"
                            required>
                    </div>
                    <div class="inputBox">
                        <span>Address line 02 :</span>
                        <input type="text" name="street" placeholder="e.g. Street name" class="box" minlength="10"
                            required>
                    </div>
                    <div class="inputBox">
                        <span>City :</span>
                        <input type="text" name="city" placeholder="e.g. Bangalore" class="box" maxlength="50" required>
                    </div>
                    <div class="inputBox">
                        <span>State :</span>
                        <input type="text" name="state" placeholder="e.g. Karnataka" class="box" maxlength="50"
                            required>
                    </div>
                    <div class="inputBox">
                        <span>Country :</span>
                        <input type="text" name="country" placeholder="e.g. India" class="box" maxlength="50" required>
                    </div>
                    <div class="inputBox">
                        <span>Pin Code :</span>
                        <input type="text" name="pin_code" placeholder="e.g. 123456" class="box" pattern="[0-9]{6}"
                            required>
                    </div>
                </div>
                <input type="submit" name="order" class="btn <?= ($grand_total > 1) ? '' : 'disabled'; ?>"
                    value="Place Order">
            </form>
        </section>

        <!-- ... -->

        <?php include 'components/footer.php'; ?>

        <!-- ... -->
    </body>

</html>