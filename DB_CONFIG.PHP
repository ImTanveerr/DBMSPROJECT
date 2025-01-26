<?php
// Database configuration
$HOSTNAME = "localhost";  // Server address (localhost if on your local machine)
$UNAME = "root";          // MySQL username (root is the default for XAMPP)
$PASS = "";               // MySQL password (empty by default for XAMPP)
$DBNAME = "eventconnect"; // Database name

// Establishing the database connection
$con = mysqli_connect($HOSTNAME, $UNAME, $PASS, $DBNAME);

// Check the connection
if(!$con){
    die("Error in connection: " . mysqli_connect_error());
} else {
    // Uncomment to confirm connection (Optional for debugging)
    // echo "Connected successfully to the database";
}

// Function to sanitize input data
function filteration($data){
    foreach($data as $key => $value){
        // Remove unwanted characters and spaces
        $data[$key] = trim($value); 
        // Remove slashes
        $data[$key] = stripslashes($value);
        // Convert special characters to HTML entities
        $data[$key] = htmlspecialchars($value); 
    }
    return $data;
}

// Function to execute select query with prepared statements
function select($sql, $values, $datatypes){
    // Using the global $con connection variable
    $con = $GLOBALS['con'];
    
    // Prepare the query
    if($stmt = mysqli_prepare($con, $sql)){
        // Bind parameters to the prepared statement
        mysqli_stmt_bind_param($stmt, $datatypes, ...$values);
        
        // Execute the prepared statement
        if(mysqli_stmt_execute($stmt)){
            // Retrieve result
            $result = mysqli_stmt_get_result($stmt);
            return $result;
        } else {
            // Close the statement in case of error
            mysqli_stmt_close($stmt);
            die("Error in execution: " . $con->error);
        }
    } else {
        // Handle errors in preparing the statement
        die("Error in prepare statement: " . $con->error);
    }
}

// Function to execute an insert query with prepared statements
function insert($sql, $values, $datatypes){
    // Using the global $con connection variable
    $con = $GLOBALS['con'];
    
    // Prepare the query
    if($stmt = mysqli_prepare($con, $sql)){
        // Bind parameters to the prepared statement
        mysqli_stmt_bind_param($stmt, $datatypes, ...$values);
        
        // Execute the prepared statement
        if(mysqli_stmt_execute($stmt)){
            return true;
        } else {
            mysqli_stmt_close($stmt);
            die("Error in insertion: " . $con->error);
        }
    } else {
        die("Error in prepare statement: " . $con->error);
    }
}

// Function to execute an update query with prepared statements
function update($sql, $values, $datatypes){
    $con = $GLOBALS['con'];
    
    if($stmt = mysqli_prepare($con, $sql)){
        mysqli_stmt_bind_param($stmt, $datatypes, ...$values);
        if(mysqli_stmt_execute($stmt)){
            return true;
        } else {
            mysqli_stmt_close($stmt);
            die("Error in updating: " . $con->error);
        }
    } else {
        die("Error in prepare statement: " . $con->error);
    }
}

// Function to execute a delete query with prepared statements
function delete($sql, $values, $datatypes){
    $con = $GLOBALS['con'];
    
    if($stmt = mysqli_prepare($con, $sql)){
        mysqli_stmt_bind_param($stmt, $datatypes, ...$values);
        if(mysqli_stmt_execute($stmt)){
            return true;
        } else {
            mysqli_stmt_close($stmt);
            die("Error in deletion: " . $con->error);
        }
    } else {
        die("Error in prepare statement: " . $con->error);
    }
}
?>
