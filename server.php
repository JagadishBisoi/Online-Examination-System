<?php

$input1 = (trim($_GET["input1"]));
$input2 = (trim($_GET["input2"]));
$input3 = (trim($_GET["input3"]));
$input4 = (trim($_GET["input4"]));
$input5 = (trim($_GET["input5"]));

$inputs = [$input1,$input2,$input3,$input4,$input5];
$count=0;


$conn = mysqli_connect('localhost', 'root', 'jagu1237', 'EXAM');
if ($conn) {
    $sql = "select * from mark ";
    $result = mysqli_query($conn, $sql);
    $i=0;
    while ($row = mysqli_fetch_row($result)) {
        $answer=trim($row[1]);
        if(strcasecmp($inputs[$i],$answer)===0)
            $count++;
        $i++;
    }
    echo "Marks obtained =" . $count;
}
mysqli_close($conn);
