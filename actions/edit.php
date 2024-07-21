<?php
//Head of the page.
require_once ("views/partials/head.php");

//Navigation panel of the page
require_once ("views/partials/nav.php");

/************************************************************************************************/
/******************************************RECIPE EDITION CODE***********************************/
/************************************************************************************************/

if(isset($_GET['recipeid'])) {
    //Recipe id
    $id = $_GET['recipeid'];  
    
    //Query to get the recipe data
    $recipeData = new RecipesData ($id);
    //Array with the recipe data
    $recipeData = $recipeData -> getRecipeData();
    ?>
    <main class="container p-4">
    <?php
    //Messages that are shown in the index page
        if(isset($_SESSION['message'])){
            $message = new Messages ($_SESSION['message'], $_SESSION['message_alert']);
            echo $message -> buttonMessage();       
        
    //Unsetting the messages variables so the message fades after refreshing the page.
            unset($_SESSION['message_alert'], $_SESSION['message']);
        }
    ?>
        <div class="row mt-2 justify-content-center">
            <h3 class="text-center">Editar Receta</h3>     
            <div class="mt-3 col-auto">
                <div class="form card card-body">
                    <!--Form to edit the recipe-->
                    <form id="recipe_form" action="update?recipeid=<?php echo $id;?>" method="POST">
    
                        <div class="input-group mb-3">
                            <label class="input-group-text is-required" for="recipeName">Nombre: </label>
                            <input type="text" name="recipeName" value="<?php echo $recipeData["name"];?>" class="form-control" id="recipeName" maxlength="100" minlength="7" required autofocus>
                        </div>

                        <div class="input-group mb-3">
                            <label class="input-group-text is-required" for="recipeTime">Tiempo: </label>
                            <input type="number" name="recipeTime" value="<?php echo $recipeData["cookingtime"];?>" class="form-control" min = 5 max = 200 id="recipeTime" required>
                        </div>

                        <div class="input-group mb-3">
                            <label class="input-group-text is-required" for="newIngredients">Ingredientes: </label>
                            <textarea class="form-control" name="ingredients" id="ingredients" cols="10" rows="8" required><?php echo $recipeData["ingredients"];?></textarea>
                        </div>

                        <div class="input-group mb-3">
                            <label class="input-group-text is-required" for="recipeUrl">URL: </label>
                            <input type="text" name="recipeUrl" value="<?php echo $recipeData["url"];?>" class="form-control" id="recipeUrl" minlength="7" required>
                        </div> 

                        <div class="row">
                            <div class="input-group mb-3 col">
                                <label class="input-group-text" for="category">Categoría: </label>                
                                <select class="form-select" name="category" id="category">                                    
                                    <?php      
                                    //Query to get the categories
                                    $row = new CategoriesData ($categoryId, false);
                                    $row = $row -> getCategoriesRow();
                           
                                    //Showing the categories
                                    echo '<option value="' . $recipeData["categoryid"] . '">' .  ucfirst($recipeData ["category"]) . '</option>';                                    

                                    while($row = $result -> fetch_assoc()) {
                                        echo '<option value="' . $row["id"]  . '">' . ucfirst($row["name"]) . '</option>';
                                    }
                                    ?>
                                </select>
                            </div>                                
                        </div>

                        <!--Buttons to edit the recipe-->
                        <div class="mt-2 text-center">
                            <input class="btn btn-primary" type="submit" value="Editar" name="recipeEditSubmit">
                            <a href= "<?php echo root;?>" class="btn btn-secondary">Regresar</a>
                        </div>    

                    </form>                   
                </div>
            </div>                 
        </div> 
    </main>
    <?php
    } /*else {
        header('Location: ' . root . 'error404');
        die();
    }*/

/************************************************************************************************/
/******************************************CATEGORY EDITION CODE***********************************/
/************************************************************************************************/

if(isset($_GET['categoryid'])){
$categoryId = $_GET['categoryid'];

//Query to get the categories
$row = new CategoriesData ($categoryId);
$row = $row -> getCategoriesRow();

//Category name
$category = $row["name"];

?>
<main class="container p-4">
<?php
//Messages that are shown in the index page
    if(isset($_SESSION['message'])) {
    $message = new Messages ($_SESSION['message'], $_SESSION['message_alert']);
    echo $message -> buttonMessage();         

//Unsetting the messages variables so the message fades after refreshing the page.
    unset($_SESSION['message_alert'], $_SESSION['message']);
    }
?>
    <div class="row mt-2 text-center justify-content-center">
        <h3>EDITAR CATEGORÍA</h3>     
        <div class="mt-3 col-auto">
            <form id="category_form" class="form card card-body" action="update?categoryid=<?php echo $categoryId; ?>" method="POST">

                <div class="input-group mb-3">
                    <label class="input-group-text is-required" for="categoryName">Nombre: </label>
                    <input type="text" name="categoryName" value="<?php echo $category;?>" class="form-control" id="categoryName" pattern="[a-zA-Z áéíóúÁÉÍÓÚñÑ,;:]+" maxlength="20" minlength="2" required autofocus>
                </div>

                <div class="mt-2">
                    <input class="btn btn-primary" type="submit" value="Editar" name="categoryeditionsubmit">
                    <a href= "<?php echo root;?>categories" class="btn btn-secondary">Regresar</a>
                </div>
                </form>
               <!-- <script>
                formValidation();   
               
//Image format validation
                function formValidation(){

                    var form = document.getElementById("category_form");    

                    form.addEventListener("submit", function(event) { 
//Accepted formats            
                        var ext=/(.jpg|.JPG|.jpeg|.JPEG|.png|.PNG|.webp|.WEBP)$/i;
                        var regExp = /[a-zA-Z,;:\t\h]+|(^$)/;
                        var categoryImageInput = document.getElementById('categoryImage');
                        var categoryImage = categoryImageInput.value;                            
                        var categoryNameInput = document.getElementById('categoryName');
                        var categoryName = categoryNameInput.value;
                        var allowedImageTypes = ["image/jpeg", "image/gif", "image/png", "image/jpg" , "image/webp"];  

                        if(categoryName == "") {
                            event.preventDefault();                
                            confirm ("¡Escriba el nombre de la categoría!");                                
                            return false;
                        }

                        if(categoryName.length < 2 || categoryName.length > 20) {
                            event.preventDefault();
                            confirm("¡Longitud de categoría incorrecta!");               
                            return false;                
                        }
                        
                        if(!categoryName.match(regExp)){ 
                            event.preventDefault();                
                            confirm ("¡Nombre de categoría incorrecto!");                                
                            return false;
                        }

                        if (categoryImage != "") {
                            var file = categoryImageInput.files[0];                   
                            var fileType = file.type;  
//Weight of the file                        
                            var weight = file.size;
//Size in Bytes     
                            if(weight > 300000) {
                                event.preventDefault();
                                confirm ("¡El tamaño de la imagen debe ser menor que 300 KB!");  
                                return false;
                            }       
//Image format validation
                            if(!allowedImageTypes.includes(fileType)){
                                event.preventDefault();
                                confirm ("¡Formatos de imagen admitidos: webp, jpg, png y gif!");
                                return false;
                            }                            
                        }
                        return true;               
                    })
                }
                </script> -->   
            </div>
       </div>                  
    </div>     
</main>

<?php
}

/************************************************************************************************/
/**************************************INGREDIENT EDITION CODE***********************************/
/************************************************************************************************/
if(isset($_GET['ingredientid'])){
    $id = $_GET['ingredientid'];

//Query to get the ingredients
    $row = new IngredientsData ($id);
    $row = $row -> getIngredientsRow();

    $ingredientName = $row["name"];
?>
<main class="container p-4">
<?php
//Messages that are shown in the index page
    if(isset($_SESSION['message'])) {
    $message = new Messages ($_SESSION['message'], $_SESSION['message_alert']);
    echo $message -> buttonMessage();         

//Unsetting the messages variables so the message fades after refreshing the page.
    unset($_SESSION['message_alert'], $_SESSION['message']);
    }
?>
    <div class="row mt-2 text-center justify-content-center">
        <h3>EDITAR INGREDIENTE</h3>     
        <div class="mt-3 col-auto">
            <form id="ingredient_form" class="form card card-body" action="update?ingredientId=<?php echo $id; ?>" method="POST">

                <div class="input-group mb-3">
                    <label class="input-group-text is-required" for="ingredientName">Ingrediente: </label>
                    <input type="text" name="ingredientName" value="<?php echo $ingredientName;?>" class="form-control" id="ingredientName" pattern="[a-zA-Z áéíóúÁÉÍÓÚñÑ,;:]+" maxlength="50" minlength="2" required autofocus>
                </div>
                <div class="mt-2">
                    <input class="btn btn-primary" type="submit" value="Editar" name="ingredienteditionsubmit">
                    <a href= "<?php echo root;?>ingredients" class="btn btn-secondary">Regresar</a>
                </div>
                </form>
     <!--           <script>
                formValidation();   
               
//Image format validation
                function formValidation(){

                    var form = document.getElementById("ingredient_form");    

                    form.addEventListener("submit", function(event) { 
      
                        var regExp = /[a-zA-Z,;:]/;                        
                        var ingredientNameInput = document.getElementById('ingredientName');
                        var ingredientName = ingredientNameInput.value;
                       
                        if(ingredientName == "") {
                            event.preventDefault();                
                            confirm ("¡Escriba el nombre del ingrediente!");                                
                            return false;
                        }

                        if(ingredientName.length < 2 || ingredientName.length > 50) {
                            event.preventDefault();
                            confirm("¡Longitud de ingrediente incorrecta!");               
                            return false;                
                        }
                        
                        if(!ingredientName.match(regExp)){ 
                            event.preventDefault();                
                            confirm ("¡Nombre de ingrediente incorrecto!");                                
                            return false;
                        }                       
                        return true;               
                    })
                }
                </script>    -->
            </div>
       </div>                  
    </div>     
</main>

<?php
}
//Closing the connection
$conn -> close();    

//Footer of the page.
require_once ("views/partials/footer.php");
?>