<?php
//Head
require_once ("views/partials/head.php");

//Nav
require_once ("views/partials/nav.php");
?>

<main class="container p-4">
    <?php
//Messages
        if(isset($_SESSION['message'])){
        $message = new Messages ($_SESSION['message'], $_SESSION['message_alert']);
        echo $message -> buttonMessage();           

//Unsetting the messages
        unset($_SESSION['message_alert'], $_SESSION['message']);
        }
    ?>
    
    <div class="m-2 justify-content-center row">         
        <h3 class="text-center mb-3">Agregar Receta</h3>
        <div class="col-lg-8 col-md-12 col-sm-12 col-xl-7">
            <form class="text-center form" id="add_recipe_form" method="POST" action="<?php echo root;?>create">
            
                <div class="input-group mb-3">
                    <label class="input-group-text is-required" for="recipename">Nombre: </label>
                    <input  class="form-control" type="text" id="recipename" name="recipename" maxlength="100" minlength="7" required autofocus>             
                </div>

                <div class="input-group mb-3">
                    <label class="input-group-text is-required" for="ingredients">Ingredientes: </label>
                    <textarea class="form-control" name="ingredients" id="ingredients" cols="10" rows="8" required></textarea>             
                </div>

                <div class="input-group mb-3">
                    <label class="input-group-text is-required" for="cookingtime">Tiempo: </label>
                    <input  class="form-control" type="number" id="cookingtime" name="cookingtime" max="100" min="5" required>             
                </div>             

                <div class="input-group mb-3">
                    <label class="input-group-text is-required" for="url">Enlace: </label>
                    <input  class="form-control" type="url" id="url" name="url" minlength="7" required>             
                </div> 

                <div class="input-group mb-3">
                    <label class="input-group-text is-required" for="category">Categoría: </label>                
                    <select class="form-select" name="category" id="category">
                        <?php
                        //Checking if the session is set
                        if(isset($_SESSION['categoryName']) && isset($_SESSION['categoryId'])) {
                            $where = "WHERE id <> '" . $_SESSION['categoryId'] . "'";
                        //Displaying first category
                            echo '<option value="' . $_SESSION['categoryId'] . '">' . $_SESSION['categoryName'] .'</option>';
                        } else {
                        //If the session is not set
                            $where = "";
                        }
                        //Getting the categories
                            $result = new CategoriesData (null);
                            $result = $result -> getCategory();
                            
                            if($result -> num_rows > 0) {
                                while($row = $result -> fetch_assoc()) {
                                    echo '<option value="' . $row["id"] . '">' . ucfirst($row["name"]) . '</option>';
                                }
                            //If there are no categories
                            } else {
                                echo '<option value="0">No hay categorías</option>';
                            }
                        ?>
                    </select>
                </div>
                <div>
                    <input class="btn btn-primary" type="submit" value="Agregar" name="addrecipe">
                </div> 
                <!-- <div class="mt-3" id="message"></div> -->              
            </form>       
        </div>
    </div>
</main>
<?php
//Exiting connection
$conn -> close();

//Footer
require_once ("views/partials/footer.php");
?>