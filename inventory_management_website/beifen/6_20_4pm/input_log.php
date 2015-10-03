<?php
    session_start();
?>
<!DOCTYPE html>
<html>
<head>
<!-- jquery -->
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>  
<meta charset="UTF-8">
<title>Consume Log</title>
<!--css link-->
<!--<link rel="stylesheet" type="text/css" href="table_style1.css" />-->
</head>

<body>
<div id="log_wrapper">
    <div id="log_show">
        <table id ="log_table">
            <tr><td><h3>Employee</h3></td><td><h3>Product</h3></td><td><h3>Brand</h3></td><td><h3>Quantity</h3></td><td><h3>Price</h3></td><td><h3>Date</h3></td><td><h3>Edit</h3></td></tr>
            <tr><td><label>Add new logs below!</label></td><td></td><td></td><td></td><td></td><td></td><td></td></tr>
            <tr><td><input type ="text" name ="employee"></td><td><input type ="text" name ="product"></td><td><input type ="text" name ="brand"></td><td><input type ="text" name ="quantity"></td><td><input type ="text" name ="price"></td><td></td><td></td></tr>
            <tr><td><input type ="text" name ="employee"></td><td><input type ="text" name ="product"></td><td><input type ="text" name ="brand"></td><td><input type ="text" name ="quantity"></td><td><input type ="text" name ="price"></td><td></td><td></td></tr>
            <tr><td><input type ="text" name ="employee"></td><td><input type ="text" name ="product"></td><td><input type ="text" name ="brand"></td><td><input type ="text" name ="quantity"></td><td><input type ="text" name ="price"></td><td></td><td></td></tr>
            <tr><td><input type ="text" name ="employee"></td><td><input type ="text" name ="product"></td><td><input type ="text" name ="brand"></td><td><input type ="text" name ="quantity"></td><td><input type ="text" name ="price"></td><td></td><td></td></tr>
            <tr><td></td><td></td><td></td><td></td><td><button id="button_user">add one record</button></td><td></td><td></td></tr>
            
        </table>
    <div id ="debug"></div>
    <div id ="debug2"></div>
    </div>
</div>
      
<script>
function verification(){
            var validate_sign = <?php
                $id = session_id();
                if( isset($id) && ($_SESSION["id"]==="admin" || $_SESSION["id"]==="client")){                  
                    echo 1;
                }
                else{
                    //header('HTTP/1.0 403 Forbidden');
                    echo 0;
                    //exit;
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
                $("input[name=employee], input[name=product], input[name=brand], input[name =quantity],input[name=price]").keypress(function(e){
                    if(e.keyCode==13)
                        validate();
                });
        });
function validate(){
    var employee = document.getElementsByName("employee");
    var product = document.getElementsByName("product");
    var brand = document.getElementsByName("brand");
    var quantity = document.getElementsByName("quantity");
    var price = document.getElementsByName("price");
    var i, sign_blank=1, sign_number=0, sign_below_0=0;
    for(i =0; i<4;i++){
        if(employee[i].value !="" && product[i].value != ""&& brand[i].value != ""&& quantity[i].value != ""&& price[i].value != ""){
            sign_blank = 0;
            var quantity_n = Number(quantity[i].value);
            var price_n = Number(price[i].value);
            if( !isNaN(quantity_n) && !isNaN(price_n) ){
                if( quantity_n>0 && price_n>=0 ){
                    ajax_trigger("add", employee[i].value, product[i].value, brand[i].value, quantity[i].value, price[i].value);
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
function delete_user(r){
    //alert("delete user !");
    var employee = r.parentNode.parentNode.parentNode.childNodes[0].childNodes[0].nodeValue;
    var product = r.parentNode.parentNode.parentNode.childNodes[1].childNodes[0].nodeValue;
    var product = r.parentNode.parentNode.parentNode.childNodes[1].childNodes[0].nodeValue;
    var product = r.parentNode.parentNode.parentNode.childNodes[1].childNodes[0].nodeValue;
    var product = r.parentNode.parentNode.parentNode.childNodes[1].childNodes[0].nodeValue;
    ajax_trigger(username, password, "delete","","");
    var i = r.parentNode.parentNode.parentNode.rowIndex;
    document.getElementById("id_table").deleteRow(i);
}    
function select_edit_delete(s){
    var selected = s.value;
    if(selected==="delete")
        delete_user(s.childNodes[1]);
    if(selected==="update")
        alert("update");
        //update_user(s.childNodes[2]);
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
    var keys = Object.keys(obj.logs);
    var row; var cell1; var cell2; var cell3; var cell4; var cell5; var cell6; var cell7;
    var i=0;
    for(var i =0; i<keys.length; i++){
        row = document.getElementById("log_table").insertRow(1);
        cell1 = row.insertCell(0);
        cell2 = row.insertCell(1);
        cell3 = row.insertCell(2);
        cell4 = row.insertCell(3);
        cell5 = row.insertCell(4);
        cell6 = row.insertCell(5);
        cell7 = row.insertCell(6);
        cell1.innerHTML= obj.logs[i].employee;
        cell2.innerHTML= obj.logs[i].product;
        cell3.innerHTML= obj.logs[i].brand;
        cell4.innerHTML= obj.logs[i].quantity;
        cell5.innerHTML= obj.logs[i].price;
        cell6.innerHTML= "      ";
        cell7.innerHTML='<select onchange ="select_edit_delete(this)"><option>edit</option><option>delete</option><option>update</option>';
    }
    
    //document.getElementById("debug2").innerHTML= obj.logs[3].employee+"<br>"+obj.logs.length;
    
}
function ajax_trigger(sign, employee, product, brand, quantity, price){
    //$("#debug").text(password);
    //alert(password);
    $.ajax({
        url: "input_table.php",
        data: {
            purpose: sign,
            employee: employee,
            product: product,
            brand: brand,
            quantity: quantity,
            price: price
            //username: username,
            //password: password,
            //admin:admin
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