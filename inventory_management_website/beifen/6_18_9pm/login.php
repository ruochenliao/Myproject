<html lang="en">
    
    <!-- jQuery -->    
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>      
    <!-- validate liberary -->
    <script src="http://ajax.aspnetcdn.com/ajax/jquery.validate/1.13.1/jquery.validate.js"></script>
    <head>
        <link rel="stylesheet" type="text/css" href="style.css" />
    </head>

    <body>
        <section>				
            <div id="container_demo" >
                    <div id="wrapper">
                        <!--login begin-->
                        <div id="login" class="animate form">
                            <form id ="login-form" name="login-form" class="login-form" action="" method=""> 
                                <h1>Log in</h1>
                                <select class = "select" id ="selected">
                                    <option value ="administrator" >administrator</option>
                                    <option value ="client">client</option>
                                </select>
                                <p> 
                                <label for="username" class="uname" data-icon="u" > Your email or username </label>
                                <input id="username" name="username" required="required" type="text" placeholder="username"/>
                                </p>
                                <p> 
                                <label for="password" class="youpasswd" data-icon="p"> Your password </label>
                                <input id="password" name="password" required="required" type="password" placeholder="password" /> 
                                </p>
                                <p class="keeplogin"> 
								<input type="checkbox" name="loginkeeping" id="loginkeeping" value="loginkeeping" /> 
								<label for="loginkeeping">Keep me logged in</label>
								</p>
                                    <input type="submit" class="button" value="Login" onclick ="click_f()"/> 
                                <div id="debug">debug</div>
                            </form>
                        </div>
                    </div>
                </div>  
            </section>
        
<script>
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
                var e = document.getElementById("selected");
                alert(e.options[e.selectedIndex].text);
                ajax_trigger($("#username").val(), $("#password").val(),e.options[e.selectedIndex].text );
            }
        });
    });
function login(output){
    if(output ==="adminLogin" ){
        alert("Admin pass!");
        var session_id = <?php 
            session_start();
            $sel =session_id();
            //$_SESSION["id"] = "admin";
            echo "\"$sel\"";
        ?>;
        console.log(session_id);
            window.open("distribute_id.php");
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