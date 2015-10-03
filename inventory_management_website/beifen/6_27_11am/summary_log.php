<?php
    session_start();
?>
<!DOCTYPE html>
<html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1">
<!-- jquery -->
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script> 
<!-- bootstrap -->
<link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css">
<script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/js/bootstrap.min.js"></script> 
<!-- local reference to pagination -->
<script src="pagination/src/bootstrap-paginator.js"></script>
<script src="pagination/lib/qunit-1.11.0.js"></script>       
<meta charset="UTF-8">
<title>Summary Log</title>
<!--css link-->
<!--<link rel="stylesheet" type="text/css" href="table_style1.css" />-->
</head>

<body>
    <h1>Summary</h1>
    <a href="input_log.php">Input Log</a>&nbsp;<a href="consume_log.php">Consume Log</a>&nbsp;<a id="distribute_id_link" href="distribute_id.php">Distribute ID table</a>
<div id="log_wrapper">
    <div id="log_show">
        <table id ="log_table">
            <tr><td><h3>Product</h3></td><td><h3>Brand</h3></td><td><h3>Quantity</h3></td><td><h3>Price</h3></td><td><h3>Last_Modified</h3></td><td><h3>Input</h3></td><td><h3>Consume</h3></td><td><h3>delete</h3></td><td><h3>Key</h3></td></tr>
            <tr><td><label>Add new products!</label></td><td></td><td></td><td></td><td></td><td><button id="input">confirm</button></td><td><button id="consume">confirm</button></td><td><button id="delete">confirm</button></td><td></td></tr>
            <tr><td><h3>Product</h3></td><td><h3>Brand</h3></td><td><h3>Quantity</h3></td><td><h3>Price</h3></td><td></td><td></td><td></td><td></td><td></td></tr>
            <tr><td><input type ="text" name ="product"></td><td><input type ="text" name ="brand"></td><td><input type ="text" name ="quantity"></td><td><input type ="text" name ="price"></td><td></td><td></td><td></td><td></td><td></td></tr>
            <tr><td><input type ="text" name ="product"></td><td><input type ="text" name ="brand"></td><td><input type ="text" name ="quantity"></td><td><input type ="text" name ="price"></td><td></td><td></td><td></td><td></td><td></td></tr>
            <tr><td><input type ="text" name ="product"></td><td><input type ="text" name ="brand"></td><td><input type ="text" name ="quantity"></td><td><input type ="text" name ="price"></td><td></td><td></td><td></td><td></td><td></td></tr>
            <tr><td><input type ="text" name ="product"></td><td><input type ="text" name ="brand"></td><td><input type ="text" name ="quantity"></td><td><input type ="text" name ="price"></td><td></td><td></td><td></td><td></td><td></td></tr>
            <tr><td></td><td></td><td></td><td><button id="add">add one record</button></td><td></td><td></td><td></td><td></td><td></td></tr>
        </table>
<div>
    <ul id='bp-3-element-test'></ul>
</div>        
    <div id ="debug"></div>
    <div id ="debug1"></div>
    </div>
</div>
      
<script>
var identification_g;
function verification(){
            var identification =<?php
                    $id = session_id();
                    if( isset($id)&& ($_SESSION["id"]==="admin"))
                        echo 1;
                    else if( isset($id)&& ($_SESSION["id"]==="client") )
                        echo 2;
                    else echo 3;
                ?>;
            alert("identity is: "+identification);
            identification_g = identification;
            if(identification == 1){
                $("#log_table").ready(function(){
                    ajax_trigger("read", "", "", "", "", "","","");
                });
            }

            else if (identification == 2){
                    $("#delete").hide();
                    $("#distribute_id_link").hide();
                    ajax_trigger("read", "", "", "", "", "","","");
            }

            else {
                document.body.innerHTML = "<h1>Login Forbidden</h1>";
                window.close();
            }
        }        
        $(document).ready(function(){
                verification();
            /*
                $("#log_table").ready(function(){
                    ajax_trigger("read", "", "", "", "", "", "", "");
                });
            */
                $("#add").click(function(){
                    validate();
                });
                $("#input").click(function(){
                    update_input_products();
                });
                $("#consume").click(function(){
                    update_consume_products();
                })
                $("#delete").click(function(){
                    delete_products();
                });
//                $("input[name=add_user], input[name=add_password]").keypress(function(e){
//                    if(e.keyCode==13)
//                        validate();
//                });
        });
function validate(){
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
                    alert(product[i].value);
                    ajax_trigger("add", "", "jiawei", product[i].value, brand[i].value, quantity[i].value, price[i].value,"");
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
/*    
function validate(){
    //var employee = document.getElementsByName("employee");
    var product = document.getElementsByName("product");
    var brand = document.getElementsByName("brand");
    var quantity = document.getElementsByName("quantity");
    var price = document.getElementsByName("price");
    var i, sign = 1;
    for(i =0; i<4;i++){
        if(product[i].value != ""&& brand[i].value != ""&& quantity[i].value != ""&& price[i].value != ""){
            ajax_trigger("add", "", "jiawei", product[i].value, brand[i].value, quantity[i].value, price[i].value,"");
            sign = 0;
        }
    }
    if(sign == 1)
        alert("Input can not be blank!");
}   
*/    
function update_input_products(){
    var num = document.getElementsByName("input").length;
    var input = document.getElementsByName("input");
    var i = 0; var count = 0;
    for(i =0; i<num;i++){
        if (input[i].value != 0){
            count++;
            var quantity = parseInt(input[i].value);
            var new_quantity = parseInt(input[i].parentNode.parentNode.childNodes[2].childNodes[0].nodeValue) + parseInt(input[i].value);
            var product = input[i].parentNode.parentNode.childNodes[0].childNodes[0].nodeValue;
            var brand = input[i].parentNode.parentNode.childNodes[1].childNodes[0].nodeValue;
            var price = input[i].parentNode.parentNode.childNodes[3].childNodes[0].nodeValue;
            var key = input[i].parentNode.parentNode.childNodes[8].childNodes[0].nodeValue;
            ajax_trigger("update", key, "jiawei", product, brand, new_quantity, price ,quantity);
            input[i].parentNode.parentNode.childNodes[2].childNodes[0].nodeValue = new_quantity;
            input[i].value = "0";
        }
    }
} 
function update_consume_products(){
    var num = document.getElementsByName("consume").length;
    var consume = document.getElementsByName("consume");
    var i = 0; var count = 0;
    for(i =0; i<num;i++){
        if (consume[i].value != 0){
            count++;
            var quantity = parseInt(consume[i].value);
            var new_quantity = parseInt(consume[i].parentNode.parentNode.childNodes[2].childNodes[0].nodeValue) - parseInt(consume[i].value);
            if (new_quantity < 0){
                alert("error!");
                return;
            }else{
                var product = consume[i].parentNode.parentNode.childNodes[0].childNodes[0].nodeValue;
                var brand = consume[i].parentNode.parentNode.childNodes[1].childNodes[0].nodeValue;
                var price = consume[i].parentNode.parentNode.childNodes[3].childNodes[0].nodeValue;
                var key = consume[i].parentNode.parentNode.childNodes[8].childNodes[0].nodeValue;
                ajax_trigger("update_consume", key, "jiawei", product, brand, new_quantity, price ,quantity);
                consume[i].parentNode.parentNode.childNodes[2].childNodes[0].nodeValue = new_quantity;
                consume[i].value = "0";
            }
        }
    }
}
function delete_product(s){
    //alert("delete user !");
    var key = s.parentNode.parentNode.childNodes[8].childNodes[0].nodeValue;
    //var employee = s.parentNode.parentNode.childNodes[0].childNodes[0].nodeValue;
    var product = s.parentNode.parentNode.childNodes[0].childNodes[0].nodeValue;
    var brand = s.parentNode.parentNode.childNodes[1].childNodes[0].nodeValue;
    var quantity = s.parentNode.parentNode.childNodes[2].childNodes[0].nodeValue;
    var price = s.parentNode.parentNode.childNodes[3].childNodes[0].nodeValue;
    ajax_trigger("delete", key, "", product, brand, quantity, price, "");
    var i = s.parentNode.parentNode.rowIndex;
    document.getElementById("log_table").deleteRow(i);
}
function delete_products(){
    var check_num = document.getElementsByName("delete").length;
    var box = document.getElementsByName("delete");
    var i = 0;
    for(i =0; i<check_num;i++){
        if (box[i].checked == true){
            delete_product(box[i]);
            i--;
            //alert(i);
        }
    }
}
function show_item(obj,i){
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
        //cell1.innerHTML= obj.logs[i].employee;
        cell1.innerHTML= obj.logs[i].product;
        cell2.innerHTML= obj.logs[i].brand;
        cell3.innerHTML= obj.logs[i].quantity;
        cell4.innerHTML= obj.logs[i].price;
        cell5.innerHTML= obj.logs[i].AddDate;  
        cell6.innerHTML = '<input name="input" type=number min="0" max="10000" step="1" value="0" size="6">';
        cell7.innerHTML = '<input name="consume" type=number min="0" max="10000" step="1" value="0" size="6">';
        if(identification_g == 1){
            cell8.innerHTML= '<input type="checkbox" name="delete"/>';
        }
        cell9.innerHTML= obj.logs[i].key;     
}
function pagination_f(obj){
    var total_num = obj.logs.length;
    var page = obj.logs[0].page;
    if(  page =="" )
        page =1;
    else if (obj.logs[0].sign==="insert"){
        show_item(obj,0);
        return;
    }
    alert("page is "+ page);
    alert("total number of items: "+total_num);
    var row; var cell1; var cell2; var cell3; var cell4; var cell5; var cell6; var cell7;
    var currentPage =page;
    var numberOfPages;
    var totalPages;
    var num_per_page =30;
    var num_current_items;
    if( total_num < num_per_page){
        totalPages = 1;
        numberOfPages = 1;
        num_current_items =total_num;
    }
    else{
        if( total_num % num_per_page)
            totalPages = parseInt( total_num/num_per_page +1 );
        else 
            totalPages = total_num/num_per_page
        num_current_items = num_per_page;
        if( totalPages <4 ){
            numberOfPages = totalPages;
        }
        else{
            numberOfPages = 4;
        }
    }
    $('#debug1').text("Page item clicked, type: "+totalPages+" page: "+page);
    var item_from = num_per_page*(page-1);
    var item_to;
    if(page == totalPages && total_num % num_per_page){
        item_to = item_from +(total_num%num_per_page);
    }
    else{
        item_to = item_from + num_per_page;
    }
    var table = document.getElementById("log_table");
    var row_length = table.rows.length;
    for(var j =1; j<row_length-7; j++){
        table.deleteRow(1);
    }
    alert('item_to is: '+item_to);
    alert('item_from is: '+item_from);    
    for(var i =item_to-1; i>=item_from;i--){
        show_item(obj,i);
    }
    var options = {
				alignment:"center",
                bootstrapMajorVersion:3,
                currentPage: currentPage,
                numberOfPages: numberOfPages,
                totalPages:totalPages,
                onPageClicked: function(e,originalEvent,type,page){
                    ajax_trigger("read", "", "", "", "", "","","",page);
                }
    }
    var element = $('#bp-3-element-test');
    element.bootstrapPaginator(options);     
}    
function show_form(output){
    console.log(output);
    if(output ==="duplicate"){
        alert("error: duplicate user name and password!");
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
    pagination_f(obj);
}
//need a key
function ajax_trigger(sign, key, employee, product, brand, quantity, price, original_quantity,page){

    $.ajax({
        url: "summary_table.php",
        data: {
            purpose: sign,
            key: key,
            employee: employee,
            product: product,
            brand: brand,
            quantity: quantity,
            price: price,
            original_quantity: original_quantity,
            page:page
        },
        type:'GET',
        success:function(output){
            $("#debug").html(output);
            show_form(output);
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