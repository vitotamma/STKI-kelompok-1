<?php
    $hostname = "localhost";
    $username = "root";
    $password = "";
    $dbname = "stki";

    $conn = mysqli_connect($hostname, $username, $password, $dbname);

    if ($conn->connect_error) {
        echo $conn;
        die("Connection failed : ".$conn->connect_error);
    } else{
        // echo "connect success";
    }
