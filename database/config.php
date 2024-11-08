<?PHP
    $servername = "localhost";
    $username = "FlexMatch";
    $password = "parttimejob2024";
    $dbname = "flexmatch_db";

    // Create connection
    $con = mysqli_connect($servername, $username, $password, $dbname);

    // Check connection
    if (!$con) {
        die('Connection failed: ' . mysqli_connect_error());
    }
    
?>