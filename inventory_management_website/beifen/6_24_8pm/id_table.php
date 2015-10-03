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
//realname VARCHAR(30) NOT NULL,
function create_id_table($conn){
    $sql = "CREATE TABLE id_table (
id INT(6) UNSIGNED AUTO_INCREMENT,
realname VARCHAR(30) NOT NULL,
user VARCHAR(30) NOT NULL,
pass VARCHAR(30) NOT NULL,
Admin VARCHAR(30),
PRIMARY KEY(id, user, pass)
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
function check_duplicate($conn,$realname,$user,$pass){
    $sql ="SELECT * FROM id_table WHERE realname='$realname' AND user='$user' AND pass ='$pass'";
    $result= $conn -> query($sql);
    if( $result->num_rows==1)
        return true;
    else
        return false;
}
function insert_data($conn,$realname,$user,$pass){
    if( check_duplicate($conn,$realname,$user,$pass)==true )
        echo "duplicate: ".$user." ".$pass;
    else{
        $sql = "INSERT INTO id_table (realname, user, pass) VALUES ('$realname','$user','$pass')";
        if ($conn->query($sql) === TRUE) {
            $return_pair = '{"logs":[{"realname":"'.$realname.'", "user":"'.$user.'","pass":"'.$pass.'"}]}';
            //$return_pair = "[{\"".$user."\"".":"."\"".$pass."\"}]";
            echo $return_pair;
        }
        else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    }
}
function add($conn){
    //echo "you got add";
    $realname =$_GET["real_name"];
    $user= $_GET["user"];
    $pass= $_GET["pass"];
    insert_data($conn,$realname,$user,$pass);
}
function read($conn){
    $sql = "SELECT id, realname, user, pass FROM id_table ORDER BY user DESC";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        $read_from_table ='{"logs":[';// = array();
        while($row = $result->fetch_assoc()) {
            //$read_from_table .="{\"".$row["user"]."\":\"".$row["pass"]."\"},";
            $read_from_table .= '{"realname":"'.$row["realname"].'","user":"'.$row["user"].'","pass":"'.$row["pass"].'"},';
        }
        $read_from_table= rtrim($read_from_table,",");
        $read_from_table .="]}";
        echo $read_from_table;
    } 
    else {
        echo "0 results";
    }
}
function delete($conn){
    $realname= $_GET["real_name"];
    $user= $_GET["user"];
    $pass= $_GET["pass"];
    $sql ="SELECT Admin FROM id_table WHERE realname= '$realname' AND user='$user' AND pass ='$pass'";
    $result = $conn ->query($sql);
    $row = $result->fetch_assoc();
    if( strcmp($row["Admin"],"yes") ==0 ){
        echo "admin";
    }
    else{
        $sql = "DELETE FROM id_table WHERE realname= '$realname' AND user='$user' AND pass='$pass'";
        if ($conn->query($sql) === TRUE) {
            echo "delete";
        }else {
            echo "Error deleting record: " . $conn->error;
        }
    }
}
function update($conn){
    $realname= $_GET["real_name"];
    $user= $_GET["user"];
    $pass= $_GET["pass"];
    if( check_duplicate($conn,$realname,$user,$pass)===true )
        echo "duplicate";
    else{
        $original_password = $_GET["original_password"];
        $original_username = $_GET["original_username"];
        $sql = "UPDATE id_table SET realname='$realname', user='$user', pass='$pass' WHERE user='$original_username' AND pass='$original_password'";
        if( $conn->query($sql) ===TRUE)
            echo "update";
        else
            echo "fail_update";
    }
}
function setAdmin($conn,$realname,$user,$pass,$Admin){
    if( check_duplicate($conn,$realname,$user,$pass)==true )
        echo "duplicate";
    else{
        $sql = "INSERT INTO id_table (realname, user, pass,Admin) VALUES ('$realname','$user','$pass','$Admin')";
        //$sql = "INSERT INTO id_table (realname, user, pass,Admin) VALUES ('$realname',$user','$pass','yes')";
        //$sql = "INSERT INTO id_table (user, pass,Admin) VALUES ('$user','$pass','yes')";
        if ($conn->query($sql) === TRUE) {
            $return_pair = '{"logs":[{"real":"'.$realname.'", "user":"'.$user.'","pass":"'.$pass.'"}]}';
            //$return_pair = "[{\"".$user."\"".":"."\"".$pass."\"}]";
            echo $return_pair;
        }
        else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    }
}
function client_login($conn,$user,$pass){
    //$sql ="SELECT * FROM id_table WHERE user='$user' AND pass ='$pass' And Admin=NULL";
    $sql ="SELECT * FROM id_table WHERE( user='$user' AND pass ='$pass' AND Admin IS NULL)";
    $result= $conn -> query($sql);
    if( $result->num_rows==1){
        if (!isset($_SESSION['CREATED'])) {
            session_start();
            session_id();
            $row = $result->fetch_assoc();
            $_SESSION['CREATED'] = time();
            $_SESSION["id"] = "client";
            $_SESSION["realname"] = $row["realname"];
            $_SESSION["username"] = $user;
            $_SESSION["password"] = $pass;
        } else if (time() - $_SESSION['CREATED'] > 1800) {
            // session started more than 30 minutes ago
            session_regenerate_id(true);    
            // change session ID for the current session and invalidate old session ID
            $_SESSION['CREATED'] = time();  // update creation time
        }
        echo "clientLogin";
    }
    else
        echo "0result";
}
function admin_login($conn,$user,$pass){
    $sql ="SELECT * FROM id_table WHERE user='$user' AND pass ='$pass' AND Admin ='yes'";
    $result= $conn -> query($sql);
    if( $result->num_rows==1){
        if (!isset($_SESSION['CREATED'])) {
            session_start();
            session_id();
            $row = $result->fetch_assoc();
            $_SESSION['CREATED'] = time();
            $_SESSION["id"] = "admin";
            $_SESSION["realname"] = $row["realname"];
            $_SESSION["username"] = $user;
            $_SESSION["password"] = $pass;
        } else if (time() - $_SESSION['CREATED'] > 1800) {
            // session started more than 30 minutes ago
            session_regenerate_id(true);    
            // change session ID for the current session and invalidate old session ID
            $_SESSION['CREATED'] = time();  // update creation time
        } 
        echo "adminLogin";
    }
    else
        echo "0result";
    
    /*
    $check = check_duplicate($conn,$user,$pass);
    if($check)
        echo "login";
    else
        echo "0result";
    */
}
$servername = "ruochenwebtestcom.ipagemysql.com";
$username = "usc666666";
$password = "usc666666";
$dbname = "inventory_management";
$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
//create_id_table($conn);
//drop_table($conn,$dbname);
//setAdmin($conn,"realname","admin","admin","yes");
if( strcmp( $_GET["purpose"],"add") ==0 ){
    add($conn);
}
if( strcmp($_GET["purpose"],"read") ==0 )
    read($conn);
if( strcmp($_GET["purpose"],"delete")==0 )
    delete($conn);
if( strcmp($_GET["purpose"],"update")==0 )
    update($conn);
if( strcmp($_POST["purpose"],"administrator")==0 )
    admin_login($conn,$_POST["user"],$_POST["pass"]);
if(strcmp($_POST["purpose"],"client")==0 )
    client_login($conn,$_POST["user"],$_POST["pass"]);
$conn->close();
?>