<?php


if (isset($_POST['register-submit'])) {
    require 'config.php';

    $username = trim(htmlspecialchars($_POST['username']));
    $phone = trim(htmlspecialchars($_POST['phone']));
    $password = trim(htmlspecialchars($_POST['password']));
    $passwordC = trim(htmlspecialchars($_POST['password-confirm']));

    if (
        empty($username) ||
        empty($phone) ||
        empty($password) ||
        empty($passwordC)
    ) {
        header('Location:  register.php?error=allFieldsRequired&username=' . $username . '&phone=' . $phone);
        exit();
    } else if ($phone < 1000000000 || $phone > 9999999999) {
        header('Location:  register.php?error=phoneNumberLenghtInvalid&username=' . $username . '&phone=' . $phone);
        exit();
    } else if (!preg_match("/^[a-zA-Z0-9]*$/", $username)) {
        header('Location:  register.php?error=invalidUsername&username=' . $username . '&phone=' . $phone);
        exit();
    } else if ($password !== $passwordC) {
        header('Location:  register.php?error=passwordMismatch&username=' . $username . '&phone=' . $phone);
        exit();
    } else {
        $sql = "SELECT username FROM student where username = ?";
        $stmt = mysqli_stmt_init($conn);
        if (!mysqli_stmt_prepare($stmt, $sql)) {
            header('Location:  register.php?error=databaseConnectivityError');
            exit();
        } else {
            mysqli_stmt_bind_param($stmt, "s", $username);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_store_result($stmt);
            $checkDuplicateUsername = mysqli_stmt_num_rows($stmt);

            if ($checkDuplicateUsername > 0) {
                header('Location:  register.php?error=usernameAlreadyExists');
                exit();
            } else {
                $sql = "INSERT INTO student (username, pswd, phone) VALUES (?, ?, ?)";
                $stmt = mysqli_stmt_init($conn);
                if (!mysqli_stmt_prepare($stmt, $sql)) {
                    header('Location:  register.php?error=databaseConnectivityError1');
                    exit();
                } else {
                    $encryptPass = password_hash($password, PASSWORD_DEFAULT);
                    mysqli_stmt_bind_param($stmt, "sss", $username, $encryptPass, $phone);
                    if (mysqli_stmt_execute($stmt)) {
                        header('Location:  register.php?signup=success');
                        exit();
                    } else {
                        header('Location:  register.php?error=databaseConnectivityError2');
                        exit();
                    }
                }
            }
        }
    }
    mysqli_stmt_close($stmt);
    mysqli_close($conn);
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration form</title>
    <link rel="stylesheet" href="styles/style.css" />
    <style>body{
    background-color: #cccccc;
  }</style>
</head>

<body>

    <div>
        <h2>Registration</h2>
        <?php
        if (isset($_GET['error']) && $_GET['error'] == 'allFieldsRequired') {
            echo "<p style='color:red;'>All Fields are required</p>";
        }
        if (isset($_GET['error']) && $_GET['error'] == 'phoneNumberLenghtInvalid') {
            echo "<p style='color:red;'>Phone number invalid</p>";
        }
        if (isset($_GET['error']) && $_GET['error'] == 'invalidUsername') {
            echo "<p style='color:red;'>Username invalid. characters allowed :([a-z] [A-Z] [0-9])</p>";
        }
        if (isset($_GET['error']) && $_GET['error'] == 'passwordMismatch') {
            echo "<p style='color:red;'>Passwords do not match</p>";
        }
        if (isset($_GET['error']) && $_GET['error'] == 'databaseConnectivityError') {
            echo "<p style='color:red;'>Database connectivity issue. Request for admin</p>";
        }
        if (isset($_GET['error']) && $_GET['error'] == 'usernameAlreadyExists') {
            echo "<p style='color:red;'>Username already exists.</p>";
        }
        if (isset($_GET['signup']) && $_GET['signup'] == 'success') {
            echo "<p style='color:green;'>User registered successfully.</p>";
        }
        ?>
        <div>
            <form action="" method="POST">
                <input type="text" name="username" placeholder="Username" value="<?php if (isset($_GET['username'])) {
                                                                                        echo $_GET['username'];
                                                                                    } ?>">
                <br><br>
                <input type="number" name="phone" placeholder="Phone number" value="<?php if (isset($_GET['phone'])) {
                                                                                        echo $_GET['phone'];
                                                                                    } ?>">
                <br><br>
                <input type="password" name="password" placeholder="Password">
                <br><br>
                <input type="password" name="password-confirm" placeholder="Confirm Password">
                <br><br>
                <button type="submit" name="register-submit">Register</button>
            </form>
        </div>
        <div>
            <h4>Other helpful links</h4>
            <a href="login.php">Already registered ?</a>
            <br>
            <a href="index.php">Home</a>
        </div>
    </div>

</body>

</html>