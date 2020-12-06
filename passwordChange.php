<?php
if(isset($_COOKIE['user'])) {
    header('Location :index.php');
}

if (isset($_POST['in-submit'])) {

    require 'config.php';
    $username = $_POST['username'];
    $password = $_POST['password'];
    $passwordN = $_POST['passwordN'];


    if (empty($username) || empty($password) || empty($passwordN)) {
        header('Location:  passwordChange.php?error=allFieldsRequired&username=' . $username);
        exit();
    } else if (!preg_match("/^[a-zA-Z0-9]*$/", $username)) {
        header('Location:  passwordChange.php?error=invalidUsername&username=' . $username);
        exit();
    } else {
        $sql = "SELECT * FROM student WHERE USERNAME=?";
        $stmt = mysqli_stmt_init($conn);
        if (!mysqli_stmt_prepare($stmt, $sql)) {
            header('Location:  passwordChange.php?error=databaseConnectivityError');
            exit();
        } else {
            mysqli_stmt_bind_param($stmt, "s", $username);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);
            if ($row = mysqli_fetch_assoc($result)) {
                $pwdverify = password_verify($password, $row['PSWD']);
                if ($pwdverify == true) {
                    $sql = "UPDATE student SET PSWD = ? WHERE USERNAME = ?";
                    $stmt = mysqli_stmt_init($conn);
                    if (!mysqli_stmt_prepare($stmt, $sql)) {
                        header('Location:  passwordChange.php?error=databaseConnectivityError');
                        exit();
                    } else {
                        $encryptPass = password_hash($passwordN, PASSWORD_DEFAULT);
                        mysqli_stmt_bind_param($stmt, "ss", $encryptPass, $username);
                        mysqli_stmt_execute($stmt);
                        header('Location:  passwordChange.php?change=success');
                        exit();
                    }
                } else if ($pwdverify == false) {
                    header('Location:  passwordChange.php?error=invalidPassword&username=' . $username);
                    exit();
                }
            } else {
                header('Location:  passwordChange.php?error=NoDataFound');
                exit();
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
    <title>passwordChange Form</title>
    <link rel="stylesheet" href="styles/style.css" />
    <style>body{
    background-color: #cccccc;
  }</style>
</head>


<body>

    <div>
        <h2>Change Password Form</h2>
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
        if (isset($_GET['error']) && $_GET['error'] == 'invalidPassword') {
            echo "<p style='color:red;'>Invalid Password</p>";
        }
        if (isset($_GET['error']) && $_GET['error'] == 'NoDataFound') {
            echo "<p style='color:red;'>No data found</p>";
        }
        if (isset($_GET['change']) && $_GET['change'] == 'success') {
            echo "<p style='color:green;'>Password change successful.</p>";
        }
        ?>
        <div>
            <form action="" method="POST">
                <input type="text" name="username" placeholder="Enter registered username" value="<?php if (isset($_GET['username'])) {
                                                                                                        echo $_GET['username'];
                                                                                                    } ?>">
                <br><br>
                <input type="password" name="password" placeholder="Old Password">
                <br><br>
                <input type="password" name="passwordN" placeholder="New Password">
                <br><br>
                <button type="submit" name="in-submit">Done</button>

            </form>
        </div>

        <div>
            <h4>Other Helpful links</h4>
            <a href="passwordReset.php">Generate New password (Reset)</a>
            <a href="index.php">Home</a>
            <a href="login.php">Login</a>
        </div>
    </div>


</body>

</html>