<?php
//Head
require_once ("views/partials/head.php");

/************************************************************************************************/
/***************************************RECIPE DELETION CODE*************************************/
/************************************************************************************************/

if(isset($_GET['recipeid'])){
    
//Getting the id.
$recipeId = $_GET['recipeid'];

$result = $conn -> query("SELECT id FROM recipe WHERE id = '$recipeId';");

    if($result -> num_rows > 0) {
        $result = $conn -> query("DELETE FROM recipe WHERE id = '$recipeId';");

        if($result) {            
            $_SESSION['message'] = '¡Receta eliminada con éxito!';
            $_SESSION['message_alert'] = "success";

            header('Location: ' . root);
            exit;
        } else {
            $_SESSION['message'] = '¡Error al eliminar receta!';
            $_SESSION['message_alert'] = "danger";

            header('Location: ' . root);
        }
    } else {
        $_SESSION['message'] = '¡Esta receta no existe!';
        $_SESSION['message_alert'] = "danger";

        header('Location: ' . root);
        exit;
    }  
}

/************************************************************************************************/
/***************************************CATEGORY DELETION CODE***********************************/
/************************************************************************************************/

if(isset($_GET['categoryid'])){    
//Category.
$categoryId = $_GET['categoryid'];

//Getting the id
$result = $conn -> query("SELECT id FROM categories WHERE id = '$categoryId';");

    if($result -> num_rows > 0) {
        $result = $conn -> query("DELETE FROM categories WHERE id = '$categoryId';");

        if($result) {            
            $_SESSION['message'] = '¡Categoría eliminada con éxito!';
            $_SESSION['message_alert'] = "success";

            header('Location: ' . root . 'categories');
            exit;
        } else {
            $_SESSION['message'] = '¡Error al eliminar categoría!';
            $_SESSION['message_alert'] = "danger";

            header('Location: ' . root . 'categories');
            exit;
        }
    } else {
        $_SESSION['message'] = '¡Esta categoría no existe!';
        $_SESSION['message_alert'] = "danger";

        header('Location: ' . root . 'categories');
        exit;  
    }
}


/************************************************************************************************/
/***************************************INGREDIENT DELETION CODE*********************************/
/************************************************************************************************/

if(isset($_GET['ingredientid'])){
    
//Ingredient
$id = $_GET['ingredientid'];

//Getting the id
$result = $conn -> query("SELECT id FROM ingredients WHERE id = '$id';");

    if($result -> num_rows > 0) {
        $result = $conn -> query("DELETE FROM ingredients WHERE id = '$id';");

        if($result) {            
            $_SESSION['message'] = '¡Ingrediente eliminado con éxito!';
            $_SESSION['message_alert'] = "success";

            header('Location: ' . root . 'ingredients');
            exit;
        } else {
            $_SESSION['message'] = '¡Error al eliminar ingrediente!';
            $_SESSION['message_alert'] = "danger";

            header('Location: ' . root . 'ingredients');
            exit;
        }
    } else {
        $_SESSION['message'] = '¡Este ingrediente no existe!';
        $_SESSION['message_alert'] = "danger";

        header('Location: ' . root . 'ingredients');
        exit;  
    }
} 

/************************************************************************************************/
/*******************************INGREDIENT INGLOOK DELETION CODE*********************************/
/************************************************************************************************/

if(isset($_GET['customid']) && isset($_GET['uri'])) {
    
    //Ingredient id
    $id = $_GET['customid'];
    //Uri of the page
    $uri = $_GET['uri'];

    //Getting the id
    $result = $conn -> query("SELECT id FROM inglook WHERE ingredientid = '$id';");
    
    if($result -> num_rows > 0) {
        $result = $conn -> query("DELETE FROM inglook WHERE ingredientid = '$id';");

        if($result) {            
            $_SESSION['message'] = '¡Ingrediente eliminado con éxito!';
            $_SESSION['message_alert'] = "success";

            header('Location: ' . root . $uri);
            exit;
        } else {
            $_SESSION['message'] = '¡Error al eliminar ingrediente!';
            $_SESSION['message_alert'] = "danger";

            header('Location: ' . root . $uri);
            exit;
        }
    } else {
        $_SESSION['message'] = '¡Este ingrediente no existe!';
        $_SESSION['message_alert'] = "danger";

        header('Location: ' . root . $uri);
        exit;  
    }
} 

/************************************************************************************************/
/*****************************************DIET DELETION CODE*************************************/
/************************************************************************************************/

if(isset($_GET['dietid']) && isset($_GET['username'])) {
    $dietid = $_GET['dietid'];
    $username = $_GET['username'];

    if($dietid == "" || $username == "") {
        header('Location: ' . root . 'error404');
        exit;
    }

    $result = $conn -> query("SELECT dietname FROM diet WHERE id = '$dietid' AND username = '$username';");

    if($result -> num_rows > 0) {
//Diet name
        $row = $result -> fetch_assoc();
        $dietname = $row['dietname'];

        $result = $conn -> query("UPDATE diet SET state = 0 WHERE id = '$dietid';");
        if($result) {
//Notification message        
            $log_message = "Has eliminado la dieta \"" . $dietname . "\".";       
            $type = "diet";
//Verify if notifications are on
            if($_SESSION['notification'] == 1) {
                $conn -> query("INSERT INTO `log` (username, log_message, type, state) VALUES ('" . $_SESSION["username"] . "', '$log_message', '$type', 0);");
            }

            $conn -> query("INSERT INTO recycle (name, type, username, elementid) VALUES ('$dietname', 'dieta', '" . $_SESSION['username'] . "', '$dietid');");
            
            $_SESSION['message'] = '¡Dieta eliminada!';
            $_SESSION['message_alert'] = "success";

            header('Location: ' . root . 'diet');
            exit;
        }
    } else {
        $_SESSION['message'] = '¡Esta dieta no existe!';
        $_SESSION['message_alert'] = "danger";

        header('Location: ' . root . 'diet');
        exit;
    }
}
//Exiting db connection.
$conn -> close(); 

//Footer 
include("views/partials/footer.php");
?>