<?php
include 'config.php';
include 'inc/header_lore.php';
include 'inc/heading_login.php';


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Sanitize input data
    $username = mysqli_real_escape_string($con, $_POST['username']);
    $email = mysqli_real_escape_string($con, $_POST['email']);
    $password = mysqli_real_escape_string($con, $_POST['password']);

    // Check if the  exist before accese information
    $retypePassword = isset($_POST['retype_password']) ? mysqli_real_escape_string($con, $_POST['retype_password']) : '';
    $token = isset($_POST['token']) ? mysqli_real_escape_string($con, $_POST['token']) : '';
    $active = isset($_POST['active']) ? mysqli_real_escape_string($con, $_POST['active']) : '';

    // Check if the passwords match
    if ($password != $retypePassword) {
        $errorMessage = "Passwords do not match";
    } else {
        // Hash the password
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        // Check if the email already exists in the database
        $sql = "SELECT * FROM users WHERE email = '$email'";
        $result = mysqli_query($con, $sql);

        if (mysqli_num_rows($result) > 0) {
            $errorMessage = "Email already exists in the database";
        } else {
            // Insert user data into the database
            $sql = "INSERT INTO users (`username`, `email`, `password`, `token`, `active`)
                    VALUES ('$username', '$email', '$hashedPassword', '$token', '$active')";

            if (mysqli_query($con, $sql)) {
                $successMessage = "User registered successfully";
            } else {
                $errorMessage = "Error registering user: " . mysqli_error($con);
            }
        }
    }
}
?>

<div class="account-container register">
    <div class="content clearfix">
        <form method="POST" action="">

            <?php if (isset($errorMessage)) : ?>
                <div class="alert alert-danger" role="alert">
                    <?php echo $errorMessage; ?>
                </div>
            <?php endif; ?>

            <?php if (isset($successMessage)) : ?>
                <div class="alert alert-success" role="alert">
                    <?php echo $successMessage; ?>
                </div>
            <?php endif; ?>

            <h1>Signup for Free Account</h1>
            <div class="login-fields">
                <p>Create your free account:</p>
                <div class="field">
                    <label>Name:</label>
                    <input type="text" class="login" name="username" required placeholder=" Name">
                </div>
                <div class="field">
                    <label>Email Address:</label>
                    <input type="email" class="login" name="email" required placeholder="Email Address">
                </div>
                <div class="field">
                    <label for="password">Password:</label>
                    <input type="password" class="login" name="password" required placeholder="Password">
                </div>
                <div class="field">
                    <label for="confirm_password">Confirm Password:</label>
                    <input type="password" class="login" name="retype_password" required placeholder="Confirm Password">
                </div>
                <div class="field">
                    <label>Token:</label>
                    <input type="text" class="login" name="token" placeholder="Token">
                </div>
            </div> <!-- /login-fields -->
            <div class="login-actions">
                <span class="login-checkbox">
                    <input id="Field" name="active" type="checkbox" class="field login-checkbox" placeholder=">Active" value="1" tabindex="4" />
                    <label class="choice" for="Field">Active</label>
                </span>
                <button type="submit" class="button btn btn-primary btn-large">Register</button>
            </div> <!-- .actions -->
        </form>
    </div> <!-- /content -->
</div> <!-- /account-container -->

<!-- Text Under Box -->
<div class="login-extra">
    Already have an account? <a href="login.php">Login to your account</a>
</div> <!-- /login-extra -->

</body>

</html>