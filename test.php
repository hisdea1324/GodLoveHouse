<?php
echo "hello world<br>\n"; 

$mysqli = new mysqli("localhost", "lovehouse", "6394", "test");

/* check connection */
if ($mysqli->connect_errno) {
    printf("Connect failed: %s\n", $mysqli->connect_error);
    exit();
}

/* Create table doesn't return a resultset */
if ($mysqli->query("CREATE TEMPORARY TABLE myCity LIKE address") === TRUE) {
    printf("Table myCity successfully created.<br>\n");
}

/* Select queries return a resultset */
if ($result = $mysqli->query("SELECT name FROM address LIMIT 10")) {
    printf("Select returned %d rows.<br>\n", $result->num_rows);

    /* free result set */
    $result->close();
}

/* If we have to retrieve large amount of data we use MYSQLI_USE_RESULT */
if ($result = $mysqli->query("SELECT * FROM address", MYSQLI_USE_RESULT)) {

    /* Note, that we can't execute any functions which interact with the
       server until result set was closed. All calls will return an
       'out of sync' error */
    if (!$mysqli->query("SET @a:='this will not work'")) {
        printf("Error: %s<br>\n", $mysqli->error);
    }
    $result->close();
}

    $sql = "SELECT * FROM address";
    echo $sql."<br>";
    if ($result = $mysqli->query($sql)) { 
        while($obj = $result->fetch_object()){ 
            $line.="id : ".$obj->id; 
            $line.=", name : ".$obj->name; 
            $line.=", phone : ".$obj->email;
            echo $line;
        } 
    } 
    $result->close(); 
    unset($obj); 
    unset($sql); 
    unset($query); 

$mysqli->close();

?>
