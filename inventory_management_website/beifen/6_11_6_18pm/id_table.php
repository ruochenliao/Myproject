<?php
//create id table

function create_id_table($conn){
    $sql = "CREATE TABLE id_table (
id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY, 
user VARCHAR(30) NOT NULL,
pass VARCHAR(30) NOT NULL,
reg_date TIMESTAMP
)";
    if ($conn->query($sql) === TRUE) {
        echo "Table MyGuests created successfully";
    } else {
        echo "Error creating table: " . $conn->error;
    }    
}

function insert_data($conn,$user,$pass){
    $sql = "INSERT INTO id_table (user, pass) VALUES ('$user','$pass')";
    if ($conn->query($sql) === TRUE) {
        $return_pair = array( "strange" => "strange");
        echo json_enode($return_pair);
        //read($conn);
    } 
    else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

function add($conn){
    $user= $_GET["user"];
    $pass= $_GET["pass"];
    insert_data($conn,$user,$pass);
}
function read($conn){
    $sql = "SELECT user, pass FROM id_table";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        //$read_from_table ="";
        $read_from_table = array();
        while($row = $result->fetch_assoc()) {
            $read_from_table[ $row["user"] ] = $row["pass"];
        }
        echo json_encode($read_from_table);
    } 
    else {
        echo "0 results";
    }
}
function php_debug($data){
    if(is_array($data) || is_object($data))
	{
		echo("<script>console.log('PHP: ".json_encode($data)."');</script>");
	} else {
		echo("<script>console.log('PHP: ".$data."');</script>");
	}
}

$servername = "ruochenwebtestcom.ipagemysql.com";
$username = "usc666666";
$password = "usc666666";
$dbname = "inventory_management";
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} 
if( strcmp( $_GET["purpose"],"add") ==0 ){
    add($conn);
}
if( strcmp($_GET["purpose"],"read") ==0 )
    read($conn);
$conn->close();
?>