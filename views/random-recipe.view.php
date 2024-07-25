<?php
//Head
require_once ("views/partials/head.php");

//Nav
require_once ("views/partials/nav.php");
?>
<!-- Form to choose the category for the recipe suggestion-->
<main class="container p-4">

    <?php
    //Verify if there are recipes
    $recipesAccount = new TotalRecipes();
    $recipesAccount = $recipesAccount -> total();

    if($recipesAccount == 0) {
        echo "<div class='text-center mt-4 p-4'>";
        echo "<div class='alert alert-warning' role='alert'>No hay recetas disponibles.</div>"; 
        echo "<a class='btn btn-primary' href='" . root . "add-recipe' title='Agregar receta'>Agregar</a>";
        echo "</div>";
    } else {
        //Getting the categories
        $result = new CategoriesData (null);
        $result = $result -> getCategory(); 
    ?>
    <div class="row mt-2 text-center justify-content-center">
        <h3>Sugerencias</h3>
        <form action= "<?php echo root;?>random" method="POST" class="mt-3 col-auto">
            <div class="input-group mb-3">
                <label for="category" class="input-group-text">Categoría: </label>                
                <select class="form-select" name="categoryId" id="category" autofocus>
                    <?php  
                    while($row = $result -> fetch_assoc()) {
                        echo "<option value='" . $row["id"] . "'>" . ucfirst($row["name"]) . "</option>";
                    }
                    ?>
                </select>

                <input class="btn btn-primary" type="submit" value="Sugerir">
            </div>
        </form>
    </div>

    <?php
//If a category is chosen
    if(isset($_POST["categoryId"])) {
    $categoryId = $_POST["categoryId"];

//Random recipe for that category
    $sql = "SELECT name, cookingtime, url FROM recipe WHERE categoryid = '$categoryId' ORDER BY rand();";

    $result = $conn -> query($sql);
//If there is no recipe
        if($result -> num_rows == 0){
            echo "<p class='mt-5 text-center'>¡No hay recetas disponibles para esta categoría!</p>";
//If there is       
        } else {
//Showing the recipes
        while($row = $result -> fetch_assoc()) {
            //Data
            $recipename = $row["name"];
            $cookingtime = $row["cookingtime"];
            $url = $row["url"];
            ?>
            <!-- Recipe card -->
            <div class="my-2">
                <a href='<?php echo $url;?>'>
                    <?php echo $recipename . " (" . $cookingtime . " minutos)";?>     
                </a>
                <?php
                }
                ?>     
            </div>
        <?php
        }
        ?>     
    </div>
<?php
    }
}
?>
</main>
<?php
//Exiting connection
$conn -> close();

//Footer
require_once ("views/partials/footer.php");
?>
