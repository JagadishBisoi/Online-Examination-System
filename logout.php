<?php
setcookie("user", null, time() - 3600);
header('Location: index.php');
