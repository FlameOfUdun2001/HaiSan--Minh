<?php
session_start();

include 'config.php';
include 'inc/heading_login.php';
include 'inc/header_lore.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Sanitize input data
    $username = mysqli_real_escape_string($con, $_POST['username']);
    $password = mysqli_real_escape_string($con, $_POST['password']);

    // Verify user credentials
    $sql = "SELECT * FROM users WHERE username = '$username'";
    $result = mysqli_query($con, $sql);

    if (mysqli_num_rows($result) > 0) {
        $user = mysqli_fetch_assoc($result);

        if (password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
        } else {
            $errorMessage = "Invalid username or password";
        }
    } else {
        $errorMessage = "Invalid username or password";
    }
}

// Redirect to index.php if login sussxess

if (isset($_SESSION['user_id']) && !empty($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

?>

<div class="account-container">
    <div class="content clearfix">

        <form method="post">

            <?php if (isset($errorMessage)) : ?>
                <div class="alert alert-danger" role="alert">
                    <?php echo $errorMessage; ?>
                </div>
            <?php endif; ?>

            <h1>Nhân viên đăng nhập</h1>
            <div class="login-fields">

                <p>Điền thông tin của bạn</p>

                <div class="field">
                    <label for="username">Tài Khoản:</label>
                    <input type="text" id="username" name="username" value="" placeholder="Username" class="login username-field" required />
                </div> <!-- /field -->

                <div class="field">
                    <label for="password">Mật khẩu:</label>
                    <input type="password" id="password" name="password" value="" placeholder="Password" class="login password-field" required />
                </div> <!-- /password -->

            </div> <!-- /login-fields -->

            <div class="login-actions">
                <span class="login-checkbox">
                    <input id="Field" name="Field" type="checkbox" class="field login-checkbox" value="First Choice" tabindex="4" />
                    <label class="choice" for="Field">Nhớ đăng nhập</label>
                </span>
                <button class="button btn btn-success btn-large" type="submit">Đăng nhập</button>
            </div> <!-- .actions -->
        </form>
    </div> <!-- /content -->
</div> <!-- /account-container -->
<div class="login-extra">
    <a href="index.php">Reset</a> hoặc <a href="register.php">Đăng ký</a>
</div> <!-- /login-extra -->


</body>
<script src="js/jquery-1.7.2.min.js"></script>
<script src="js/bootstrap.js"></script>

<script src="js/signin.js"></script>

</html>