<?php
    session_start();
?>
<!DOCTYPE html>
<html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1">
<!-- bootstrap -->
<link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css">
<script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/js/bootstrap.min.js"></script>    
<!-- jquery -->
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
<!-- local reference to pagination -->
<script src="pagination/src/bootstrap-paginator.js"></script>
<script src="pagination/lib/qunit-1.11.0.js"></script>   
<meta charset="UTF-8">
<title>Input Log</title>
<!--css link-->
<!--<link rel="stylesheet" type="text/css" href="table_style1.css" />-->
</head>

<body>
<div id="log_wrapper">
    <div id="log_show">
        <table id ="log_table">
            <tr><td><h3>Employee</h3></td><td><h3>Product</h3></td><td><h3>Brand</h3></td><td><h3>Quantity</h3></td><td><h3>Price</h3></td><td><h3>Date</h3></td><td><h3>Edit</h3></td><td><h3>Delete</h3></td><td></td></tr>
            <tr><td><h3>Add new logs below:</h3></td><td><h3>Product</h3></td><td><h3>Brand</h3></td><td><h3>Quantity</h3></td><td><h3>Price</h3></td><td></td><td><button id="update">confirm</button></td><td><button id="confirm_delete">confirm</button></td></tr>
            <tr><td><div type ="text" name ="employee"/></td><td><input type ="text" name ="product"></td><td><input type ="text" name ="brand"></td><td><input type ="text" name ="quantity"></td><td><input type ="text" name ="price"></td><td></td><td></td></tr>
            <tr><td><div type ="text" name ="employee"/></td><td><input type ="text" name ="product"></td><td><input type ="text" name ="brand"></td><td><input type ="text" name ="quantity"></td><td><input type ="text" name ="price"></td><td></td><td></td></tr>
            <tr><td><div type ="text" name ="employee"/></td><td><input type ="text" name ="product"></td><td><input type ="text" name ="brand"></td><td><input type ="text" name ="quantity"></td><td><input type ="text" name ="price"></td><td></td><td></td></tr>
            <tr><td><div type ="text" name ="employee"/></td><td><input type ="text" name ="product"></td><td><input type ="text" name ="brand"></td><td><input type ="text" name ="quantity"></td><td><input type ="text" name ="price"></td><td></td><td></td></tr>
            <tr><td></td><td></td><td></td><td></td><td><button id="button_user">add one record</button></td><td></td><td></td></tr>
        </table>
<div>
    <ul id='bp-3-element-test'></ul>
</div>     
    <div id ="debug"></div>
    </div>
</div>
    
   
<script>
var element = $('#bp-3-element-test');
var options = {
				alignment:"center",
                bootstrapMajorVersion:3,
                currentPage: 1,
                numberOfPages: 5,
                totalPages:11
    }
            element.bootstrapPaginator(options);        
function verification(){
            var validate_sign = <?php
                $id = session_id();
                if( isset($id) && ($_SESSION["id"]==="admin" || $_SESSION["id"]==="client")){             
                    echo 1;
                }
                else{
                    echo 0;
                }
            ?>;
            alert(validate_sign);
            if(validate_sign ==1 ){
                alert("approved");
                $("#log_table").ready(function(){
                    ajax_trigger("read", "", "", "", "", "");
                });
                //document.getElementById("id_table").style.visibility = "visible";
            }
            else if(validate_sign ==0 ){
                alert("close the window");
                document.body.innerHTML("");
                window.close();
            }
        }    
        $(document).ready(function(){
                verification();
                $("#button_user").click(function(){
                    validate();
                });
                $("input[name=product], input[name=brand], input[name =quantity],input[name=price]").keypress(function(e){
                    if(e.keyCode==13)
                        validate();
                });
                $("#update").click(function(){
                    update_users();
                });
                $("#confirm_delete").click(function(){
                    delete_users();
                })            
            
        });
function validate(){
    //var employee = document.getElementsByName("employee");
    var product = document.getElementsByName("product");
    var brand = document.getElementsByName("brand");
    var quantity = document.getElementsByName("quantity");
    var price = document.getElementsByName("price");
    var i, sign_blank=1, sign_number=0, sign_below_0=0;
    for(i =0; i<4;i++){
        if( product[i].value != ""&& brand[i].value != ""&& quantity[i].value != ""&& price[i].value != ""){
            sign_blank = 0;
            var quantity_n = Number(quantity[i].value);
            var price_n = Number(price[i].value);
            if( !isNaN(quantity_n) && !isNaN(price_n) ){
                if( quantity_n>0 && price_n>=0 ){
                    ajax_trigger("add", product[i].value, brand[i].value, quantity[i].value, price[i].value);
                    product[i].value ="";brand[i].value ="";quantity[i].value="";price[i].value="";
                }
                else
                    sign_below_0 =1;
            }
            else
                sign_number =1;
        }
    }
    //alert("sign_blank is: "+ sign_blank);
    if(sign_blank == 1 )
        alert("Input can not be blank!");
    if(sign_number ==1)
        alert("Input should be number!");
    if(sign_below_0 ==1)
        alert("Input number shouldn't be negative!");
}
function delete_user(s){
    //var employee = s.parentNode.parentNode.childNodes[0].childNodes[0].nodeValue;
    var product = s.parentNode.parentNode.childNodes[1].childNodes[0].nodeValue;
    var brand = s.parentNode.parentNode.childNodes[2].childNodes[0].nodeValue;
    var quantity = s.parentNode.parentNode.childNodes[3].childNodes[0].nodeValue;
    var price = s.parentNode.parentNode.childNodes[4].childNodes[0].nodeValue;
    var key = s.parentNode.parentNode.childNodes[8].childNodes[0].nodeValue;
    alert(s.parentNode.parentNode.childNodes[8].childNodes[0].tagName);
    //alert("key is :"+key);
    ajax_trigger("delete",product, brand, quantity, price,key);
    var i = s.parentNode.parentNode.rowIndex;
    document.getElementById("log_table").deleteRow(i);
}
function delete_users(){
    var check_num = document.getElementsByName("delete").length;
    var box = document.getElementsByName("delete");
    var i = 0;
    for(i =0; i<check_num;i++){
        if (box[i].checked == true){
            delete_user(box[i]);
            i--;
        }
    }
}
//update button
function check(b){
    if (b.checked){
        b.parentNode.parentNode.childNodes[0].setAttribute("contentEditable",true);
        b.parentNode.parentNode.childNodes[1].setAttribute("contentEditable",true);
        b.parentNode.parentNode.childNodes[2].setAttribute("contentEditable",true);
        b.parentNode.parentNode.childNodes[3].setAttribute("contentEditable",true);
        b.parentNode.parentNode.childNodes[4].setAttribute("contentEditable",true);
    }else{
        b.parentNode.parentNode.childNodes[0].setAttribute("contentEditable",false);
        b.parentNode.parentNode.childNodes[1].setAttribute("contentEditable",false);
        b.parentNode.parentNode.childNodes[2].setAttribute("contentEditable",false);
        b.parentNode.parentNode.childNodes[3].setAttribute("contentEditable",false);
        b.parentNode.parentNode.childNodes[4].setAttribute("contentEditable",false);
    }
}    
function update_user(s){
    var key = s.parentNode.parentNode.childNodes[8].childNodes[0].nodeValue;
    alert("key is:"+key);
    var employee = s.parentNode.parentNode.childNodes[0].textContent;
    var product = s.parentNode.parentNode.childNodes[1].textContent;
    var brand = s.parentNode.parentNode.childNodes[2].textContent;
    var quantity = s.parentNode.parentNode.childNodes[3].textContent;
    var price = s.parentNode.parentNode.childNodes[4].textContent;
    //ajax_trigger("update", key, employee, product, brand, quantity, price, "", "", "", "", "");
    ajax_trigger("update",employee,product,brand,quantity,price,key);
    //ajax_trigger(sign, product, brand, quantity, price,key)
}    
function update_users(){
    var check_num = document.getElementsByName("update").length;
    var box = document.getElementsByName("update");
    var i = 0;   
    for(i =0; i<check_num;i++){
        if (box[i].checked == true){
            update_user(box[i]);
            //alert(i);
            box[i].checked = false;
            check(box[i]);
        }
    }
}    
function select_edit_delete(s){
    var selected = s.value;
    if(selected==="delete")
        delete_user(s.childNodes[1]);
    if(selected==="update")
        alert("update");
        //update_user(s.childNodes[2]);
}
function pagination(obj){
    
}    
    
function show_form(output){
    console.log(output);
    if(output ==="duplicate"){
        alert("error: duplicate user name and password!");
        //location.reload();
        return;
    }
    if(output ==="update"){
        alert("update successfully");
        return;
    }
    if(output ==="delete" ){
        alert("user has been deleted!");
        return;
    }
    if(output ==="fail_update"){
        alert("failed to update!");
        return;
    }
    var obj = JSON.parse(output);
    //var keys = Object.keys(obj.logs);
    var row; var cell1; var cell2; var cell3; var cell4; var cell5; var cell6; var cell7;
    var i=0;
    pagination(obj);
    //for(var i =0; i<keys.length; i++){
    for(var i =0; i<obj.logs.length; i++){
        row = document.getElementById("log_table").insertRow(1);
        cell1 = row.insertCell(0);
        cell2 = row.insertCell(1);
        cell3 = row.insertCell(2);
        cell4 = row.insertCell(3);
        cell5 = row.insertCell(4);
        cell6 = row.insertCell(5);
        cell7 = row.insertCell(6);
        cell8 = row.insertCell(7);
        cell9 = row.insertCell(8);
        cell1.innerHTML= obj.logs[i].employee;
        cell2.innerHTML= obj.logs[i].product;
        cell3.innerHTML= obj.logs[i].brand;
        cell4.innerHTML= obj.logs[i].quantity;
        cell5.innerHTML= obj.logs[i].price;
        cell6.innerHTML= obj.logs[i].AddDate;
        //cell7.innerHTML='<select onchange ="select_edit_delete(this)"><option>edit</option><option>delete</option><option>update</option>';
        cell7.innerHTML='<input type="checkbox" name="update"  onchange="check(this)"/>';
        cell8.innerHTML = '<input type="checkbox" name="delete"/>';
        //cell9.innerHTML= "<span >"+obj.logs[i].key+"</span>";  // obj.logs[i].key;//style ='display:none'
        cell9.innerHTML= obj.logs[i].key;
    }
}
function ajax_trigger(sign, employee, product, brand, quantity, price,key){
    //$("#debug").text(password);
    //alert(password);
    $.ajax({
        url: "input_table.php",
        data: {
            purpose: sign,
            employee:employee,
            product: product,
            brand: brand,
            quantity: quantity,
            price: price,
            key: key
        },
        type:'GET',
        success:function(output){
            $("#debug").html(output);
            show_form(output);
            //display_f(output);
            // show_active(page_x);
            },
        error:function(){
                throw new Error("Something went badly wrong!");
                alert("error in ajax");
            }
    }); 
}
</script>    

</body>
    
</html>