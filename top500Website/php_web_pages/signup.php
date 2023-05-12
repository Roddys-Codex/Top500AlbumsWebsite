<?php

    include("../functions/functions.php");
    
?>
<!DOCTYPE html>
<html lang="en">

<?php 
$pageTitle = "Sign up";
require("../phpTemplates/header.php"); 
?>

<script>
    $(document).ready(function() {

        $("#formSubmission").click(function(e) {
            var usernameEntered = $("#formInput0").val();
            var emailEntered = $("#formInput1").val();
            var passwordEntered = $("#formInput2").val();
            
            $.ajax({
                url: '../process_files/signupvalidate.php',
                type: 'POST',
                data: jQuery.param({ usernameCheck: usernameEntered, emailCheck: emailEntered, passwordCheck: passwordEntered},) ,
                contentType: 'application/x-www-form-urlencoded; charset=UTF-8',
                success: function (response) {
                    result = jQuery.parseJSON(response)

                    if(result.userExists == true) {
                        alert("Username already exists, please choose a different username");
                    } else if(result.emailExists == true) {
                        alert("Email already exists. Please login to account or choose a different email.")
                    } else if(result.userExists == 'No entry') {
                        alert("Please enter a username.")
                    } else if(result.emailExists == 'No entry') {
                        alert("Please enter an email.")
                    } else if(result.passwordExists == "No entry"){
                        alert("Please enter a password.")
                    } else {
                        alert("user created");
                        $("#accountCreationForm").submit();
                    }
                },
                error: function () {
                    alert("error");
                }
                });
            e.preventDefault();
            
            
        })

    });
</script>

<body>
    
    <?php 
    $navItem1 = "Home";
    $navItem1Link = "index.php";

    $navItem2 = "Album List";
    $navItem2Link = "albumList.php";

    $navItem3 = "Register <span class='sr-only'>(current)</span>";
    $navItem3Link = "signup.php";
    $navItem3Active = "active";

    $navItem5 = "Log In";
    $navItem5Link = "login.php";

    if(isset($_COOKIE["Username"])){
                
            echo "
            <script>
                window.location.assign('index.php');
            </script>
            ";
    }
    include("../phpTemplates/navbar.php"); 
    ?>
    
    <!-- *********************** CONTAINER *************************************** -->
    <!-- Container -->
    <div class="container cardsClass">
        <!-- Card -->
        <div class="card text-center mt-5 top-buffer">
            <!-- Card Header -->
            <div class="card-header">
                Account Creation
            </div>
            <!-- Card Body -->
            <div class="card-body">
                <!-- Form -->
                <form id="accountCreationForm" method="POST" action="processaccount.php" enctype='multipart/form-data'>
                    <!-- Form Container -->
                    <div class="container">
                        <!-- Username -->
                        <div class="form-group row">
                            <label for="staticEmail" class="col-md-4 col-form-label text-right">Username</label>
                            <div class="col-md-8">
                                <input type="text" class="form-control" id="formInput0" placeholder="email@example.com" name="username">
                            </div>
                        </div>

                        <!-- Email -->
                        <div class="form-group row">
                            <label for="staticEmail" class="col-md-4 col-form-label text-right">Email</label>
                            <div class="col-md-8">
                                <input type="text" class="form-control" id="formInput1" placeholder="email@example.com" name="email">
                            </div>
                        </div>

                        <!-- Password -->
                        <div class="form-group row">
                            <label for="formInput2" class="col-md-4 col-form-label text-right">Password</label>
                            <div class="col-md-8">
                                <input type="password" class="form-control" id="formInput2" name="password">
                            </div>
                        </div>

                        <!-- Fav Genre -->
                        <div class="form-group row">
                            <label for="favGenre" class="col-md-4 col-form-label text-right">Favourite Genres</label>
                            <select class="selectpicker" multiple="multiple" name="genre[]" id="formInput3">
                                <?php
                                echo
                                "<option value='Rock'>Rock</option>
                                <option value='Pop'>Pop</option>
                                <option value='Funk / Soul'>Funk / Soul</option>
                                <option value='Jazz'>Jazz</option>
                                <option value='Folk'>Folk</option>
                                <option value='World'>World</option>
                                <option value='Country'>Country</option>
                                <option value='Classical'>Classical</option>
                                <option value='Stage & Screen'>Stage & Screen</option>
                                <option value='Reggae'>Reggae</option>
                                <option value='Hip Hop'>Hip Hop</option>
                                <option value='Electronic'>Electronic</option>
                                <option value='Latin'>Latin</option>"
                                ?>
                            </select>

                        </div>

                        <!-- Profile Picture -->
                        <div class="form-group row mt-5 mb-5">
                            <label for="upload" class="col-md-4 text-right" id="formInput4">Upload display picture</label>
                            <input type="file" class="form-control-file col-md-6 justify-content-right" id="upload" onChange="readURL(this)" name="profilepic" accept="image/*">
                            <img class="mt-5" id="display-image" src=""/>
                        </div>
                    </div>
                    <!-- Submit -->
                    <button class="btn btn-primary submit btn_handler" onclick="" id="formSubmission">Submit</a>
                </form>
                
            </div>

            <!-- Card footer -->
            <div class="card-footer text-muted">
                
            </div>
        </div>
    </div>

    <?php include("../phpTemplates/footer.php"); ?>

    <script>

        function readURL(input) {
                if (input.files && input.files[0]) {
                    var reader = new FileReader();
                    reader.onload = function (e) {
                        $('#display-image').attr('src', e.target.result).css('border-radius', '50%').css('margin','auto').css('width', '13em').css('height','13em');
                    };
                    reader.readAsDataURL(input.files[0]);
                }
            }

    </script>

    <script>

        $(document).ready(function(){

            $("#accountCreationForm").submit(function(e) {
                var userData = [];

                console.log(userData)
                for(i=1; i<5; i++) {
                    userData.push(document.getElementById("formInput"+i).value);
                    console.log(userData)
                } 

                console.log("favGenre: "+$("#formInput3 option:selected").text());

                userData[2] = $("#formInput3 option:selected").text();
                userData[3] = $('#display-image').attr('src');
                
                localStorage.clear();
                if (!localStorage.getItem('formInput')) {
                    var toLocalStorage = [];
                    toLocalStorage.push(userData);
                    localStorage.setItem('formInput', JSON.stringify(toLocalStorage));
                } 
                
                console.log("Items Saved To Array");
                
                
            });

        });
        
        
    </script>
</body>
</html>