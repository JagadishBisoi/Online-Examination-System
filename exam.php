<?php

?>

<!DOCTYPE html>
<head>
  <link rel="stylesheet" href="styles/style.css" />
  <style>body{
    background-color: #cccccc;
  }</style>
</head>
<body>
  <div>
  <form action="" method="POST">
    <p>Q1. What does HTML stands for?</p>
    Answer:<input type="text" id="ans1" name="ans1">
    <br>  

    <p>Q2. What is the correct HTML element for inserting a line break?</p>
    Answer:<input type="text" id="ans2" name="ans2">
    <br>

    <p>Q3. Which character is used to indicate an end tag?</p>
    Answer:<input type="text" id="ans3" name="ans3">
    <br>

    <p>Q4. www is based on which model?</p>
    Answer:<input type="text" id="ans4" name="ans4">
    <br>

    <p>Q5. Choose the correct HTML Heading element for the largest heading:</p>
    Answer:<input type="text" id="ans5" name="ans5"><br><br>
    
    <button type="button" id="submit" name="submit">Submit</button>
    
    <div>
      <textarea name="solution" id="solution" cols="100" readonly>
                   Score will be deplayed here......
            </textarea>
    </div>
    </div>
    
  </form>
  <div>
    <a href='logout.php'>
        <button>Logout</button>
      </a>
      </div>
</div>



<script src="javascript.js"></script> 

</body>
</html>
