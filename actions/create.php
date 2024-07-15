<?php
//Iniciating session. 
session_start();

//Models.
require_once ("models/models.php");

//Including the database connection.
$conn = DatabaseConnection::dbConnection();

/************************************************************************************************/
/***************************************RECIPE ADITION CODE*************************************/
/************************************************************************************************/

//receive the data
if(isset($_POST["recipename"]) && isset($_POST["url"]) && isset($_POST['category']) && isset($_POST['cookingtime']) && isset($_POST['ingredients'])){

//Data sanitization
  $filter = new Filter ($_POST['recipename'], FILTER_SANITIZE_STRING);
  $recipename = $filter -> sanitization();

  $filter = new Filter ($_POST['cookingtime'], FILTER_SANITIZE_NUMBER_INT);
  $cookingtime = $filter -> sanitization();

  $filter = new Filter ($_POST['ingredients'], FILTER_SANITIZE_STRING);
  $ingredients = $filter -> sanitization();

  $categoryId = $_POST['category']; 
  $Url = $_POST["url"]; 

  //Getting the category name
  $categoryName = new FromIdToName($categoryId, "categories");
  $categoryName = $categoryName -> name();

  //Storing the category name and id in a session
  $_SESSION['categoryName'] = $categoryName;
  $_SESSION['categoryId'] = $categoryId;

  //Verifying if it already exists
  $result = $conn -> query("SELECT name FROM recipe WHERE name = '$recipename';");
  $num_rows = $result -> num_rows;
//Check if the recipe exists            
  if($num_rows == 0){
    $stmt = $conn -> prepare("INSERT INTO recipe (name, categoryid, ingredients, cookingtime, url) VALUES (?, ?, ?, ?, ?);");
    $stmt->bind_param ("sisis", $recipename, $categoryId, $ingredients, $cookingtime, $Url);
    
    $result = $stmt -> execute();  

    if($result) {
      $_SESSION['message'] = '¡Receta agregada con éxito!';
      $_SESSION['message_alert'] = "success";

      header('Location: ' . root . 'add-recipe');
      exit;
    } else {
        $_SESSION['message'] = 'Error al agregar receta';
        $_SESSION['message_alert'] = "danger";

        header('Location: ' . root . 'add-recipe');
        exit;
    }
  } else {
    $_SESSION['message'] = '¡Esta receta ya existe!';
    $_SESSION['message_alert'] = "danger";

    header('Location: ' . root . 'add-recipe');
    exit;
  }
}

/************************************************************************************************/
/***************************************CATEGORIES ADITION CODE**************************************/
/************************************************************************************************/

//receive the data
if(isset($_POST['add_categories'])){  
  $filter = new Filter ($_POST['add_categories'], FILTER_SANITIZE_STRING);
  $categoryName = $filter -> sanitization();

//lowercase the variable
  $category = strtolower($category);
//Check if the category had been added
  $result = $conn -> query("SELECT id FROM categories WHERE name = '$categoryName';");

  if ($result -> num_rows > 0) {
      $_SESSION['message'] = '¡Ya ha sido agregado!';
      $_SESSION['message_alert'] = "success";

      header('Location: ' . root . 'categories');
      exit;
  } else {
    $stmt = $conn -> prepare("INSERT INTO categories (name) VALUES (?);");
    $stmt->bind_param("s", $categoryName);

    if($stmt -> execute()) {
      $_SESSION['message'] = '¡Categoría agregada con éxito!';
      $_SESSION['message_alert'] = "success";

      $stmt -> close();

      header('Location: ' . root . 'categories');
      exit;
    } else {
      $_SESSION['message'] = '¡Error al agregar categoría!';
      $_SESSION['message_alert'] = "danger";

      header('Location: ' . root . 'categories');
      exit;
    }
  } 
}
/************************************************************************************************/
/***************************************INGREDIENT ADITION CODE**********************************/
/************************************************************************************************/

//receive the data
if(isset($_POST['add_ingredient'])){
  $filter = new Filter ($_POST['add_ingredient'], FILTER_SANITIZE_STRING);
  $ingredient = $filter -> sanitization();

//lowercase the variable
  $ingredient = strtolower($ingredient);

  $result = $conn -> query("SELECT id FROM ingredients WHERE name = '$ingredient';");
  $num_rows = $result -> num_rows;

//Check if it already exists
  if($num_rows != 0){
      $_SESSION['message'] = '¡Ya ha sido agregado!';
      $_SESSION['message_alert'] = "success";

      header('Location: ' . root . 'ingredients');
      exit;
  } else {
  $stmt = $conn -> prepare("INSERT INTO ingredients (name) VALUES (?);");
  $stmt->bind_param("s", $ingredient);

  $result = $stmt -> execute();  

  if ($result) {
      $_SESSION['message'] = '¡Ingrediente agregado con éxito!';
      $_SESSION['message_alert'] = "success";

      $stmt -> close();
      header('Location: ' . root . 'ingredients');
      exit;
    } else {
      $_SESSION['message'] = '¡Error al agregar ingrediente!';
      $_SESSION['message_alert'] = "danger";
          
      header('Location: ' . root . 'ingredients');
      exit;
    }
  }
}

/************************************************************************************************/
/***************************************INGREDIENTS REPOSITORY CODE******************************/
/************************************************************************************************/

//receive the data
if(isset($_POST['customid']) && isset($_POST['uri'])){ 
  $ingredientId = $_POST['customid'];
  $uri = $_POST['uri'];

  $result = $conn -> query("SELECT ingredientid FROM inglook WHERE ingredientid = '$ingredientId';");
//Check if the recipe has been added
  if($result -> num_rows > 0){
      $_SESSION['message'] = '¡Ya ha sido agregado!';
      $_SESSION['message_alert'] = "success";

      header('Location: ' . root . $uri);
      exit;
  } else {
    $stmt = $conn -> prepare("INSERT INTO inglook (ingredientid) VALUES (?);");
    $stmt->bind_param ("i", $ingredientId);

    if($stmt -> execute()) {
        $_SESSION['message'] = '¡Ingrediente agregado con éxito!';
        $_SESSION['message_alert'] = "success";

        $stmt -> close();            

        header('Location: ' . root . $uri);
        exit;
    } else {
      $_SESSION['message'] = '¡Error al agregar ingrediente!';
      $_SESSION['message_alert'] = "danger";
          
      header('Location: ' . root . $uri);
      exit;
    }
  }
}


/******************************************************************************************************************** */
/*************************************************DIET ADDING CODE*************************************************** */
/******************************************************************************************************************** */

if (isset($_POST['data']) && isset($_POST['diet']) && isset($_POST['days'])) {

$filter = new Filter ($_POST['diet'], FILTER_SANITIZE_STRING);
$dietName = $filter -> sanitization();

$data = $_POST['data'];
$days = $_POST['days'];

//Input validation object  
$inputs = ["El nombre de dieta" => [$dietName, [2,30], "incorrecto", true], 
"Los datos" => [$data, [], "incorrectos", false],
"Los días" => [$days, [], "incorrectos", false]]; 

$message = new InputValidation ($inputs, "/[a-zA-Z áéíóúÁÉÍÓÚñÑ,;:]/");  
$message = $message -> lengthValidation();

  if(count($message) > 0) {
    $_SESSION['message'] = $message [0];
    $_SESSION['message_alert'] = $message [1];          

    header('Location: ' . root . 'diet');
    exit;
  } 

//Inserting the recipe name
  $result = $conn -> query("INSERT INTO diet (dietname, username) VALUES ('$dietName', '". $_SESSION["username"]."');");

  if($result) {
//Getting the last id    
    $last_id = $conn->insert_id;
//Declaring the multi query
    $sql = "";
//Inserting the recipes details
    for($i = 0; $i < count($data); $i++) {
      $sql .= "INSERT INTO diet_details (day, recipes, dietid) VALUES ('" . $days[$i]. "', '". $data[$i] ."', '$last_id');";
    }

    if($conn -> multi_query($sql)) {
      $_SESSION['message'] = '¡Dieta agregada correctamente!';
      $_SESSION['message_alert'] = "success";

      header('Location: ' . root . 'diet');
      exit;
    } else {
      $_SESSION['message'] = '¡Error al agregar dieta!';
      $_SESSION['message_alert'] = "danger";

      header('Location: ' . root . 'diet');
      exit;
    }
  } else {
      $_SESSION['message'] = '¡Error al agregar dieta!';
      $_SESSION['message_alert'] = "danger";

      header('Location: ' . root . 'diet');
      exit;
  }
}

//Exiting db connection.
$conn -> close(); 
?>