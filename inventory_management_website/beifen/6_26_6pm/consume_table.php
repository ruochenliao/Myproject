<?php
$servername = "ruochenwebtestcom.ipagemysql.com";
$username = "webtest";
$password = "webtest";
$dbname = "webtest_database";
//create connection
$conn = new mysqli($servername, $username, $password, $dbname);
//check connextion
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
//echo "Connected successfully";
//sql to create table
function create_consume_table($conn){
    $sql = "CREATE TABLE consume_table (
id INT(30) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
employee VARCHAR(30) NOT NULL,
product VARCHAR(30) NOT NULL,
brand VARCHAR(30) NOT NULL,
quantity INT(30) NOT NULL,
price DOUBLE(30,2),
AddDate TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
Admin VARCHAR(30) NOT NULL
)";
    if ($conn->query($sql) === TRUE) {
        echo "Table consume_table created successfully";
    } else {
        echo "Error creating table: " . $conn->error;
    }    
}
function drop_consume_table($conn,$dbname){
    $sql ="DROP TABLE consume_table";
    if($conn->query($sql) ===true )
        echo "delete successfully";
    else
        echo "failed to delete the table";
}

//create_consume_table($conn);
//drop_consume_table($conn,$dbname);

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
    $sql = "SELECT id, employee, product, brand, quantity, price, AddDate FROM consume_table ORDER BY AddDate ASC";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        $read_from_table ='{"logs":[';
        while($row = $result->fetch_assoc()) {
            $row["AddDate"] = toTimeZone($row["AddDate"], $from_tz = 'America/New_York', $to_tz = 'America/Los_Angeles', $fm = 'Y-m-d H:i:s');
            $read_from_table .='{"employee":"'.$row["employee"].'", "product":"'.$row["product"].'", "brand":"'.$row["brand"].'", "quantity":"'.$row["quantity"].'", "price":"'.$row["price"].'", "AddDate":"'.$row["AddDate"].'", "key":"'.$row["id"].'"},';
        }
        $read_from_table= rtrim($read_from_table,",");
        $read_from_table .="]}";
        echo $read_from_table;
    } 
    else {
        echo "0 results";
    }
}

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