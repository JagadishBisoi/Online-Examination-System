<?php

$servername = "localhost";
$dbUser = "root";
$dbPswd = "jagu1237";
$dbName = "EXAM";

$conn = mysqli_connect($servername, $dbUser, $dbPswd);
if (!$conn) {
    die("Could not connect to database : " . mysqli_connect_error());
}
$sql = "CREATE DATABASE EXAM";
mysqli_query($conn,$sql);
mysqli_close($conn);

$conn = mysqli_connect($servername, $dbUser, $dbPswd, $dbName);
$sql = "CREATE TABLE student (
    USERNAME VARCHAR(100) PRIMARY KEY,
    PSWD LONGTEXT NOT NULL,
    PHONE VARCHAR(10) NOT NULL
    )";

mysqli_query($conn,$sql);

$conn = mysqli_connect($servername, $dbUser, $dbPswd, $dbName);
$sql = "CREATE TABLE mark (
    ID VARCHAR(100) PRIMARY KEY,
    ANSWER VARCHAR(100) NOT NULL
    )";

mysqli_query($conn,$sql);

$sql= "INSERT INTO mark VALUES('ans1','HyperText MarkUp Language')";
mysqli_query($conn,$sql);
$sql= "INSERT INTO mark VALUES('ans2','<br>')";
mysqli_query($conn,$sql);
$sql= "INSERT INTO mark VALUES('ans3','/')";
mysqli_query($conn,$sql);
$sql= "INSERT INTO mark VALUES('ans4','client server')";
mysqli_query($conn,$sql);
$sql= "INSERT INTO mark VALUES('ans5','<h1>')";
mysqli_query($conn,$sql);

