<?php
//Models.
require_once ("models/models.php");

//Path requested
$uri = parse_url($_SERVER["REQUEST_URI"])['path']; 

//Parameters coming with that path
$param = isset(parse_url($_SERVER["REQUEST_URI"])['query']) ? parse_url($_SERVER["REQUEST_URI"])['query'] : "";

//No parameters
if($param == "") {
    $routes = [
    root => "controllers/index.controller.php",    
    root. "random" => "controllers/random.controller.php",
    root. "create" => "controllers/create.controller.php",
    root. "custom-inclusive" => "controllers/custom-recipe-inclusive.controller.php",
    root. "custom-exclusive" => "controllers/custom-recipe-exclusive.controller.php",
    root. "ingredients" => "controllers/ingredients.controller.php",
    root. "add-recipe" => "controllers/add-recipe.controller.php",
    root. "categories" => "controllers/categories.controller.php",    
    root. "error404" => "controllers/404.controller.php", 
    root. "not-found" => "controllers/notfound.controller.php",
    root. "settings" => "controllers/settings.controller.php",
    root. "update" => "controllers/update.controller.php",
    root. "diet" => "controllers/diet.controller.php",
    root. "reset" => "controllers/reset.controller.php"  
    ];
//It comes with parameters
} else {
    $routes = [    
    root. "recipes" => "controllers/recipes.controller.php",
    root. "random" => "controllers/random.controller.php",
    root. "delete" => "controllers/delete.controller.php",
    root. "edit" => "controllers/edit.controller.php",
    root. "create" => "controllers/create.controller.php",
    root. "update" => "controllers/update.controller.php"           
    ];
}

//If the uri exists the controllers is called
if(array_key_exists($uri, $routes)) {
    require $routes[$uri];
} else {
    require "controllers/404.controller.php";
}
?>