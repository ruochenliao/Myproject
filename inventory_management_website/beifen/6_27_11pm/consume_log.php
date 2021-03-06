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
<title>Consume Log</title>
<!--css link-->
<!--<link rel="stylesheet" type="text/css" href="table_style1.css" />-->
</head>

<body>
<div id="log_wrapper">
    <div id="log_show">
        <table id ="log_table">
            <tr><td><h3>Employee</h3></td><td><h3>Product</h3></td><td><h3>Brand</h3></td><td><h3>Quantity</h3></td><td><h3>Price</h3></td><td><h3>Date</h3></td><td name ="hideme"><h3>Update</h3></td><td name ="hideme"><h3>Delete</h3></td><td></td></tr>
            <tr name="hideme"><td><h3>Add new logs below:</h3></td><td><h3>Product</h3></td><td><h3>Brand</h3></td><td><h3>Quantity</h3></td><td><h3>Price</h3></td><td></td><td><button id="update">confirm</button></td><td><button id="confirm_delete">confirm</button></td></tr>
            <tr name="hideme"><td><div type ="text" name ="employee"/></td><td><input type ="text" name ="product"></td><td><input type ="text" name ="brand"></td><td><input type ="text" name ="quantity"></td><td><input type ="text" name ="price"></td><td></td><td></td></tr>
            <tr name="hideme"><td><div type ="text" name ="employee"/></td><td><input type ="text" name ="product"></td><td><input type ="text" name ="brand"></td><td><input type ="text" name ="quantity"></td><td><input type ="text" name ="price"></td><td></td><td></td></tr>
            <tr name="hideme"><td><div type ="text" name ="employee"/></td><td><input type ="text" name ="product"></td><td><input type ="text" name ="brand"></td><td><input type ="text" name ="quantity"></td><td><input type ="text" name ="price"></td><td></td><td></td></tr>
            <tr name="hideme"><td><div type ="text" name ="employee"/></td><td><input type ="text" name ="product"></td><td><input type ="text" name ="brand"></td><td><input type ="text" name ="quantity"></td><td><input type ="text" name ="price"></td><td></td><td></td></tr>
                <tr name="hideme"><td></td><td></td><td></td><td></td><td><button id="button_user">add one record</button></td><td></td><td></td></tr>
        </table>
<div>
    <ul id='bp-3-element-test'></ul>
</div>
    <div id ="debug1"></div>    
    <div id ="debug"></div>
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
                    ajax_trigger("read", "", "", "", "", "","");
                });
            }
            else if (identification == 2){
                    $('tr[name = hideme]').hide();
                    $('td[name = hideme]').hide();
                    //$('input[name = update]').hide();
                    //$('input[name = delete]').hide();
                    ajax_trigger("read", "", "", "", "", "","");
            }
            else {
                document.body.innerHTML("");
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
    var employee = document.getElementsByName("employee");
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
                    ajax_trigger("add",employee[i].value ,product[i].value, brand[i].value, quantity[i].value, price[i].value);
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
    var employee = s.parentNode.parentNode.childNodes[0].childNodes[0].nodeValue;
    var product = s.parentNode.parentNode.childNodes[1].childNodes[0].nodeValue;
    var brand = s.parentNode.parentNode.childNodes[2].childNodes[0].nodeValue;
    var quantity = s.parentNode.parentNode.childNodes[3].childNodes[0].nodeValue;
    var price = s.parentNode.parentNode.childNodes[4].childNodes[0].nodeValue;
    var key = s.parentNode.parentNode.childNodes[8].childNodes[0].nodeValue;
    alert(s.parentNode.parentNode.childNodes[8].childNodes[0].tagName);
    ajax_trigger("delete",employee, product, brand, quantity, price,key);
    var i = s.parentNode.parentNode.rowIndex;
    document.getElementById("log_table").deleteRow(i);
}
function delete_users(){
    //var check_num = document.getElementsByName("delete").length;
    var box = document.getElementsByName("delete");
    var i = 0;
    for(i =0; i<box.length;i++){
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
    var quantity_n = Number(quantity);
    var price_n = Number(price);
    if( product ==""|| brand =="" || quantity =="" || price =="" ){
        alert("update error: update can not be blank");
    }
    else if ( isNaN(quantity_n) || isNaN(price_n) ){
        alert("update error: should input number");
    }
    else if( quantity_n <0 || price_n < 0 ){
        alert("update error: Input number shouldn't be negative!");
    }
    else{
        ajax_trigger("update",employee,product,brand,quantity,price,key);
    }
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
        cell1.innerHTML= obj.logs[i].employee;
        cell2.innerHTML= obj.logs[i].product;
        cell3.innerHTML= obj.logs[i].brand;
        cell4.innerHTML= obj.logs[i].quantity;
        cell5.innerHTML= obj.logs[i].price;
        cell6.innerHTML= obj.logs[i].AddDate;
        if(identification_g == 1){
            cell7.innerHTML='<input type="checkbox" name="update"  onchange="check(this)"/>';
            cell8.innerHTML = '<input type="checkbox" name="delete"/>';
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
    for(var j =1; j<row_length-6; j++){
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
                    ajax_trigger("read", "", "", "", "", "","",page);
                }
    }
    var element = $('#bp-3-element-test');
    element.bootstrapPaginator(options);     
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
    pagination_f(obj);
}
function ajax_trigger(sign, employee, product, brand, quantity, price,key,page){
    
    $.ajax({
        url: "consume_table.php",
        data: {
            purpose: sign,
            employee:employee,
            product: product,
            brand: brand,
            quantity: quantity,
            price: price,
            key: key,
            page:page
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