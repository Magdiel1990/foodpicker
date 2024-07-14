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

//Verify the inputs
class InputValidation {

    public $inputs;
    public $pattern;

    function __construct($inputs, $pattern){
        $this -> inputs = $inputs;
        $this -> pattern = $pattern;
    }
//Validate if it comes null
    public function nullValidation() {
        $message = [];

        foreach ($this -> inputs as $x => $val) {
//If the input is a string
            if(!is_array($val[0])) {
                if($val[0] == null) {
                    $message [] = "¡Complete todos los campos requeridos!";
                    $message [] = "danger";
                    break;
                }
//If the input is an array
            } else {
                if(count($val[0]) == 0) {
                    $message [] = "¡Complete todos los campos requeridos!";
                    $message [] = "danger";
                    break;
                }
            }
        }

        return $message;
    }
//Regular expression
    public function regExpValidation() {
        $message = $this -> nullValidation();

        if(count($message) == 0) {
            foreach ($this -> inputs as $key => $value) {
//Apply regular expression only if it is not numeric, an array, the second element of the array has the max and min, or the fourth element of the array is true                
                if(count($value[1]) != 0 && !is_numeric($value[0]) && !is_array($value[0]) && $value[3] === true) {
                    if (!preg_match($this -> pattern, $value[0])){
                        $message [] = "¡" . $key . " " . $value[2] . "!";
                        $message [] = "danger"; 
                        break;
                    }
                }
            }          
        }
        return $message;   
    }
//Validate the length    
    public function lengthValidation() {
        $message = $this -> regExpValidation();

        if(count($message) == 0) {
            foreach ($this -> inputs as $key => $value) {
//If it is a string
                if(count($value[1]) != 0 && !is_numeric($value[0]) && !is_array($value[0])) {
                    if(strlen($value[0]) < $val[1][0] || strlen($value[0]) > $value[1][1]) {
                        $message [] = "¡" . $key . " debe tener entre " . $value[1][0] ." y " . $value[1][1] ." caracteres!";
                        $message [] = "danger"; 
                        break;
                    }
//If it is a number
                } else if (is_numeric($value[0])){
                    if($value[0] > $value[1][1] || $value[0] < $val[1][0]) {
                        $message [] = "¡" . $key . " debe tener un valor entre " . $value[1][0] ." y " . $value[1][1] . "!";
                        $message [] = "danger"; 
                        break;
                    }
                }
            }         
        }         
        return $message;   
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
?>