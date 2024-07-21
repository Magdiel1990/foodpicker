<?php
//Directory root
define("root", "/foodpicker/");

class DatabaseConnection {
//Database information
    static $hostname = "localhost:3306";
    static $username = "root";
    static $password = "123456";
    static $database = "foodday";

//Connection to the database.
    public static function dbConnection(){
        $conn = new mysqli(self::$hostname, self::$username, self::$password, self::$database);
        return $conn;
    }
}

//Class for the messages
class Messages {
    public $message;
    public $message_alert;

    function __construct($message, $message_alert){
        $this -> message = $message;
        $this -> message_alert = $message_alert;
    }    

//Method for the button message.
    public function buttonMessage() {
        if(isset($this -> message_alert)){
            $html = "<div class='row justify-content-center mt-2'>";
            $html .= "<div class='col-auto alert alert-" . $this -> message_alert . " alert-dismissible fade show' role='alert'>";
            $html .= "<button type='button' class='close border-0' data-dismiss='alert' aria-label='Close'>";
            $html .= "<span>" . $this -> message . "</span>";        
            $html .= "</button>";
            $html .= "</div>"; 
            $html .= "</div>";   
            return $html;             
        }
    }

//Method for the text message.
    public function textMessage() {
        if(isset($this -> message_alert)){
            $html = "<p class='pb-2 mb-0 pb-0 text-" . $this -> message_alert . " small'>";
            $html .= $this -> message;
            $html .= "</p>"; 
            return $html;             
        }
    }    
}

//Input sanitization
class Filter {
public $input;
public $type; 

    function __construct($input, $type){
        $this -> input = $input;
        $this -> type = $type;
    }

    public function sanitization() {
        $conn = DatabaseConnection::dbConnection();

        $this -> input = mysqli_real_escape_string($conn, $this -> input);   
        $this -> input = htmlspecialchars($this -> input);
        $this -> input = filter_var($this -> input, $this -> type);
        $this -> input = trim($this -> input);
        $this -> input = stripslashes($this -> input);
        return $this -> input;
    }
}

//Get the name from the id
class FromIdToName {
    public $id;
    public $table;
    public $idName;

    function __construct($id, $table, $idName = "id"){
        $this -> id = $id;
        $this -> table = $table;
        $this -> idName = $idName;
    }
    //Get the name
    private function getName() {
        $conn = DatabaseConnection::dbConnection();
        $sql = "SELECT name FROM " . $this -> table . " WHERE " . $this -> idName . " = '" . $this -> id ."';";
   
        return $conn -> query($sql);
    }

    private function getRow() {
        return $this -> getName() -> fetch_assoc();
    }

    public function name() {
        return $this -> getRow()['name'];
    }

    public function rows() {
        return $this -> getName() -> num_rows;
    }
}

//Verify the existance of the new name
class FromNameToIdVerifying {
    public $name;
    public $table;

    function __construct($name, $table){
        $this -> name = $name;
        $this -> table = $table;
    }

    private function getId() {
        $conn = DatabaseConnection::dbConnection();
        $sql = "SELECT id FROM " . $this -> table . " WHERE name = '" . $this -> name ."';";

        return $conn -> query($sql);
    }

    public function rows() {
        return $this -> getId() -> num_rows;
    }
}

//Get the id from the id
class FromIdToId {
    public $id;
    public $table;
    public $idName;

    function __construct($id, $table, $idName = "id"){
        $this -> id = $id;
        $this -> table = $table;
        $this -> idName = $idName;
    }
    //Get the name
    private function getName() {
        $conn = DatabaseConnection::dbConnection();
        $sql = "SELECT id FROM " . $this -> table . " WHERE " . $this -> idName . " = '" . $this -> id ."';";
   
        return $conn -> query($sql);
    }

    public function rows() {
        return $this -> getName() -> num_rows;
    }
}

//Get the total of recipes
class TotalRecipes {
    private function totalRecipes() {
        $conn = DatabaseConnection::dbConnection();
        $sql = "SELECT COUNT(*) as total FROM recipe;";

        return $conn -> query($sql);
    }

    private function getRow() {
        return $this -> totalRecipes() -> fetch_assoc();
    }

    public function total() {
        return $this -> getRow()['total'];
    }
}

//Get recipe data
class RecipesData {
    public $id;

    function __construct($id){
        $this -> id = $id;
    }

    private function getRecipe() {
        $conn = DatabaseConnection::dbConnection();
        $sql = "SELECT r.name, r.ingredients, r.cookingtime, r.url, c.name as `category`, r.categoryid FROM recipe as r join categories as c on r.categoryid = c.id WHERE r.id = '" . $this -> id . "';";

        return $conn -> query($sql);
    }

    private function getRow() {
        return $this -> getRecipe() -> fetch_assoc();
    }

    public function getRecipeData() {
        $row = $this -> getRow();
        $data = array(
            "name" => $row['name'],
            "ingredients" => $row['ingredients'],
            "cookingtime" => $row['cookingtime'],
            "url" => $row['url'],
            "category" => $row['category'],
            "categoryid" => $row['categoryid']
        );

        return $data;
    }
}

//Get the categories data
class CategoriesData {
    public $id;
    public $flag;
    public $order;

    function __construct($id, $flag = true, $order = "asc"){
        $this -> id = $id;
        $this -> flag = $flag;
        $this -> order = $order;
    }

    public function getCategory() {
        $conn = DatabaseConnection::dbConnection();

        if($this -> flag){
            $where = "WHERE id = '" . $this -> id . "'";
        } else {
            $where = "WHERE NOT id = '" . $this -> id . "'";
        }

        if($this -> order == "asc"){
            $order = "ORDER BY name ASC";
        } else {
            $order = "ORDER BY name DESC";
        }

        //If the id is null, the where clause is empty
        if($this -> id == null){
            $where = "";
        }

        $sql = "SELECT * FROM categories $where $order;";

        return $conn -> query($sql);
    }

    public function getCategoriesRow() {
        $result = $this -> getCategory();   
        return $result -> fetch_assoc();
    }
}

//Get the ingredients data
class IngredientsData {
    public $id;
    public $flag;
    public $order;

    function __construct($id, $flag = true, $order = "asc"){
        $this -> id = $id;
        $this -> flag = $flag;
        $this -> order = $order;
    }

    public function getIngredient() {
        $conn = DatabaseConnection::dbConnection();

        if($this -> flag){
            $where = "WHERE id = '" . $this -> id . "'";
        } else {
            $where = "WHERE NOT id = '" . $this -> id . "'";
        }

        if($this -> order == "asc"){
            $order = "ORDER BY name ASC";
        } else {
            $order = "ORDER BY name DESC";
        }

        //If the id is null, the where clause is empty
        if($this -> id == null){
            $where = "";
        }

        $sql = "SELECT * FROM ingredients $where $order;";

        return $conn -> query($sql);
    }

    public function getIngredientRow() {
        $result = $this -> getIngredient();   
        return $result -> fetch_assoc();
    }
}

class CustomRecipeClass {
    public $result;
    public $page;

    function __construct($result, $page){
        $this -> result = $result;
        $this -> page = $page;
    }

    public function ingredientsDropdownSelection() {
        if($this -> result -> num_rows > 0) {
            $conn = DatabaseConnection::dbConnection();             
           
            if($this -> result -> num_rows > 0){
            //Getting the ingredients
                 $result = $conn -> query("SELECT * FROM ingredients WHERE id NOT IN (SELECT ingredientid FROM inglook);");

                 echo "<select class='form-select' name='customid' id='customid' autofocus>";
                 while($row = $result -> fetch_assoc()) {
                     echo "<option value='" . $row['id'] . "'>" . ucfirst($row['name']) . "</option>";
                 }
                 echo "</select> ";
                 echo '<input type="hidden" name="uri" value="' .  $this -> page . '">';
                 echo '<input class="btn btn-primary" type="submit" value="Agregar"> ';
           //If there are no ingredients added
            } else {
                echo "<div value=''></div>";
            }                  
        //If there is no ingredient added                
        } else {
            echo '<a class="btn btn-primary" href="<?php echo root;?>ingredients">Agregar</a>';
        } 
    }

    public function ingredientsAddDisplay() {
        $conn = DatabaseConnection::dbConnection();
        $result = $conn -> query("SELECT i.name as `ingredient`, il.ingredientid as `id` FROM inglook as il join ingredients as i on il.ingredientid = i.id;");

        if($result -> num_rows > 0){
            $html = "<div class='col-auto'>";
            $html .= "<ul class='custom-list'>";
            while($row = $result -> fetch_assoc()) {
                $html .= "<li>";
                $html .= "<a href='" . root . "delete?customid=" . $row['id'] . "&uri=" . $this -> page . "' " . "title='Eliminar' class='click-del-link'>";
                $html .= ucfirst($row["ingredient"]);
                $html .= "</a>";
                $html .= "</li>";
                //Ingredients are added into an array                
                $ingArray[] = $row["ingredient"];
            }
            $html .= "</ul>";
            $html .= "</div>";     
            echo $html;           
        } else {
            echo "<p class='text-center'>Agregue los ingredientes para conseguir recetas...</p>";
        }
        return $ingArray;
    }

    private function recipesList() {
        //Connection to the database
        $conn = DatabaseConnection::dbConnection();
        //Array containing the chosen ingredients
        $ingArray = $this -> ingredientsAddDisplay();

        //Array containing the chosen recipes        
        if(isset($ingArray)){   
            //Getting the recipes
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
        }
        return $recipes;
    }

    public function recipesDisplay() {
        //Getting the recipes
        $recipes = $this -> recipesList();
        //If there are no recipes
        if(count($recipes) > 0){
            $html = "<h3 class='text-center'>Recetas</h3>";
            $html .= "<div class='col-auto'>";
            $html .= "<ul class='custom-list' id='recipe-table'>";
            for($i = 0; $i < count($recipes); $i++) {
                //Getting an array of the recipe data
                $recipeData= new RecipesData($recipes[$i]);
                $recipeData = $recipeData -> getRecipeData();

                //Displaying the recipe name
                $html .= "<li>";
                $html .= "<a href='". $recipeData["url"] . "'>" . ucfirst($recipeData["name"]) . "</a>";
                $html .= "</li>";
            }
            $html .= "</ul>";
            $html .= "</div>";     
            echo $html;
        } else {
            echo "<p class='text-center'>No hay recetas con estos ingredientes...</p>";
        }
    }
}



?>