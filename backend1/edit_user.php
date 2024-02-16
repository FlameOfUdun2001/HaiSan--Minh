<?php
include 'inc/session_login.php';
include 'config.php';
include 'inc/heading.php';
include 'inc/header.php';

$errors = [];
$formData = [];

// Take the user details from the database and put it in the fields
if (isset($_GET['id'])) {
    $id = trim($_GET['id']);
    $sql = "SELECT * FROM users WHERE id=$id";
    $result = mysqli_query($con, $sql);

    if ($row = mysqli_fetch_array($result)) {
        $formData['name'] = $row['username'];
        $formData['email'] = $row['email'];
        $formData['password'] = ''; // Empty password field
        $formData['retype_password'] = ''; // Empty retype password field
        $formData['id'] = $row['id'];
    } else {
        $errorMessage = "Không thấy user";
    }
}

// Handle form submissions for editing an existing user
if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $id = trim($_POST['id']);
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    $retypePassword = trim($_POST['retype_password']);

    // Check that the two password fields match
    if ($password !== $retypePassword) {
        $errors['password'] = 'Không đúng password.';
    }

    // Check for errors in username, email, and password
    if (empty($username)) {
        $errors['username'] = "Vui lòng nhập username.";
    } else {
        $formData['username'] = $username;
    }

    if (empty($email)) {
        $errors['email'] = "Vui lòng nhập email.";
    } else {
        $formData['email'] = $email;
    }

    if (empty($password)) {
        $errors['password'] = "Vui lòng thêm password.";
    }

    // If no errors, update the user record in the database
    if (empty($errors)) {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $sql = "UPDATE users SET username='$username', email='$email', password='$hashedPassword' WHERE id=$id";

        if (mysqli_query($con, $sql)) {
            $successMessage = "Sửa user thành công";
        } else {
            $errorMessage = "Lỗi: " . mysqli_error($con) . ". Query: " . $sql;
        }
    }
}
?>

<div class="main-inner">
    <div class="container">
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

        <div class="row">
            <div class="span12">
                <div class="widget">
                    <div class="widget-header">
                        <i class="icon-edit"></i>
                        <h3>Edit User</h3>
                    </div>
                    <div class="widget-content">
                        <form class="form-horizontal" method="post">
                            <input type="hidden" name="id" value="<?php echo $formData['id']; ?>">
                            <div class="control-group">
                                <label class="control-label" for="inputUsername">Username:</label>
                                <div class="controls">
                                    <input type="text" class="span6" id="inputUsername" name="username" value="<?php echo $formData['name']; ?>">
                                    <?php if (!empty($errors['username'])) : ?>
                                        <span class="help-inline"><?php echo $errors['username']; ?></span>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label">Email:</label>
                                <div class="controls">
                                    <input type="email" class="span6" name="email" value="<?php echo $formData['email']; ?>">
                                    <?php if (!empty($errors['email'])) : ?>
                                        <span class="help-inline"><?php echo $errors['email']; ?></span>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label">Password:</label>
                                <div class="controls">
                                    <input type="password" class="span6" name="password" value="">
                                    <?php if (!empty($errors['password'])) : ?>
                                        <span class="help-inline"><?php echo $errors['password']; ?></span>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label">Nhập lại password:</label>
                                <div class="controls">
                                    <input type="password" class="span6" name="retype_password" value="">
                                    <?php if (!empty($errors['retype_password'])) : ?>
                                        <span class="help-inline"><?php echo $errors['retype_password']; ?></span>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <div class="form-actions">
                                <button type="submit" class="btn btn-primary" name="submit">Update User</button>
                                <a class="btn" href="users.php">Hủy</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="footer1">
    <?php include 'inc/footer.php'; ?>
</div>