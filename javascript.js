document.getElementById("submit").onclick = function () {
    var input1 = document.getElementById("ans1").value;
    var input2 = document.getElementById("ans2").value;
    var input3 = document.getElementById("ans3").value;
    var input4 = document.getElementById("ans4").value;
    var input5 = document.getElementById("ans5").value;

    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function () {
        if (this.readyState == 4 && this.status == 200) {
            document.getElementById("solution").innerHTML = " ";
            document.getElementById("solution").innerHTML = this.responseText;
        }
    };
    xhttp.open("GET", "server.php?input1=" + input1 + "&input2=" + input2 + "&input3=" + input3+ "&input4=" + input4+ "&input5=" + input5, true);
    xhttp.send(); 

}

