<!DOCTYPE html>
<html>
<!-- <meta name="viewport" content="height=device-height">  -->
<meta name="viewport" content="width=device-width">
<!-- jQuery -->    
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>      
<!-- validate liberary -->
<script src="http://ajax.aspnetcdn.com/ajax/jquery.validate/1.13.1/jquery.validate.js"></script>
    
<head>
<title>Login</title>
<!--CSS style-->
<link href="Cstyle.css" rel="stylesheet" type="text/css" />
</head>
<body>
<div id="wrapper">
<!--LOGIN FORM-->
<form id ="login-form" name="login-form" class="login-form" action="" method="">
	<!--HEADER-->
    <div class="header">
        <h1>Login Form</h1>
        <select>
            <option value ="administrator">administrator</option>
            <option value ="client">client</option>
        </select>
    </div>
    <!--END HEADER-->
	<!--CONTENT-->
    <div class="content">
        <input id="username" name="username" type="text" class="input username" value="" onfocus="this.value=''" placeholder="username"/>
        <input id="password" name="password" type="password" class="input password" value="" onfocus="this.value=''" placeholder="password"/>
    </div>
    <!--END CONTENT-->
    <!--FOOTER-->
    <div class="footer">
        <input type="submit" name="login" value="Login" class="button" onclick="click_f()"/>
        <input type="button" name="register" value="register" class="register" />
    </div>
    <div id ="debug"></div>
    <!--END FOOTER-->
</form>
<!--END LOGIN FORM-->
</div>
<script type="text/javascript">
    //$("login").click(function(){
    function click_f(){
        $(document).ready(function(){
            $("#login-form").validate({
                rules:{
                    username:{
                        required:true,
                        minlength:1
                    },
                    password:{
                        required:true,
                        minlength:1
                    }
                },
                messages:{
                    username:{
                        required: '<span style ="color:red">username can not be blank</span>',
                        minlength:'<span style ="color:red">Please enter at least 7 characters</span>'
                    },
                    password:{
                        required: '<span style ="color:red">password can not be blank</span>',
                        minlength:'<span style ="color:red">Please enter at least 7 characters</span>'
                    }
                },
                success:"valid",
                submitHandler:function(){
                    //alert("submitted");
                    ajax_trigger($("#username").val(), $("#password").val(), "login");
                }
            });
        });
function login(output){
    if(output ==="login" ){
        alert("pass!");
        var session_id = <?php 
            session_start();
            $sel =session_id();
            echo "\"$sel\"";
        ?>;
        console.log(session_id);
        //$("login").click(function(){
            window.open("distribute_id.php");
        //});
        
        //window.close();
    }
    if(output ==="0result")
        alert("failed!");
}
function ajax_trigger(username,password,sign){
    //$("#debug").text(password);
    //alert(password);
    $.ajax({
        url: "id_table.php",
        data: {
            purpose: sign,
            user:username,
            pass: password
        },
        type:'POST',
        success:function(output){
            //window.open("distribute_id.php");
            //alert("you passed: "+ output);
            $("#debug").html(output);
                login(output);
            },
        error:function(){
                throw new Error("Something went badly wrong!");
                alert("error in ajax");    
            },
        async: false
    }); 
}
}    
</script>
</body>
</html>