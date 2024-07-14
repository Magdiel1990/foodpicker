<?php
//Iniciating session. 
session_start();

//Models.
require_once ("models/models.php");

//Including the database connection.
$conn = DatabaseConnection::dbConnection();

if(isset($_POST['search'])) {
   
    //Search
    $search = $_POST['search'];
    
    //Query
    $query = "SELECT * FROM recipes WHERE name LIKE '%$search%' OR ingredients LIKE '%$search%' OR instructions LIKE '%$search%' OR category LIKE '%$search%'";
    
    //Result
    $result = $conn -> query($query);
}
?>