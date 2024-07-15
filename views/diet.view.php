<?php
//Head
require_once ("views/partials/head.php");

//Nav
require_once ("views/partials/nav.php");

$_SESSION["lastcheck"] = 3;

//Messages
    if(isset($_SESSION['message'])){
    $message = new Messages ($_SESSION['message'], $_SESSION['message_alert']);
    echo $message -> buttonMessage();           

//Unsetting the messages
    unset($_SESSION['message_alert'], $_SESSION['message']);
    }

?>
<main class="container p-4">

<?php
    if(isset($_POST["generate"])) {   

//Receiving how many meals a day  
    $amount = $_POST["generate"];

//So the selection remains after reloading the page
    $_SESSION["lastcheck"] = $amount;

    $daysNames = ["Lunes", "Martes", "Miércoles", "Jueves", "Viernes", "Sábado", "Domingo"];
//Amount of days
    $dayCount = count($daysNames);
//Amount of recipes
    $totalRecipe = $amount * $dayCount;

    $limit = "LIMIT " . $totalRecipe;

    $result = $conn -> query("SELECT name, url FROM recipe ORDER BY rand() $limit;"); 
?>
<!-- Table with the diets-->
    <div class="d-flex flex-column-reverse p-2">  
        <div class="table-responsive mt-4">
            <table class="table table-bordered">
                <thead class="text-light text-center">       
                    <tr>
                    <?php
                        for($i = 0; $i < count($daysNames); $i++) {
                            echo "<th scope='col'><h4>" . $daysNames[$i] . "</h4></th>";
                        }
                    ?>    
                    </tr>
                </thead>
                <tbody>
                <?php
//Users recipes                     
                    $recipes = [];

                    while($row = $result -> fetch_assoc()) {
                        $recipe = [
                            'nombre' => $row['name'],
                            'url' => $row['url']
                        ];

                        $recipes [] = $recipe;
                    }

//Amount of recipes demanded are higher than the recipes availables                        
                    if(count($recipes) < $totalRecipe) {
                        $excess = $totalRecipe - count($recipes);
//New array with some of the recipes already added until completing the target
                        $newArray = [];
                        
                        while(count($newArray) < $excess) {
                            $newArray [] = $recipes [rand(0, count($recipes) - 1)];
                        }

//Completing the array so it can have the amount demanded
                        $recipes = array_merge($recipes, $newArray);
                    }

//Recipes chunks
                    $recipes = (array_chunk($recipes, $dayCount));

                    for($i = 0; $i < count($recipes); $i++) {
                        echo "<tr class='diet-elements'>";     
                        for($j = 0; $j < count($recipes [$i]); $j++) {              
                            echo "<td><a href='" . $recipes [$i][$j]["url"] . "'>" . $recipes [$i][$j]["nombre"] . "</a></td>";
                        }
                        echo "</tr>"; 
                    }

//Creating an array with the days and its corresponding recipes to be store
                    $daysRecipes = [];

                    for($i = 0; $i < $dayCount; $i++) {
//The array is emptied                        
                        $recipesDaysNames = [];
//The day is added
                        $recipesDaysNames [] = $daysNames[$i];
//The recipes are added
                        for($j = 0; $j < $amount; $j++) {                            
                            array_push($recipesDaysNames, $recipes [$j][$i]["nombre"]);                         
                        }
//Each resulted array is added to the main one
                        $daysRecipes [] = $recipesDaysNames;
                    }                 
                    
//Getting only the days
                    $days = [];

                    for($i = 0; $i < count($daysRecipes); $i++) {
                        array_push($days, $daysRecipes [$i] [0]);
                    }
//Recipes
                    $onlyRecipes = [];
//Getting only the recipes not the days
                    for($i = 0; $i < count($daysRecipes); $i++) {
                        $subOnlyRecipes = [];
                        for($j = 0; $j < count($daysRecipes [$i]); $j++) {
                            if($j != 0) {
                                array_push($subOnlyRecipes, $daysRecipes[$i][$j]);  
                            }                                    
                        }                         
                        array_push($onlyRecipes, $subOnlyRecipes);        
                    }
//Converting the array into string
                    $recipesString = [];

                    for($i = 0; $i < count($onlyRecipes); $i++) {
                        $recipesString [] = implode(",",$onlyRecipes [$i]);  
                    }
                ?>
                </tbody>
            </table>
        </div>

<!--  Saving recipe form-->
        <div class="row text-center justify-content-center p-2 mt-4">
            <form class="col-auto" method="POST" action="<?php echo root;?>create" id="diet_form">
                <?php
                    for($i = 0; $i < count($days); $i++) {
                        echo '<input type="hidden" name="days[]" value = "' . $days [$i] . '">';
                    }

                    for($i = 0; $i < count($recipesString); $i++) {
                        echo '<input type="hidden" name="data[]" value = "' . $recipesString [$i] . '">';
                    }                    
                ?>                         
                <input class="form-control mb-3" type="text" name="diet" pattern="[a-zA-Z áéíóúÁÉÍÓÚñÑ,;:]+" maxlength="40" minlength="7" id="diet_name" placeholder="Escriba el nombre de la dieta" required autofocus>
                <input class="btn btn-primary" type="submit" value="Agregar" title="Generar">
            </form>
        </div>
    </div>
<?php
    }
?>
<!--Form for the amount of meals a day-->
    <div class="row p-4 text-center justify-content-center">
        <form class="col-sm-8 col-md-9 col-lg-8 col-xl-8" method="POST" action="<?php echo root;?>diet" id="recipesPerDay">
            <h3 class="mb-3">Comidas</h3>
            <div class="mb-3 border py-4" style="background-color: rgb(255, 246, 234);">
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="generate" id="three" value="3" <?php if($_SESSION["lastcheck"] == 3) { echo "checked";}?> required>
                    <label class="form-check-label" for="three">3</label>
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="generate" id="four" value="4" <?php if($_SESSION["lastcheck"] == 4) { echo "checked";}?>>
                    <label class="form-check-label" for="four">4</label>
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="generate" id="five" value="5" <?php if($_SESSION["lastcheck"] == 5) { echo "checked";}?>>
                    <label class="form-check-label" for="five">5</label>
                </div>
            </div>
            <input class="btn btn-success" type="submit" value="Generar dieta" title="Generar">            
        </form>
    </div>
    <?php
    $resultDiet = $conn -> query ("SELECT id, name FROM diet ORDER BY name;");
    $num_rows = $resultDiet -> num_rows;   

    if($num_rows > 0) { 
    ?>
    <div class="row my-2">
        <span>Tienes <span style="font-weight: bold;"><?php  echo $num_rows;?></span> dietas</span>
    </div>
    <?php
        while ($row = $resultDiet -> fetch_assoc()) {
            $id = $row["id"];
            $dietname = $row["dietname"];

            $result = $conn -> query ("SELECT dd.recipes, dd.day, d.id FROM diet AS d JOIN diet_details AS dd ON dd.dietid = d.id WHERE d.username = '" . $_SESSION["username"] . "' AND dd.dietid = '$id' AND d.state = 1;"); 
    ?> 
    <div class="table-responsive my-4">         
        <div class="row">
            <h3 class="text-center"><a class="text-warning" style= "text-decoration: none;" href='<?php echo root . "delete?dietid=" . $id . "&username=" . $_SESSION['username']; ?>' title='Eliminar'>Dieta <?php echo $dietname;?></a></h3>
            <table class="table table-bordered">
                <thead class="text-light text-center">       
                    <tr>
                    <?php
                        while ($row = $result -> fetch_assoc()) {
                            echo "<th scope='col'><h4>" . $row["day"] . "</h4></th>";
                        }
                    ?>    
                    </tr>
                </thead>
                <tbody>
                <?php       
                    $result = $conn -> query ("SELECT dd.recipes, dd.day, d.id, d.dietname FROM diet AS d JOIN diet_details AS dd ON dd.dietid = d.id WHERE d.username = '" . $_SESSION["username"] . "' AND dd.dietid = '$id' AND d.state = 1;"); 
                    $recipes = [];
//List of days                    
                    while ($row = $result -> fetch_assoc()) {
                        array_push ($recipes, explode(",", $row ["recipes"]));
                    }
//List of recipes                
                    for($i = 0; $i < count($recipes[$i]); $i++) {
                        echo "<tr class='diet-elements'>";
                        for($j = 0; $j < count($recipes); $j++) {
                            echo "<td><a href='recipes?recipe=" . $recipes [$j][$i] . "&username=" . $_SESSION['username']. "'>" . $recipes [$j][$i] . "</a></td>";
                        } 
                        echo "</tr>"; 
                    }
                ?>
                </tbody>
            </table>
        </div>
    </div>
    <?php
        }
    } else {
    ?>
    <div class="row">
        <h3 class="text-center mt-4 p-4">No hay dietas agregadas aún...</h3>
    </div>
    <?php
    }
    ?>
</main>
<!--<script>
diet_validation();

//Diet addition validation method              
    function diet_validation() {
//Form   
        var form = document.getElementById("diet_form");
        form.addEventListener("submit", function(event){

        var regExp = /[a-zA-Z,;:\t\h]+|(^$)/;
        var dietname = document.getElementById("diet_name").value;
               
//Conditions
        if(dietname == ""){
            event.preventDefault();
            confirm("Completar los campos requeridos");             
            return false;
        }
//Regular Expression    
        if(!dietname.match(regExp)){
            event.preventDefault();
            confirm("¡Nombre de dieta incorrecto!");                 
            return false;
        }
//Diet length    
        if(dietname.length > 40 || dietname.length < 4){
            event.preventDefault();
            confirm("¡Dieta debe tener entre 4 y 40 caracteres!");  
            return false;
        }            
            return true;                           
        })
    }
</script>-->
<?php
require_once ("views/partials/footer.php");

//Exiting connection
$conn -> close();
?>