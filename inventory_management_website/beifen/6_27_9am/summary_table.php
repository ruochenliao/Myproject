<?php
session_start();
$servername = "ruochenwebtestcom.ipagemysql.com";
$username = "webtest";
$password = "webtest";
$dbname = "webtest_database";
$conn = new mysqli($servername, $username, $password, $dbname);
//check connextion
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
//echo "Connected successfully";
//sql to create table
function create_summary_table($conn){
    $sql = "CREATE TABLE summary_table (
id INT(30) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
product VARCHAR(30) NOT NULL,
brand VARCHAR(30) NOT NULL,
quantity INT(30) NOT NULL,
price DOUBLE(30,2),
AddDate TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
Admin VARCHAR(30)
)";
    if ($conn->query($sql) === TRUE) {
        echo "Table summary_table created successfully";
    } else {
        echo "Error creating table: " . $conn->error;
    }    
}
function drop_summary_table($conn,$dbname){
    $sql ="DROP TABLE summary_table";
    if($conn->query($sql) ===true )
        echo "delete successfully";
    else
        echo "failed to delete the table";
}

//create_summary_table($conn);
//drop_summary_table($conn,$dbname);

function insert_data($conn, $employee, $product, $brand, $quantity, $price){
        $employee =$_SESSION['realname'];
        if( strcmp( $_SESSION['id'],"admin") ==0 )
            $Admin = "admin";
        else if( strcmp( $_SESSION['id'],"client") ==0 )
            $Admin = "client".$_SESSION["realname"].$_SESSION["username"];
        else{
            echo "insert: your identity is not found";
            exit(0);
        }            
        $sql = "INSERT INTO summary_table (product, brand, quantity, price) VALUES ('$product','$brand','$quantity','$price')";
        $sql_input = "INSERT INTO input_table (employee, product, brand, quantity, price,Admin) VALUES ('$employee','$product','$brand','$quantity','$price','$Admin')";
        //echo $sql_input;
        $conn->query($sql_input);
        if ($conn->query($sql) === TRUE) {
            $return_pair = '{"logs":[{"product":"'.$product.'", "brand":"'.$brand.'", "quantity":"'.$quantity.'", "price":"'.$price.'", "key":"'.$conn->insert_id.'"'; 
            $sql = "SELECT AddDate FROM summary_table WHERE id='$conn->insert_id'";            
            $result = $conn->query($sql);
            $row = $result->fetch_assoc();
            $row["AddDate"] = toTimeZone($row["AddDate"], $from_tz = 'America/New_York', $to_tz = 'America/Los_Angeles', $fm = 'Y-m-d H:i:s');
            //echo $row["AddDate"];
            $return_pair .=",\"AddDate\":\"".$row["AddDate"]."\"";
            $return_pair .=",\"sign\":\"insert\"}]}";
            echo $return_pair;
        }
        else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
        $sql = "INSERT INTO input_table (employee, product, brand, quantity, price) VALUES (\"jiawei\",'$product','$brand','$quantity','$price')";
}
//function check_duplicate($conn, $employee, $product, $brand, $quantity, $price){
//    $sql ="SELECT * FROM summary_table WHERE employee='$employee' AND product ='$product'AND brand ='$brand'AND quantity ='$quantity'AND price ='$price'";
//    $result= $conn -> query($sql);
//    if( $result->num_rows==1)
//        return true;
//    else
//        return false;
//}
function add($conn){
    $employee= $_GET["employee"];
    $product= $_GET["product"];
    $brand= $_GET["brand"];
    $quantity= $_GET["quantity"];
    $price= $_GET["price"];
    
    insert_data($conn, $employee, $product, $brand, $quantity, $price);
}
function toTimeZone($src, $from_tz = 'America/New_York', $to_tz = 'America/Los_Angeles', $fm = 'Y-m-d H:i:s') {
    $datetime = new DateTime($src, new DateTimeZone($from_tz));
    $datetime->setTimezone(new DateTimeZone($to_tz));
    return $datetime->format($fm);
}
function read($conn){
    //$sql = "SELECT id, product, brand, quantity, price, AddDate FROM summary_table ORDER BY AddDate ASC";
    $sql = "SELECT id, product, brand, quantity, price, AddDate FROM summary_table ORDER BY product ASC";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        $read_from_table ='{"logs":[';
        while($row = $result->fetch_assoc()) {
            $row["AddDate"] = toTimeZone($row["AddDate"], $from_tz = 'America/New_York', $to_tz = 'America/Los_Angeles', $fm = 'Y-m-d H:i:s');
            $read_from_table .='{"product":"'.$row["product"].'", "brand":"'.$row["brand"].'", "quantity":"'.$row["quantity"].'", "price":"'.$row["price"].'", "AddDate":"'.$row["AddDate"].'", "key":"'.$row["id"].'"},';
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
    //$employee= $_GET["employee"];
    $product= $_GET["product"];
    $brand= $_GET["brand"];
    $quantity= $_GET["quantity"];
    $price= $_GET["price"];
    $key= $_GET["key"];
    $sql = "DELETE FROM summary_table WHERE id='$key'";
    if ($conn->query($sql) === TRUE) {
        echo "delete";
    } else {
        echo "Error deleting record: " . $conn->error;
    }
}
function update($conn){
    $employee= $_GET["employee"];
    $product= $_GET["product"];
    $brand= $_GET["brand"];
    $quantity= $_GET["quantity"];
    $price= $_GET["price"];
    $original_quantity= $_GET["original_quantity"];
    $key= $_GET["key"];
        
        $sql = "UPDATE summary_table SET quantity='$quantity' WHERE id='$key'";
        //$sql_input = "INSERT INTO input_table (employee, product, brand, quantity, price) VALUES ('$employee','$product','$brand','$original_quantity','$price')";
        if( strcmp( $_SESSION['id'],"admin") ==0 )
            $Admin = "admin";
        else if( strcmp( $_SESSION['id'],"client") ==0 )
            $Admin = "client".$_SESSION["realname"].$_SESSION["username"];
        else{
            echo "insert: your identity is not found";
            exit(0);
        }    
        $sql_input = "INSERT INTO input_table (employee, product, brand, quantity, price,Admin) VALUES ('$employee','$product','$brand','$original_quantity','$price','$Admin')";    
    
    $conn->query($sql_input);
        if( $conn->query($sql) ===TRUE)
            echo "update";
        else
            echo "fail_update";
}
function update_consume($conn){
    $employee= $_GET["employee"];
    $product= $_GET["product"];
    $brand= $_GET["brand"];
    $quantity= $_GET["quantity"];
    $price= $_GET["price"];
    $original_quantity= $_GET["original_quantity"];
    $key= $_GET["key"];
        
        $sql = "UPDATE summary_table SET quantity='$quantity' WHERE id='$key'";
        $sql_consume = "INSERT INTO consume_table (employee, product, brand, quantity, price) VALUES ('$employee','$product','$brand','$original_quantity','$price')";
    $conn->query($sql_consume);
        if( $conn->query($sql) ===TRUE)
            echo "update";
        else
            echo "fail_update";
}
/*
function add_input($conn){
    $employee= $_GET["employee"];
    $product= $_GET["product"];
    $brand= $_GET["brand"];
    $quantity= $_GET["quantity"];
    $price= $_GET["price"];
    echo "add input";
    $sql_input = "INSERT INTO input_table (employee, product, brand, quantity, price) VALUES ('$employee','$product','$brand','$quantity','$price')";
    $conn->query($sql_input);
}
*/
//function add_consume($conn){
//    $employee= $_GET["employee"];
//    $product= $_GET["product"];
//    $brand= $_GET["brand"];
//    $quantity= $_GET["quantity"];
//    $price= $_GET["price"];
//
//    $sql_consume = "INSERT INTO consume_table (employee, product, brand, quantity, price) VALUES ('$employee','$product','$brand','$quantity','$price')";
//    $conn->query($sql_consume);
//}
if( strcmp( $_GET["purpose"],"add") ==0 )
    add($conn);

if( strcmp($_GET["purpose"],"read") ==0 )
    read($conn);

if( strcmp($_GET["purpose"],"delete")==0 )
    delete($conn);

if( strcmp($_GET["purpose"],"update")==0 )
    update($conn);

if( strcmp($_GET["purpose"],"update_consume")==0 )
    update_consume($conn);


$conn->close();
?>