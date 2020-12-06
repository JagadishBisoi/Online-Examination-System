<?php

?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Login & Registration</title>
  <link rel="stylesheet" href="styles/style.css" />
  <style>body{
    background-color: #cccccc;
  }</style>
</head>

<body>
  <div>
    <h1>Welcome to Online Exam System</h1>
    <?php if (isset($_COOKIE['user'])) { ?>
      <p>You are logged in as <span style='color:green;'> <?php echo $_COOKIE['user']; ?> </span></p>
      <br><br>
      <a href='logout.php'>
        <button>Logout</button>
      </a>
    <?php } else { ?>
      <p>Register Or Login to Our Website to Appear the Exam</p>
      <a href='login.php'>
        <button>Login</button>
      </a>
      <br>
      <a href='register.php'>
        <button>Register</button>
      </a>
    <?php } ?>
  </div>
</body>

</html>