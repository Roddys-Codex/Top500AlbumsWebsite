<!DOCTYPE html>
<html lang="en">
<?php 
$pageTitle = "Login";
require("../phpTemplates/header.php"); 
?>

<body>
    <?php 
    
    $navItem1 = "Home";
    $navItem1Link = "index.php";

    $navItem2 = "Album List";
    $navItem2Link = "albumList.php";

    $navItem5 = "Log In <span class='sr-only'>(current)</span>";
    $navItem5Link = "login.php";
    

    if(isset($_COOKIE["Username"])){
                
            echo "
            <script>
                window.location.assign('index.php');
            </script>
            ";
    }
    require("../phpTemplates/navbar.php"); ?>

    <script>
    $(document).ready(function() {

        $("#loginFormSubmission").click(function(e) {
            
            var usernameEntered = $("#formInput0").val();
            var passwordEntered = $("#formInput1").val();

            $.ajax({
                url: '../process_files/loginValidate.php',
                type: 'POST',
                data: jQuery.param({ usernameCheck: usernameEntered, passwordCheck: passwordEntered},) ,
                contentType: 'application/x-www-form-urlencoded; charset=UTF-8',
                success: function (response) {
                    result = jQuery.parseJSON(response)

                    if(result.userValid !== "No entry") {

                        if(result.passwordValid !== "No entry") {
                            
                            if(result.userValid === true) {

                                if(result.passwordValid === false) {

                                    alert("Incorrect password. Please try again or enter another username.")
                                    
                                } else if(result.passwordValid === true) {
                                    alert("Everythings  correct!");
                                    $("#accountLoginForm").submit();
                                }
                            
                            } else {
                                alert("Username does not exist.");
                            }
                        } else {
                            alert("Please enter a password.");
                        }
                        
                    } else {
                        alert("Please enter a username.");
                    }
                    
                },
                error: function () {
                    alert("error");
                }
                });

            e.preventDefault();
            
            
        })

        $("#forgotPassword").click(function(e) {
            
            var isExecuted = confirm("Are you sure you have forgotten your password and wish to reset?");

            if(isExecuted == true) {
                var emailAddress = prompt('Enter your email (a temporary password will be issued to this email address):');
            
            

                $.ajax({
                    url: '../../phpmailersend.php',
                    type: 'POST',
                    data: jQuery.param({ emailAddressSend: emailAddress},) ,
                    contentType: 'application/x-www-form-urlencoded; charset=UTF-8',
                    success: function (response) {

                        alert("Check your email! If an account existed with that email a temporary password has been issued.");
                        window.location.reload();
                        
                    },
                    error: function () {
                        alert("error");
                    }
                });
            }
            e.preventDefault();
            
            
        })

    });
</script>
    
    <!-- *********************** CONTAINER ****************************** -->
    <!-- Container -->
    <div class="container">

        <!-- Card -->
        <div class="card cardsClass w-75 text-center mx-auto mt-5 top-buffer">
            <!-- Card Header -->
            <div class="card-header">
                Login
            </div>

            <!-- Card Body -->
            <div class="card-body">

                <!-- Form -->
                <form id="accountLoginForm" method="POST" action="authenticateUser.php" enctype='multipart/form-data'>

                    <!-- Form Container -->
                    <div class="container">

                        <!-- Username -->
                        <div class="form-group row">
                            <label for="usernamelog" class="col-md-4 col-form-label text-right">Username</label>
                            <div class="col-md-8">
                                <input type="text" class="form-control" id="formInput0" placeholder="username" name="usernamelog">
                            </div>
                        </div>

                        <!-- Password -->
                        <div class="form-group row">
                            <label for="formInput2" class="col-md-4 col-form-label text-right">Password</label>
                            <div class="col-md-8">
                                <input type="password" class="form-control" id="formInput1" name="passwordlog">
                            </div>
                        </div>

                        <!-- Forgotten password -->
                        <a href='' class='d-block mb-3' id='forgotPassword'>Forgot password?</a>

                        <!-- Submit -->
                        <button class="btn btn-primary submit btn_handler mb-3" onclick="" id="loginFormSubmission">Login</a>
                
                    </div>
                </form>
                
            </div>

            <!-- Card footer -->
            <div class="card-footer text-muted">
            </div>
        </div>
    </div>

    <?php require("../phpTemplates/footer.php"); ?>

    

</body>
</html>