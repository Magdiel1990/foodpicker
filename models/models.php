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

    function __construct($id, $table){
        $this -> id = $id;
        $this -> table = $table;
    }
    //Get the name
    private function getName() {
        $conn = DatabaseConnection::dbConnection();
        $sql = "SELECT name FROM " . $this -> table . " WHERE id = '" . $this -> id ."';";
   
        return $conn -> query($sql);
    }

    private function getRow() {
        return $this -> getName() -> fetch_assoc();
    }

    public function name() {
        return $this -> getRow()['name'];
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
?>