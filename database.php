<?php
if(mail("Dheeraj.kukreja78@gmail.com","Success","Send mail from localhost using PHP")) {
  echo "mail send";
} else {
  echo "not send";
}

    $servername = "localhost";
    $username = "root";
    $password = "";
    
    // Create connection
    $conn = new mysqli($servername, $username, $password,'rtcamp_assignment');

    
    // Check connection
    if ($conn->connect_error) {
      die("Connection failed: " . $conn->connect_error);
    }
?>