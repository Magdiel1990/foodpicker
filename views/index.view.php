<?php
//Head
require_once ("views/partials/head.php");

//Nav
require_once ("views/partials/nav.php");

//Array with the columns to show
$columns = ["r.id", "r.name", "c.name as `category`", "r.url", "r.cookingtime"];
$columns = implode(", ", $columns);

//Condition for the search
$where = "";
//If there is a search
if(isset($_POST["search"])){
//Sanitizing the search
$search = $conn -> real_escape_string($_POST["search"]);

//Storing the search in a session
$_SESSION['search'] = $search;
//Setting the focus
$_SESSION['focus'] = ""; 

$where .= "WHERE r.name LIKE '%$search' OR c.name LIKE '%$search' OR r.cookingtime LIKE '%$search' OR r.url LIKE '%$search'";

$sql = "";
$sql .= "SELECT $columns FROM recipe as r join categories as c on r.categoryid = c.id $where;";
//If there is no search
} else {
    //If there is a session search
    $_SESSION['search'] = "";
    //Setting the focus
    $_SESSION['focus'] = "autofocus";         

    $sql = "";
    $sql .= "SELECT $columns FROM recipe as r join categories as c on r.categoryid = c.id $where;";    
}
?>

<main class="container py-4">
<?php
//Messages
    if(isset($_SESSION['message'])){
    $message = new Messages ($_SESSION['message'], $_SESSION['message_alert']);
    echo $message -> buttonMessage();         

//Unsetting the messages
    unset($_SESSION['message_alert'], $_SESSION['message']);
    }
?>

<!--Form for filtering the recipes-->
<div class="row mt-2 g-2 justify-content-center">
        <div class="col-auto">
            <form action="" method="POST" class="input-group mb-3">
                <input class="form-control" type="text" value="<?php echo $_SESSION['search']; ?>" id="search" name="search" maxlength="300" <?php echo $_SESSION["focus"];?>> 
                <input type="submit" class="btn btn-primary" value="Buscar">
            </form>
        </div>
    </div>
<!-- Table to show the recipes-->

<?php
    //Counting the recipes  
    $result = $conn -> query($sql);

    if($result -> num_rows == 0){
        echo "<div class='alert alert-warning text-center' role='alert'>No hay recetas disponibles.</div>";
    } else {
?>
    <div class="table-responsive-sm mt-4">
        <table class="table table-condensed shadow" id="recipe-table">
            <thead>
                <tr class="table_header text-center">
                    <th scope="col">Receta</th>
                    <th scope="col">Tiempo</th>
                    <th scope="col">Categoría</th>
                    <th scope="col">Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php
                //Showing the recipes
                while($row = $result -> fetch_assoc()){ 
                    echo "<tr class='text-center'>";
                    echo "<td><a href='" . $row['url'] . "'>".$row['name']."</a></td>";
                    echo "<td>".$row['cookingtime']."</td>";
                    echo "<td>".$row['category']."</td>";
                    echo "<td>";
                    echo "<span class='btn-group'>";
                    echo "<a href='" . root . "edit?recipeid=" . $row['id'] . "' class='btn btn-outline-secondary' title='Editar'><i class='fa-solid fa-pen'></i></a>";
                    echo "<a href='" . root . "delete?recipeid=" . $row['id'] . "' class='btn btn-outline-danger' title='Eliminar' onclick='deleteMessage()' id='recipe'><i class='fa-solid fa-trash'></i></a>";
                    echo "</span>";
                    echo "</td>";
                    echo "</tr>";            
                }
                ?>
            </tbody>
        </table>
    </div>
<?php
    }
?>
</main>
<!-- Ajax script-->
<script>
//Delete message
function deleteMessage(){
    var deleteButtons = document.getElementById("recipe");
  
    if(confirm("¿Desea eliminar esta receta?")) {
        return true;
    } else {
        event.preventDefault();
        return false;
    }
}
</script>
<?php
//Footer.
require_once ("views/partials/footer.php");
?>