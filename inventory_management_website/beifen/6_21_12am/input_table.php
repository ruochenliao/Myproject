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
Admin VARCHAR(30)
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
//    if( check_duplicate($conn, $employee, $product, $brand, $quantity, $price)==true )
//        echo "duplicate: ".$employee." ".$product." ".$brand." ".$quantity." ".$price;
//    else{
        $Admin = $_SESSION['id'].";".$_SESSION['username'];
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
    //}
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
    
    insert_data($conn, $employee, $product, $brand, $quantity, $price);
}
function toTimeZone($src, $from_tz = 'America/New_York', $to_tz = 'America/Los_Angeles', $fm = 'Y-m-d H:i:s') {
    $datetime = new DateTime($src, new DateTimeZone($from_tz));
    $datetime->setTimezone(new DateTimeZone($to_tz));
    return $datetime->format($fm);
}
function read($conn){
    $sql = "SELECT id, employee, product, brand, quantity, price, AddDate FROM input_table ORDER BY AddDate ASC";
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