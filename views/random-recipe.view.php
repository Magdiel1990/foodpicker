<?php
//Head
require_once ("views/partials/head.php");

//Nav
require_once ("views/partials/nav.php");
?>
<!-- Form to choose the category for the recipe suggestion-->
<main class="container p-4">
    <div class="row mt-2 text-center justify-content-center">
        <h3>Sugerencias</h3>
        <form action= "<?php echo root;?>random" method="POST" class="mt-3 col-auto">
            <div class="input-group mb-3">
                <label for="category" class="input-group-text">Categoría: </label>
                
                <select class="form-select" name="categoryId" id="category" autofocus>
                    <?php
                    $sql = "SELECT * FROM categories;";
                    //Getting the categories
                    $result = $conn -> query($sql); 

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
    $sql = "SELECT name, cookingtime, url FROM recipe WHERE categoryid = '$categoryId' ORDER BY rand() LIMIT 10;";

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
?>
</main>
<?php
//Exiting connection
$conn -> close();

//Footer
require_once ("views/partials/footer.php");
?>
