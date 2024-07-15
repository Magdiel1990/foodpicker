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
    <div  class="row mt-2 text-center justify-content-center">
<!--Form for choosing the ingredients-->
        <h3>Elegir por Ingrediente</h3>
        <form class="m-3 col-auto" method="POST" action="<?php echo root;?>create">
<!-- List of ingredients -->
           <div class="input-group">
                <label class="input-group-text" for="customingredient">Ingredientes: </label>
                <?php
//Checking if there are ingredients added
                $result = $conn -> query("SELECT id FROM ingredients;");

                if($result -> num_rows > 0) {
                    
//Getting the ingredients
                    $result = $conn -> query("SELECT * FROM ingredients WHERE id NOT IN (SELECT ingredientid FROM inglook);");
                    //If there are no ingredients added
                    if($result -> num_rows == 0){
                        echo "<div value=''></div>";
                    //If there are ingredients added
                    } else {
                        echo "<select class='form-select' name='customid' id='customid' autofocus>";
                        while($row = $result -> fetch_assoc()) {
                            echo "<option value='" . $row['id'] . "'>" . ucfirst($row['name']) . "</option>";
                        }
                        echo "</select> ";
                        echo '<input type="hidden" name="uri" value="custom-exclusive">';
                        echo '<input class="btn btn-primary" type="submit" value="Agregar"> ';
                    }                  
                ?>                
                <?php
//If there is no ingredient added                
                } else {
                ?>
                <a class="btn btn-primary" href="<?php echo root;?>ingredients">Agregar</a>
                <?php 
                } 
                ?> 
            </div>
        </form>
    </div>
    <div class="row mt-4">
        <?php
//List of chosen ingredients 
        $result = $conn -> query("SELECT i.name as `ingredient`, il.ingredientid as `id` FROM inglook as il join ingredients as i on il.ingredientid = i.id;");

        if($result -> num_rows == 0){
            echo "<p class='text-center'>Agregue los ingredientes para conseguir recetas...</p>";
        } else {
            $html = "<div class='col-auto'>";
            $html .= "<ul class='custom-list'>";
            while($row = $result -> fetch_assoc()) {
                $html .= "<li>";
                $html .= "<a href='" . root . "delete?customid=" . $row['id'] . "&uri=custom-exclusive' " . "title='Eliminar' class='click-del-link'>";
                $html .= ucfirst($row["ingredient"]);
                $html .= "</a>";
                $html .= "</li>";
//Ingredients are added into an array                
                $ingArray[] = $row["ingredient"];
            }
            $html .= "</ul>";
            $html .= "</div>";     
            echo $html;
        }
        ?>
    </div>
    <div class="row mt-2">
    <?php
    //Array containing the chosen recipes        
        if(isset($ingArray)){        
        $result = $conn -> query ("SELECT id, ingredients FROM recipe;");
        //Recipes
        $recipes = [];
            while($row = $result -> fetch_assoc()) {
                //Counter for the ingredients
                $counter = 0;
                //Checking if the ingredients are in the recipe
                for ($i = 0; $i < count($ingArray); $i++) {
                    if(strpos(strtolower($row["ingredients"]), $ingArray[$i]) == false) {
                        $counter += 1;
                    }
                }
                //If all the ingredients are in the recipe
                if($counter == count($ingArray)) {
                    $recipes[] = $row["id"];
                }
            }         
        if(count($recipes) == 0){
            echo "<p class='text-center'>No hay recetas con estos ingredientes...</p>";
        } else {
            $html = "<h3 class='text-center'>Recetas</h3>";
            $html .= "<div class='col-auto'>";
            $html .= "<ul class='custom-list' id='recipe-table'>";
            for($i = 0; $i < count($recipes); $i++) {
                //Getting the recipe name
                $result = $conn -> query("SELECT name, url FROM recipe WHERE id = " . $recipes[$i] . ";");
                $row = $result -> fetch_assoc();
                //Displaying the recipe name
                $html .= "<li>";
                $html .= "<a href='". $row["url"] . "'>" . ucfirst($row["name"]) . "</a>";
                $html .= "</li>";
            }
            $html .= "</ul>";
            $html .= "</div>";     
            echo $html;
        }
    }
    ?>
    </div>    
</main>
<script>
deleteMessage("click-del", "ingrediente");   

//Delete message
function deleteMessage(button, pageName){
var deleteButtons = document.getElementsByClassName(button);

    for(var i = 0; i<deleteButtons.length; i++) {
        deleteButtons[i].addEventListener("click", function(event){    
            if(confirm("¿Desea eliminar este " + pageName + "?")) {
                return true;
            } else {
                event.preventDefault();
                return false;
            }
        })
    }
}
</script>

<?php
//Exiting connection
$conn -> close();

//Footer
require_once ("views/partials/footer.php");
?>