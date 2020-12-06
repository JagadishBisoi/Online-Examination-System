<?php
if (isset($_COOKIE['user'])) {
    header('Location :index.php');
}

if (isset($_POST['in-submit'])) {

    require 'config.php';
    $username = $_POST['username'];
    $password = $_POST['password'];

    if (empty($username) || empty($password)) {
        header('Location:  login.php?error=allFieldsRequired&username=' . $username);
        exit();
    } else if (!preg_match("/^[a-zA-Z0-9]*$/", $username)) {
        header('Location:  login.php?error=invalidUsername&username=' . $username);
        exit();
    } else {
        $sql = "SELECT * FROM student WHERE USERNAME=?";
        $stmt = mysqli_stmt_init($conn);
        if (!mysqli_stmt_prepare($stmt, $sql)) {
            header('Location:  login.php?error=databaseConnectivityError');
            exit();
        } else {
            mysqli_stmt_bind_param($stmt, "s", $username);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);
            if ($row = mysqli_fetch_assoc($result)) {
                $pwdverify = password_verify($password, $row['PSWD']);
                if ($pwdverify == true) {
                    $remember = $_POST['remember'];
                    if (!empty($remember)) {
                        setcookie("user", $username, time() + (86400 * 30));
                        header("Location: index.php");
                    }
                    else {
                        setcookie("user", null, time() - 3600);
                        header('Location:  exam.php');
                    }
                    exit();
                } else if ($pwdverify == false) {
                    header('Location:  login.php?error=invalidPassword&username=' . $username);
                    exit();
                }
            } else {
                header('Location:  login.php?error=NoDataFound');
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
    <title>Login Form</title>
    <link rel="stylesheet" href="styles/style.css" />
    <style>body{
    background-color: #cccccc;
  }</style>
</head>


<body>

    <div>
        <h2>Log in Form</h2>
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
        ?>
        <div>
            <form action="" method="POST">
                USERNAME:<input type="text" name="username" placeholder="Enter registered username" value="<?php if (isset($_GET['username'])) {
                                                                                                        echo $_GET['username'];
                                                                                                    } ?>">
                <br><br>
                PASSWORD:<input type="password" name="password" placeholder="Password">
                <br><br>
                <input type="checkbox" name="remember" value="Remember me">
                <label for="remember" style="font-size: 14px;">Remember me</label>
                <br><br>
                <button type="submit" name="in-submit">Login</button>

            </form>
        </div>

        
            <a href="register.php">Not yet registered ?</a>
            <a href="passwordChange.php">Change password here</a>
            <a href="passwordReset.php">Generate New password (Reset)</a>
            <a href="index.php">Home</a>
        
    </div>


</body>

</html>