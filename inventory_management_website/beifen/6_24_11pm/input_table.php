<?php
function create_input_table($conn){
    $sql = "CREATE TABLE input_table (
id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
employee VARCHAR(30) NOT NULL,
product VARCHAR(30) NOT NULL,
brand VARCHAR(30) NOT NULL,
quantity INT(30) NOT NULL,
price DOUBLE(30,2),
AddDate TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
Admin VARCHAR(30) NOT NULL
)";
    if ($conn->query($sql) === TRUE) {
        echo "Table input_table created successfully";
    } else {
        echo "Error creating table: " . $conn->error;
    }    
}
function drop_input_table($conn,$dbname){
    $sql ="DROP TABLE input_table";
    if($conn->query($sql) ===true )
        echo "delete successfully";
    else
        echo "failed to delete the table";
}
function insert_data($conn, $employee, $product, $brand, $quantity, $price){
        if( strcmp( $_SESSION['id'],"admin") ==0 )
            $Admin = "admin";
        else if( strcmp( $_SESSION['id'],"client") ==0 )
            $Admin = "client".$_SESSION["realname"].$_SESSION["username"];
        else{
            echo "insert: your identity is not found";
            exit(0);
        }    
        $sql = "INSERT INTO input_table (employee, product, brand, quantity, price,Admin) VALUES ('$employee','$product','$brand','$quantity','$price','$Admin')";
        if ($conn->query($sql) === TRUE) {
            $return_pair = '{"logs":[{"employee":"'.$employee.'", "product":"'.$product.'", "brand":"'.$brand.'", "quantity":"'.$quantity.'", "price":"'.$price.'","key":"'.$conn->insert_id.'"'; 
            $sql = "SELECT AddDate FROM input_table WHERE id='$conn->insert_id'";            
            $result = $conn->query($sql);
            $row = $result->fetch_assoc();
            $row["AddDate"] = toTimeZone($row["AddDate"], $from_tz = 'America/New_York', $to_tz = 'America/Los_Angeles', $fm = 'Y-m-d H:i:s');
            //echo $row["AddDate"];
            $return_pair .=",\"AddDate\":\"".$row["AddDate"]."\"}]}";
            echo $return_pair;
        }
        else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
}
function check_duplicate($conn, $employee, $product, $brand, $quantity, $price){
    $sql ="SELECT * FROM input_table WHERE employee='$employee' AND product ='$product'AND brand ='$brand'AND quantity ='$quantity'AND price ='$price'";
    $result= $conn -> query($sql);
    if( $result->num_rows==1)
        return true;
    else
        return false;
}
function add($conn){
    $employee= $_GET["employee"];
    $product= $_GET["product"];
    $brand= $_GET["brand"];
    $quantity= $_GET["quantity"];
    $price= $_GET["price"]; 
    insert_data($conn, $_SESSION["realname"], $product, $brand, $quantity, $price);
}
function toTimeZone($src, $from_tz = 'America/New_York', $to_tz = 'America/Los_Angeles', $fm = 'Y-m-d H:i:s') {
    $datetime = new DateTime($src, new DateTimeZone($from_tz));
    $datetime->setTimezone(new DateTimeZone($to_tz));
    return $datetime->format($fm);
}
function read($conn){
    $sql = "SELECT id, employee, product, brand, quantity, price, AddDate FROM input_table ORDER BY AddDate DESC";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        $read_from_table ='{"logs":[';
        while($row = $result->fetch_assoc()) {
            $row["AddDate"] = toTimeZone($row["AddDate"], $from_tz = 'America/New_York', $to_tz = 'America/Los_Angeles', $fm = 'Y-m-d H:i:s');
            $read_from_table .='{"employee":"'.$row["employee"].'", "product":"'.$row["product"].'", "brand":"'.$row["brand"].'", "quantity":"'.$row["quantity"].'", "price":"'.$row["price"].'","AddDate":"'.$row["AddDate"].'","key":"'.$row["id"].'"},';
        }
        $read_from_table= rtrim($read_from_table,",");
        $read_from_table .="]}";
        echo $read_from_table;
    }
    else {
        echo "0 results";
    }
}
function update($conn){
    //$employee = $_SESSION["realname"];
    $employee= $_GET["employee"];
    $product= $_GET["product"];
    $brand= $_GET["brand"];
    $quantity= $_GET["quantity"];
    $price= $_GET["price"];
    $key= (int)$_GET["key"];
    if( strcmp($_SESSION["id"],"admin" ) ==0 ){
        $sql = "UPDATE input_table SET employee='$employee', product='$product', brand='$brand', quantity='$quantity', price='$price' WHERE id ='$key'";
    }
    else if ( strcmp($_SESSION["id"],"client")==0  ){
        $Admin = "client".$_SESSION["realname"].$_SESSION["username"];
        $sql = "UPDATE input_table SET product='$product', brand='$brand', quantity='$quantity', price='$price' WHERE id ='$key' AND Admin='$Admin'";
    }
    else{
        echo "update: your identity is not found";
        exit(0);
    }
    //$sql = "UPDATE input_table SET employee='$employee', product='$product', brand='$brand', quantity='$quantity', price='$price' WHERE id='$key'";
        if( $conn->query($sql) ===TRUE)
            //echo $sql;
            echo "update";
        else
            echo "fail_update1";
}
function delete($conn){
    //$employee= $_GET["employee"];
    $employee = $_SESSION["realname"];
    $product= $_GET["product"];
    $brand= $_GET["brand"];
    $quantity= (int)$_GET["quantity"];
    $price= $_GET["price"];
    $key =(int)$_GET["key"];
    if( strcmp($_SESSION["id"],"admin" ) ==0 ){
        $sql = "DELETE FROM input_table WHERE product='$product' AND brand='$brand' AND quantity='$quantity' AND price='$price' AND id ='$key'";
        echo "admin: ".$sql;
    }
    else if ( strcmp($_SESSION["id"],"client")==0  ){
        $Admin = "client".$_SESSION["realname"].$_SESSION["username"];
        $sql = "DELETE FROM input_table WHERE employee='$employee' AND product='$product' AND brand='$brand' AND quantity='$quantity' AND price='$price' AND id ='$key' AND Admin='$Admin'"; 
        echo "client: ".$sql;
    }
    else{
        echo "delete: your identity is not found";
        exit(0);
    }
    if ($conn->query($sql) === TRUE) {
        echo "delete";
    } else {
        echo "Error deleting record: " . $conn->error;
    }
}

session_start();
$servername = "ruochenwebtestcom.ipagemysql.com";
$username = "usc666666";
$password = "usc666666";
$dbname = "inventory_management";
//create connection
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
//create_input_table($conn);
//drop_input_table($conn,$dbname);

if( strcmp( $_GET["purpose"],"add") ==0 )
    add($conn);
if( strcmp($_GET["purpose"],"read") ==0 )
    read($conn);
if( strcmp($_GET["purpose"],"delete")==0 )
    delete($conn);
if( strcmp($_GET["purpose"],"update")==0 )
    update($conn);
$conn->close();
?>