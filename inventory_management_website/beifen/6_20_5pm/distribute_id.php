<?php
    session_start();
?>
<!DOCTYPE html>
<html>
<head>
    <!-- jquery -->
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>  
<meta charset="UTF-8">
<title>Distribute Id</title>
</head>

<body>
<?php
    echo $_POST["sign"];
?>
    <table id ="id_table"> <!-- style="visibility: hidden"> -->
        <tr><td><font size =5>User name</font></td><td><font size =5>Password</font></td><td><font size =5>Edit</font></td></tr>
<!--        <tr><td>administrator</td><td>password</td><td><select onchange ="select_edit_delete(this)"><option>edit</option><option></option><option>update</option></select></td></tr>-->
        <tr><td>You can add new user below!</td><td></td><td></td></tr>
        <tr><td><input type ="text" name ="add_user"></td><td><input type ="text" name ="add_password"></td><td></td></tr>
        <tr><td><input type ="text" name ="add_user"></td><td><input type ="text" name ="add_password"></td><td></td></tr>
        <tr><td><input type ="text" name ="add_user"></td><td><input type ="text" name ="add_password"></td><td></td></tr>
        <tr><td><input type ="text" name ="add_user"></td><td><input type ="text" name ="add_password"></td><td></td></tr>
        <tr><td></td><td><button id ="button_user">add one user</button></td><td></td></tr>
<!--
        <tr><td><input type ="text" id="admin_name"></td><td><input type ="text" id="admin_password"></td><td></td></tr>
        <tr><td></td><td><button onclick="update_admin()">update Administrator</button></td><td></td></tr>
-->
    </table>
    <div id ="debug"></div>
    <script>
        function verification(){
            var validate_sign = <?php
                $id = session_id();
                if( isset($id) && $_SESSION["id"]==="admin"){                    
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
                document.getElementById("id_table").style.visibility = "visible";
                $("#id_table").ready(function(){
                    ajax_trigger("", "", "read","","");
                });                
            }
            else if(validate_sign ==0 ){
                alert("close the window");
                document.body.innerHTML("");
                window.close();
            }
        }
        /*
        function verification(){
            var testV = 1;
            var pass1 = prompt('Please Enter Your Password',' ');
            while (testV < 3) {
                if (!pass1)
                    history.go(-1);
                if (pass1.toLowerCase() == "letmein") {
                    return;
                    window.open('protectpage.html');
                    break;
                }
                testV+=1;
                var pass1 = prompt('Access Denied - Password Incorrect, Please Try Again.','Password');
            }
            if (pass1.toLowerCase()!="password" & testV ==3){
                history.go(-1);
                alert("error password");
                window.close();
            }
        }
        */
        $(document).ready(function(){
                verification();
                $("#button_user").click(function(){
                    validate();
                });
                $("input[name=add_user], input[name=add_password]").keypress(function(e){
                    if(e.keyCode==13)
                        validate();
                });
        });    

function validate(){
    var user_name = document.getElementsByName("add_user");
    var password = document.getElementsByName("add_password");
    var i, sign = 1;
    for(i =0; i<4;i++){
        if(user_name[i].value !="" && password[i].value != ""){
            if(password[i].value.length<1){
                alert("password must has more than 6 characters");
            }
            else{
                ajax_trigger(user_name[i].value,password[i].value,"add","","");
            }
            sign = 0;
        }
    }
    if(sign == 1)
        alert("User name and password can not be blank");
}
function delete_user(r){
    //alert("delete user !");
    var username = r.parentNode.parentNode.parentNode.childNodes[0].childNodes[0].nodeValue;
    var password = r.parentNode.parentNode.parentNode.childNodes[1].childNodes[0].nodeValue;
    ajax_trigger(username, password, "delete","","");
    var i = r.parentNode.parentNode.parentNode.rowIndex;
    document.getElementById("id_table").deleteRow(i);
}
function update_user(r){
    var i = r.parentNode.parentNode.parentNode.rowIndex;
    r.parentNode.parentNode.parentNode.childNodes[0].setAttribute("contentEditable",true);
    r.parentNode.parentNode.parentNode.childNodes[1].setAttribute("contentEditable",true);
    var original_username = r.parentNode.parentNode.parentNode.childNodes[0].childNodes[0].nodeValue;
    var original_password =r.parentNode.parentNode.parentNode.childNodes[1].childNodes[0].nodeValue;
    var node = r.parentNode.parentNode.parentNode.childNodes[0];
    r.parentNode.parentNode.parentNode.setAttribute("id","update_r");
    r.parentNode.parentNode.parentNode.childNodes[0].setAttribute("id","update1");
    r.parentNode.parentNode.parentNode.childNodes[1].setAttribute("id","update2");
    function click_enter_event(para){
            if(para ==="useless") return;
            if( $("#update1").text()===original_username&&$("#update2").text()===original_password )
                ;
            else{
            alert("update1: "+$("#update1").text()+" "+"update2: "+$("#update2").text()+"original password: "+original_password);
            ajax_trigger( $("#update1").text(), $("#update2").text(),"update", original_username,original_password);
            $("#update1").removeAttr("contentEditable");
            $("#update2").removeAttr("contentEditable");
            $("#update1").removeAttr("id");
            $("#update2").removeAttr("id");
            $("#update_r").removeAttr("id");
            var sel = r.parentNode;
            sel.selectedIndex = 0;
            para.preventDefault();    
            }
    }
    var click_sign = 0;
    
    $("#update_r").keypress(function(e){  
        if(e.which ==13){
            click_enter_event(e);
            click_sign = 0;
            return;
        }
    });
    
    $("#update_r").click(function(event){
        event.stopPropagation();
        click_enter_event("useless");
        click_sign = 1;
    });
    $("body").one("click",function(){
        if(click_sign ==1)
            click_enter_event();
        click_sign =0;
    });
    //$("#update1").removeAttr("contentEditable");
}
function select_edit_delete(s){
    var selected = s.value;
    if(selected==="delete")
        delete_user(s.childNodes[1]);
    if(selected==="update")
        update_user(s.childNodes[2]);
}
function show_form(output){
    console.log(output);
    if(output==="admin"){
        alert("You can not delete the Administrator");
        location.reload();
        return;
    }
    if(output ==="duplicate"){
        alert("error: duplicate user name and password!");
        location.reload();
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
    var html = "";
    var json_return = JSON.parse(output);
    var keys = Object.keys(json_return);
    var row;var cell1;var cell2;var cell3;
    var i =0;
    for(var i =0; i<keys.length; i++){
        //alert("keys length is: "+keys.length);
        row = document.getElementById("id_table").insertRow(1);
        cell1 = row.insertCell(0);
        cell2 = row.insertCell(1);
        cell3 = row.insertCell(2);
        var key = [];
        for(var k in json_return[i])
            key.push(k);
        //value = Object.values(json_return[i]);
        cell1.innerHTML= key[0];
        //alert(key[i]+":"+json_return[i][key[0]]);
        cell2.innerHTML= json_return[i][key[0]];
        cell3.innerHTML='<select onchange ="select_edit_delete(this)"><option value="edit">edit</option><option>delete</option><option>update</option>';
    }
}
function ajax_trigger(username, password, sign,original_username,original_password){
    //$("#debug").text(password);
    //alert(password);
    $.ajax({
        url: "id_table.php",
        data: {
            purpose: sign,
            user:username,
            pass: password,
            original_username:original_username,
            original_password:original_password
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