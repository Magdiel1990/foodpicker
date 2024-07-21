<?php
//Head of the page.
require_once ("views/partials/head.php");

$_SESSION['location'] = root;


/************************************************************************************************/
/******************************************RECIPE UPDATE CODE************************************/
/************************************************************************************************/

if(isset($_GET["recipeid"]) && isset($_POST["recipeName"]) && isset($_POST["recipeTime"])
&& isset($_POST["ingredients"]) && isset($_POST["recipeUrl"]) && isset($_POST["category"])) {

$id = $_GET["recipeid"];

$filter = new Filter ($_POST["recipeName"], FILTER_SANITIZE_STRING);  
$newRecipeName = $filter -> sanitization();

$filter = new Filter ($_POST["recipeTime"], FILTER_SANITIZE_NUMBER_INT);  
$newRecipeTime = $filter -> sanitization();

$filter = new Filter ($_POST["ingredients"], FILTER_SANITIZE_STRING);  
$newIngredients = $filter -> sanitization();

$filter = new Filter ($_POST["recipeUrl"], FILTER_SANITIZE_URL);  
$newRecipeUrl = $filter -> sanitization();

$categoryId = $_POST["category"];

    $stmt = $conn -> prepare("UPDATE recipe SET name = ?, cookingtime = ?, ingredients = ?, url = ?, categoryid = ? WHERE id = ?;");
    $stmt->bind_param("sissii", $newRecipeName, $newRecipeTime, $newIngredients, $newRecipeUrl, $categoryId, $id);

    if ($stmt -> execute()) {
        $_SESSION['message'] = '¡Receta editada con éxito!';
        $_SESSION['message_alert'] = "success";

        //The page is redirected to the edit.php
        header('Location: ' . root . 'edit?recipeid='. $id);
        exit;  
    } else {
        $_SESSION['message'] = '¡Error al editar receta!';
        $_SESSION['message_alert'] = "danger";

        //The page is redirected to the edit.php
        header('Location: ' . root . 'edit?recipeid='. $id);
        exit;
    }
}


/************************************************************************************************/
/**************************************INGREDIENT UPDATE CODE************************************/
/************************************************************************************************/

if(isset($_POST["ingredientName"]) && isset($_GET["ingredientId"])){
  $filter = new Filter ($_POST["ingredientName"], FILTER_SANITIZE_STRING);
  $ingredient = $filter -> sanitization();
  
  $id = $_GET["ingredientId"];

//lowercase the variable
    $ingredient = strtolower($ingredient);

    $stmt = $conn -> prepare("UPDATE ingredients SET name = ? WHERE id = ?;");
    $stmt->bind_param("si", $ingredient, $id);

    if ($stmt -> execute()) {
        $_SESSION['message'] = '¡Ingrediente editado con éxito!';
        $_SESSION['message_alert'] = "success";

        $stmt -> close();
        header('Location: ' . root . 'edit?ingredientid=' . $id);
        exit;
    } else {
        $_SESSION['message'] = '¡Error al editar ingrediente!';
        $_SESSION['message_alert'] = "danger";
            
        header('Location: ' . root . 'edit?ingredientid=' . $id);
        exit;
    }
}


/************************************************************************************************/
/******************************************CATEGORY UPDATE CODE***********************************/
/************************************************************************************************/

if(isset($_GET['categoryid']) && isset($_POST['categoryName'])) {

$categoryId = $_GET["categoryid"];

$filter = new Filter ($_POST["categoryName"], FILTER_SANITIZE_STRING);  
$categoryName = $filter -> sanitization();

$result = $conn -> query("SELECT id FROM categories WHERE id = '$categoryId';"); 

    if($result -> num_rows == 0) {
        $_SESSION['message'] = '¡Esta categoría no existe!';
        $_SESSION['message_alert'] = "danger";

        header('Location: ' . root . 'categories');
        exit;  
    } else {
        $stmt = $conn -> prepare("UPDATE categories SET name = ? WHERE id = ?;");
        $stmt->bind_param("si", $categoryName, $categoryId);

        if ($stmt -> execute()) {
            $_SESSION['message'] = '¡Categoría editada con éxito!';
            $_SESSION['message_alert'] = "success";

            //The page is redirected to the edit.php
            header('Location: ' . root . 'edit?categoryid='. $categoryId);
            exit;  
        } else {
            $_SESSION['message'] = '¡Error al editar categoría!';
            $_SESSION['message_alert'] = "danger";

            //The page is redirected to the edit.php
            header('Location: ' . root . 'edit?categoryid='. $categoryId);
            exit;
        }  
    } 
}

//Exit connection
$conn->close();
?>