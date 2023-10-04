<?php

define('SERVER_NAME', 'localhost');

define('USERNAME', 'root');

define('PASSWORD', '');

define('DB_NAME', 'michaelkorsbags');

// CONNECT WITH DATABASE 


$conn = mysqli_connect(SERVER_NAME, USERNAME, PASSWORD, DB_NAME);


//  check connection


if (!$conn) {
    echo "Connection error" . mysqli_connect_error();
} else {

    echo "Connection successful";
}
