<?php
//Head
require_once ("views/partials/head.php");

/************************************************************************************************/
/***************************************RECIPE DELETION CODE*************************************/
/************************************************************************************************/

if(isset($_GET['recipeid'])){
    
//Getting the id.
$recipeId = $_GET['recipeid'];

//Check if it exists
$num_rows = new FromIdToName ($recipeId, "recipe");
$num_rows = $num_rows -> rows();

    if($num_rows > 0) {
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

//Check if it exists
$num_rows = new FromIdToName ($categoryId, "categories");
$num_rows = $num_rows -> rows();

    if($num_rows > 0) {
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

//Check if it exists
$num_rows = new FromIdToName ($id, "ingredients");
$num_rows = $num_rows -> rows();

    if($num_rows > 0) {
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

    //Check if it exists
    $num_rows = new FromIdToId ($id, "inglook", "ingredientid");
    $num_rows = $num_rows -> rows();    
    
    if($num_rows > 0) {
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

if(isset($_GET['dietid'])) {
    $dietid = $_GET['dietid'];

    if($dietid == "") {
        header('Location: ' . root . 'error404');
        exit;
    }

    //Check if it already exists
    $num_rows = new FromIdToName ($dietid, "diet");
    $num_rows = $num_rows -> rows();

    if($num_rows > 0) {
//Diet name
        $row = $result -> fetch_assoc();

        $result = $conn -> query("DELETE FROM diet WHERE id = '$dietid';");
        if($result) {
            $_SESSION['message'] = '¡Dieta eliminada!';
            $_SESSION['message_alert'] = "success";

            header('Location: ' . root . 'diet');
            exit;
        } else {
            $_SESSION['message'] = '¡Error al eliminar dieta!';
            $_SESSION['message_alert'] = "danger";

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