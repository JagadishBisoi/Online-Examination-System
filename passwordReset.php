<?php
if(isset($_COOKIE['user'])) {
    header('Location :index.php');
}
function random_password($length) {
    $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
    $password = substr( str_shuffle( $chars ), 0, $length );
    return $password;
}

if (isset($_POST['in-reset'])) {

    require 'config.php';
    $username = $_POST['username'];
    $phone = $_POST['phone'];

    if (empty($username) || empty($phone)) {
        header('Location:  passwordReset.php?error=allFieldsRequired&username=' . $username);
        exit();
    } else if (!preg_match("/^[a-zA-Z0-9]*$/", $username)) {
        header('Location:  passwordReset.php?error=invalidUsername&username=' . $username);
        exit();
    } else if ($phone < 1000000000 || $phone > 9999999999) {
        header('Location:  passwordReset.php?error=phoneNumberLenghtInvalid&username=' . $username . '&phone=' . $phone);
        exit();
    } else {
        $sql = "SELECT * FROM student where USERNAME = ? AND PHONE = ?";
        $stmt = mysqli_stmt_init($conn);
        if (!mysqli_stmt_prepare($stmt, $sql)) {
            header('Location:  passwordReset.php?error=databaseConnectivityError');
            exit();
        } else {
            mysqli_stmt_bind_param($stmt, "ss", $username, $phone);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_store_result($stmt);
            $checkDuplicateUsername = mysqli_stmt_num_rows($stmt);

            if ($checkDuplicateUsername == 0) {
                header('Location:  passwordReset.php?error=recordNotFound');
                exit();
            } else {
                $sql = "UPDATE student SET PSWD = ? WHERE USERNAME = ?";
                $stmt = mysqli_stmt_init($conn);
                if (!mysqli_stmt_prepare($stmt, $sql)) {
                    header('Location:  passwordReset.php?error=databaseConnectivityError');
                    exit();
                } else {
                    $newPass = random_password(8);
                    $encryptPass = password_hash($newPass, PASSWORD_DEFAULT);
                    mysqli_stmt_bind_param($stmt, "ss", $encryptPass, $username);
                    mysqli_stmt_execute($stmt);
                    header('Location:  passwordReset.php?reset=success&newP=' . $newPass);
                    exit();
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
    <title>Password reset</title>
    <link rel="stylesheet" href="styles/style.css" />
    <style>body{
    background-color: #cccccc;
  }</style>
</head>


<body>

    <div>
        <h2>Password Reset Form</h2>
        <?php
        if (isset($_GET['error']) && $_GET['error'] == 'allFieldsRequired') {
            echo "<p style='color:red;'>All Fields are required</p>";
        }
        if (isset($_GET['error']) && $_GET['error'] == 'invalidUsername') {
            echo "<p style='color:red;'>Username invalid. characters allowed :([a-z] [A-Z] [0-9])</p>";
        }
        if (isset($_GET['error']) && $_GET['error'] == 'databaseConnectivityError') {
            echo "<p style='color:red;'>Database connectivity issue. Request for admin</p>";
        }
        if (isset($_GET['error']) && $_GET['error'] == 'recordNotFound') {
            echo "<p style='color:red;'>No record found</p>";
        }
        if (isset($_GET['error']) && $_GET['error'] == 'phoneNumberLenghtInvalid') {
            echo "<p style='color:red;'>Phone number length invalid</p>";
        }
        if (isset($_GET['reset']) && $_GET['reset'] == 'success') {
            echo "<p style='color:green;'>Password reset successfully.</p>";
            echo "<p style='color:blue;'>New Password is : </p>". $_GET['newP'];
        }
        ?>
        <div>
            <form action="" method="POST">
                <input type="text" name="username" placeholder="Enter registered username" value="<?php if (isset($_GET['username'])) {
                                                                                                        echo $_GET['username'];
                                                                                                    } ?>">
                <br><br>
                <input type="number" name="phone" placeholder="Registered Phone number">
                <br><br>
                <button type="submit" name="in-reset">Reset Password</button>
            </form>
        </div>

        <div>
            <h4>Other Helpful links</h4>
            <a href="passwordChange.php">Change password here</a>
            <a href="index.php">Home</a>
            <a href="login.php">Login</a>
        </div>
    </div>


</body>

</html>