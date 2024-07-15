<?php
//Models.
require_once ("models/models.php");

//Including the database connection.
$conn = DatabaseConnection::dbConnection();

/************************************************************************************************/
/***************************************RESET PROGRAM********************************************/
/************************************************************************************************/

if(isset($_POST["reset"])){
    $reset = $_POST["reset"];

    if($reset == 1){
    $conn->begin_transaction();
    
        try { 
            //Charge the script
            $script = file_get_contents('./db/Foodday.sql'); 
            $queries = explode(';', $script); // Divide el script en instrucciones individuales

            // Ejecutar cada instrucción individual
            foreach ($queries as $query) {
                if (!empty(trim($query))) { 
                    $conn->query($query);
                }
            }

            //Commit the transaction
            $conn->commit();
            
            $_SESSION['message'] = 'Reset hecho';
            $_SESSION['message_alert'] = "success";

            header('Location: ' . root);
            exit;
        
        } catch (Exception $e) {
            // Si algo sale mal, revertir todas las operaciones
            $conn->rollback();

            $_SESSION['message'] = 'Error al hacer reset';
            $_SESSION['message_alert'] = "danger";

            header('Location: ' . root);
            exit;
        }
    }
}
?>