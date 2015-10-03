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
    <!--css link-->
<link rel="stylesheet" type="text/css" href="table_style_id.css" />
</head>

<body background="background_image.jpg">
<header>
				<nav class="codrops-demos">
					<span>Welcome user: <strong><?php echo $_SESSION["realname"]?></strong></span>
                    <a href="summary_log.php">Summary Log</a>
<!--                    <button id="logout">logout</button>-->
				</nav>
</header>    
<?php
    echo $_POST["sign"];
?>
<div id="log_wrapper">
    <div id="log_show">
        <h1>Distributed ID</h1>
    <table id ="id_table"> <!-- style="visibility: hidden"> -->
        <tr><td><h4>Real Name</h4></td><td><h4>User Name</h4></td><td><h4>Password</h4></td><td><h4>Update</h4></td><td><h4>Delete</h4></td><td></td></tr>
        <tr><td><h4>Add new user below!</h4></td><td><h4>User Name</h4></td><td><h4>Password</h4></td><td><button id="update">confirm</button></td><td><button id="confirm_delete">confirm</button></td><td></td></tr>
        <tr><td><input type ="text" name ="add_realName"></td><td><input type ="text" name ="add_user"></td><td><input type ="text" name ="add_password"></td><td></td><td></td><td></td></tr>
        <tr><td><input type ="text" name ="add_realName"></td><td><input type ="text" name ="add_user"></td><td><input type ="text" name ="add_password"></td><td></td><td></td><td></td></tr>
        <tr><td><input type ="text" name ="add_realName"></td><td><input type ="text" name ="add_user"></td><td><input type ="text" name ="add_password"></td><td></td><td></td><td></td></tr>
        <tr><td><input type ="text" name ="add_realName"></td><td><input type ="text" name ="add_user"></td><td><input type ="text" name ="add_password"></td><td></td><td></td><td></td></tr>
        <tr><td></td><td></td><td><button id ="button_user">add one user</button></td><td></td><td><button id="refresh_page">Refresh the Page</button></td><td></td></tr>

    </table>
    </div>
</div>
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
            //alert(validate_sign);
            if(validate_sign ==1 ){
                document.getElementById("id_table").style.visibility = "visible";
                $("#id_table").ready(function(){
                    ajax_trigger("","", "", "read","","");
                });                
            }
            else if(validate_sign ==0 ){
                //alert("close the window");
                //document.body.innerHTML("");
                document.body.innerHTML = "<h1>Login Forbidden</h1>";
                window.close();
            }
        }
        $(document).ready(function(){
                verification();
                $("#logout").click(function(){
                    
                    ajax_trigger("logout");
                });            
                $("#button_user").click(function(){
                    validate();
                });
                $("input[name=add_realName],input[name=add_user], input[name=add_password]").keypress(function(e){
                    if(e.keyCode==13)
                        validate();
                });
                $("#update").click(function(){
                    update_users();
                });            
                $("#confirm_delete").click(function(){
                    delete_users();
                });
                $("#refresh_page").click(function(){
                    location.reload();
                })
        });

function validate(){
    //alert("you got validate");
    var real_name = document.getElementsByName("add_realName");
    var user_name = document.getElementsByName("add_user");
    var password = document.getElementsByName("add_password");
    var i, sign = 1;
    for(i =0; i<4;i++){
        if(real_name[i].value !="" && user_name[i].value !="" && password[i].value != ""){
            if(password[i].value.length<1){
                alert("password must has more than 6 characters");
            }
            else{
                //alert("real_name[i] is: "+real_name[i]);
                ajax_trigger(real_name[i].value,user_name[i].value,password[i].value,"add","","");
                real_name[i].value ="";user_name[i].value="";password[i].value="";
            }
            sign = 0;
        }
    }
    if(sign == 1)
        alert("Real name, User name and password can not be blank");
}
function delete_user(r){
    //alert("delete user !");
    var realname = r.childNodes[0].childNodes[0].nodeValue;
    var username = r.childNodes[1].childNodes[0].nodeValue;
    var password = r.childNodes[2].childNodes[0].nodeValue;
    ajax_trigger(realname,username, password, "delete","","");
    var i = r.rowIndex;
    document.getElementById("id_table").deleteRow(i);
}
function delete_users(){
    //var check_num = document.getElementsByName("delete").length;
    var box = document.getElementsByName("delete");
    var i = 0;
    for(i =0; i<box.length;i++){
        if (box[i].checked == true){
            delete_user(box[i].parentNode.parentNode);
            i--;
        }
    }
}
/*        
function update_user(r){
    var i = r.rowIndex;
    r.childNodes[0].setAttribute("contentEditable",true);
    r.childNodes[1].setAttribute("contentEditable",true);
    r.childNodes[2].setAttribute("contentEditable",true);
    var original_realname = r.childNodes[0].childNodes[0].nodeValue;
    var original_username = r.childNodes[1].childNodes[0].nodeValue;
    var original_password = r.childNodes[2].childNodes[0].nodeValue;
    //var node = r.parentNode.parentNode.parentNode.childNodes[0];
    r.setAttribute("id","update_r");
    r.childNodes[0].setAttribute("id","update0");
    r.childNodes[1].setAttribute("id","update1");
    r.childNodes[2].setAttribute("id","update2");
    function return_status(r){
    
    }
    function return_status(r){
                $("#update0").removeAttr("contentEditable");
                $("#update1").removeAttr("contentEditable");
                $("#update2").removeAttr("contentEditable");
                $("#update0").removeAttr("id");
                $("#update1").removeAttr("id");
                $("#update2").removeAttr("id");
                $("#update_r").removeAttr("id");
                var sel = r.childNodes[3].childNodes[0];
                sel.selectedIndex = 0;   
                para.preventDefault();         
    }
    function click_enter_event(para){
            if(para ==="useless") {
                return;
            }
            if($("#update0").text()===""||$("#update1").text()===""||$("#update2").text()===""){
                alert("update value can not be blank !");
                return_status(r);
                return;
            }
            if( $("#update0").text()===original_realname&&$("#update1").text()===original_username&&$("#update2").text()===original_password )
                return_status(r);
            else{
                alert($("#update0").text()+" "+$("#update1").text()+" "+$("#update2").text()+"original password: "+original_password);
                ajax_trigger( $("#update0").text(),$("#update1").text(), $("#update2").text(),"update", original_username,original_password);
                return_status(r);
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
}
*/
        
function check(b){
    if (b.checked){
        b.parentNode.parentNode.childNodes[0].setAttribute("contentEditable",true);
        b.parentNode.parentNode.childNodes[1].setAttribute("contentEditable",true);
        b.parentNode.parentNode.childNodes[2].setAttribute("contentEditable",true);
    }else{
        b.parentNode.parentNode.childNodes[0].setAttribute("contentEditable",false);
        b.parentNode.parentNode.childNodes[1].setAttribute("contentEditable",false);
        b.parentNode.parentNode.childNodes[2].setAttribute("contentEditable",false);
    }
}    
function update_user(r){
    var realname = r.childNodes[0].childNodes[0].nodeValue;
    var username = r.childNodes[1].childNodes[0].nodeValue;
    var password = r.childNodes[2].childNodes[0].nodeValue;
    var key = r.childNodes[5].childNodes[0].nodeValue;
    ajax_trigger(realname,username, password, "update",key);
}    
function update_users(){
    var check_num = document.getElementsByName("update").length;
    var box = document.getElementsByName("update");
    var i = 0;   
    for(i =0; i<check_num;i++){
        if (box[i].checked == true){
            update_user(box[i].parentNode.parentNode);
            box[i].checked = false;
            check(box[i]);
        }
    }
}
        
function select_edit_delete(s){
    var selected = s.value;
    if(selected==="delete")
        delete_user(s.parentNode.parentNode);
    if(selected==="update")
        update_user(s.parentNode.parentNode);
}
function show_form(output){
    console.log(output);
    if(output ==="logout"){
        window.open("login.php",'_self',false);
    }    
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
        alert("a user has been deleted!");
        return;
    }
    if(output ==="fail_update"){
        alert("failed to update!");
        return;
    }
    var html = "";
    var json_return = JSON.parse(output);
    //var keys = Object.keys(json_return);
    var row;var cell0; var cell1;var cell2;var cell3;
    var i =0;
    for(var i =0; i<json_return.logs.length; i++){
        //alert("keys length is: "+keys.length);
        row = document.getElementById("id_table").insertRow(1);
        cell0 = row.insertCell(0);
        cell1 = row.insertCell(1);
        cell2 = row.insertCell(2);
        cell3 = row.insertCell(3);
        cell4 = row.insertCell(4);
        cell5 = row.insertCell(5);
        //value = Object.values(json_return[i]);
        cell0.innerHTML = json_return.logs[i].realname;
        cell1.innerHTML= json_return.logs[i].user;
        cell2.innerHTML= json_return.logs[i].pass;
        //cell3.innerHTML='<select onchange ="select_edit_delete(this)"><option value="edit">edit</option><option>delete</option><option>update</option>';
        cell3.innerHTML= '<input type="checkbox" name="update" onchange="check(this)"/>';
        cell4.innerHTML ='<input type="checkbox" name="delete"/>';
        cell5.innerHTML =json_return.logs[i].key;
    }
}
function ajax_trigger(real_name,username, password, sign,key){
    //$("#debug").text(password);
    //alert(password);
    $.ajax({
        url: "id_table.php",
        data: {
            purpose: sign,
            real_name:real_name,
            user:username,
            pass: password,
            key:key
            //original_username:original_username,
            //original_password:original_password
        },
        type:'GET',
        success:function(output){
            //$("#debug").html(output);
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