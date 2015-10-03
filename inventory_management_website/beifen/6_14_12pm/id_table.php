<?php
//create id table
function php_debug($data){
    if(is_array($data) || is_object($data))
	{
		echo("<script>console.log('PHP: ".json_encode($data)."');</script>");
	} else {
		echo("<script>console.log('PHP: ".$data."');</script>");
	}
}

function create_id_table($conn){
    $sql = "CREATE TABLE id_table (
id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
user VARCHAR(30) NOT NULL,
pass VARCHAR(30) NOT NULL
)";
    if ($conn->query($sql) === TRUE) {
        echo "Table MyGuests created successfully";
    } else {
        echo "Error creating table: " . $conn->error;
    }    
}
function drop_table($conn,$dbname){
    $sql ="DROP TABLE id_table";
    if($conn->query($sql) ===true )
        echo "delete successfully";
    else
        echo "failed to delete the table";
}
function check_duplicate($conn,$user,$pass){
    $sql ="SELECT * FROM id_table WHERE user='$user' AND pass ='$pass'";
    $result= $conn -> query($sql);
    if( $result->num_rows==1)
        return true;
    else
        return false;
}
function insert_data($conn,$user,$pass){
    if( check_duplicate($conn,$user,$pass)==true )
        echo "duplicate: ".$user." ".$pass;
    else{
        $sql = "INSERT INTO id_table (user, pass) VALUES ('$user','$pass')";
        if ($conn->query($sql) === TRUE) {
            $return_pair = array( $user => $pass);
            echo json_encode($return_pair);
        }
        else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    }
    
}

function add($conn){
    $user= $_GET["user"];
    $pass= $_GET["pass"];
    insert_data($conn,$user,$pass);
}
function read($conn){
    $sql = "SELECT user, pass FROM id_table ORDER BY user DESC";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        $read_from_table = array();
        $debug = "";
        while($row = $result->fetch_assoc()) {
            $read_from_table[ $row["user"] ] = $row["pass"];
            echo "id: " . $row["id"]. " - Name: " . $row["user"]. " " . $row["pass"]. "<br>";
        }
        echo json_encode($read_from_table);
    } 
    else {
        echo "0 results";
    }
}
function delete($conn){
    $user= $_GET["user"];
    $pass= $_GET["pass"];
    $sql = "DELETE FROM id_table WHERE user='$user' AND pass='$pass'";
    if ($conn->query($sql) === TRUE) {
        echo "delete";
    } else {
        echo "Error deleting record: " . $conn->error;
    }
}
function update($conn){
    $user= $_GET["user"];
    $pass= $_GET["pass"];
    if( check_duplicate($conn,$user,$pass)===true )
        echo "duplicate";
    else{
        $original_password = $_GET["original_password"];
        $original_username = $_GET["original_username"];
        //$sql = "UPDATE id_table SET user='$user', pass='$pass' WHERE user='$original_username' AND pass='$original_password'";
        $sql = "DELETE FROM id_table WHERE user='$original_password' AND pass='$original_username'";
        $v1 = $conn ->query($sql);
        $sql = "INSERT INTO id_table (user, pass) VALUES ('$user','$pass')";
        $v2 = $conn ->query($sql);
        if($v1 && $v2){
            echo "update";
        }
        else
            echo "duplicate".$conn->error;
    }
}
$servername = "ruochenwebtestcom.ipagemysql.com";
$username = "usc666666";
$password = "usc666666";
$dbname = "inventory_management";
$conn = new mysqli($servername, $username, $password, $dbname);
//create_id_table($conn);
//drop_table($conn,$dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
/*
if( strcmp( $_GET["purpose"],"add") ==0 ){
    add($conn);
}
*/

if( strcmp($_GET["purpose"],"read") ==0 )
    read($conn);
/*
if( strcmp($_GET["purpose"],"delete")==0 )
    delete($conn);
if( strcmp($_GET["purpose"],"update")==0 )
    update($conn);
*/
$conn->close();
?>