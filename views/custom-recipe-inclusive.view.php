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
                ///Checking if there are ingredients added
                $result = new IngredientsData (null);
                $result = $result -> getIngredient();
                //Dropdown of the ingredients
                $customRecipeClass = new CustomRecipeClass ($result, "custom-inclusive", true);
                $ingredientsDropdown = $customRecipeClass -> ingredientsDropdownSelection();          
            ?> 
            </div>
        </form>
    </div>   
    <div class="row mt-2">
    <?php
    //Displaying the recipes
        $recipesDisplay = $customRecipeClass -> recipesDisplay();
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