<?php
    session_start();
    include("../functions/functions.php");

    if(isset($_SESSION['authenticated']) && $_COOKIE['Username']) {

        $userName = $_COOKIE['Username'];
        $sessionID = session_id();
        $endpointUser = "http://droddy03.webhosting6.eeecs.qub.ac.uk/top500API/api.php?returnUser=true&userName=$userName";

        $resourceUser = file_get_contents($endpointUser, false, stream_context_create());

        $dataUser = json_decode($resourceUser, true);

        $sessionIDStored = $dataUser["sessionID"];

        if($sessionID != $sessionIDStored) {
            header("Location: logout.php");
            die();
        }

    } else {
        header("Location: logout.php");
        die();
    }
?>

<?php

    $userBio = $dataUser['biography'];
    $userPic = $dataUser['profile_picture'];

    if($userBio) {
        $userbiography = $userBio;
    } else {
        $userbiography = "No bio added yet";
    }

    if($userPic) {
        $userPicture = $userPic;
    } else {
        $userPicture = "../uploads/default_image.jpg";
    }

    $endpointFavGenres = "http://droddy03.webhosting6.eeecs.qub.ac.uk/top500API/api.php?favouriteGenres=true&userID={$dataUser['user_id']}";

    $resourceFavGenres = file_get_contents($endpointFavGenres, false, stream_context_create());

    $dataFavGenres = json_decode($resourceFavGenres, true);

?>  

<!DOCTYPE html>
<html lang="en">

<?php 
$pageTitle = "Profile";
require("../phpTemplates/header.php"); 
?>

<script>

        $(document).ready(function() {  
            $("#editBioInput").hide();
            $("#saveBioChanges").hide();
            $("#successBio").hide();

            $("#editBioIcon").click(function() {

                if($("#editBioIcon").hasClass("bioShown")){

                    $("#bioText").hide();
                    $("#edit-text").hide();
                    $("#editBioInput").show();
                    $("#saveBioChanges").show();
                    $("#editBioIcon").removeClass("bioShown");
                    
                    $("#bioIconSVG").removeClass("bi-pencil");
                    $("#bioIconSVG").addClass("bi-x-square-fill");

                    $("#bioIconPath").attr("d","M2 0a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2H2zm3.354 4.646L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 1 1 .708-.708z");

                } else {
                    $("#bioText").show();
                    $("#edit-text").show();
                    $("#editBioIcon").show();
                    $("#editBioInput").hide();
                    $("#saveBioChanges").hide();
                    $("#editBioIcon").addClass("bioShown");

                    $("#bioIconSVG").removeClass("bi-x-square-fill");
                    $("#bioIconSVG").addClass("bi-pencil");

                    $("#bioIconPath").attr("d","M12.146.146a.5.5 0 0 1 .708 0l3 3a.5.5 0 0 1 0 .708l-10 10a.5.5 0 0 1-.168.11l-5 2a.5.5 0 0 1-.65-.65l2-5a.5.5 0 0 1 .11-.168l10-10zM11.207 2.5 13.5 4.793 14.793 3.5 12.5 1.207 11.207 2.5zm1.586 3L10.5 3.207 4 9.707V10h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.293l6.5-6.5zm-9.761 5.175-.106.106-1.528 3.821 3.821-1.528.106-.106A.5.5 0 0 1 5 12.5V12h-.5a.5.5 0 0 1-.5-.5V11h-.5a.5.5 0 0 1-.468-.325z");
                }
                    
            });

            $('#saveBioChanges').click(function() {
                var bio=document.getElementById('editBioInput').value;
                
                $.ajax({
                url: '../process_files/bioUpdate.php',
                type: 'POST',
                data: jQuery.param({ biography: bio, username: <?php echo "\"$userName\""; ?>},) ,
                contentType: 'application/x-www-form-urlencoded; charset=UTF-8',
                success: function (response) {
                    // alert(response);
                    $('#editBioIcon').click();
                    $("#successBio").show();
                    $("#bioText").text(bio)
                    setTimeout(function() { $("#successBio").hide(); }, 2000);
                },
                error: function (xhr, status, error) {
                    var err = JSON.parse(xhr.responseText);
                    alert(err.Message);
                }
                }); 
            });

            function changeSelectionsFavAlbum(favouriteAlbum) {

                if(favouriteAlbum == false) {
                    $( "<option class='favAlbumsSearchedOptions' disabled>No albums with that name</option>" ).insertBefore("#returnedAlbumSelection");
                } else {
                    for (var album in favouriteAlbum) {
                        $( "<option class='favAlbumsSearchedOptions'>"+favouriteAlbum[album]['album_name']+"</option>" ).insertBefore("#returnedAlbumSelection");
                    }
                }
            };

            function changeSelectionsOwnedAlbum(ownedAlbum) {
                if(ownedAlbum == false) {
                    $( "<option class='ownedAlbumsSearchedOptions' disabled>No albums with that name</option>" ).insertBefore("#returnedOwnedAlbumSelection");
                } else {
                    for (var album in ownedAlbum) {
                        $( "<option class='ownedAlbumsSearchedOptions'>"+ownedAlbum[album]['album_name']+"</option>" ).insertBefore("#returnedOwnedAlbumSelection");
                    }
                    
                }
            };

            $('#userFavSearchButton').click(function(e) {
                
                var favAlbumSearch = $('#userFavSearch').val();
                $(".listAllAlbumsFav").hide();
                $(".favAlbumsSearchedOptions").hide();

                $.ajax({
                url: '../process_files/userFavSearchButton.php',
                type: 'POST',
                data: jQuery.param({ favAlbum: favAlbumSearch,},) ,
                contentType: 'application/x-www-form-urlencoded; charset=UTF-8',
                success: function (response) {
                    data = JSON.parse(response);
                    
                    changeSelectionsFavAlbum(data);

                },
                error: function () {
                    alert("error");
                }
                }); 
            });

            $('#showAllPotentialFavourites').click(function(e) {
                
                $(".listAllAlbumsFav").show();
                $(".favAlbumsSearchedOptions").hide();
            });

            $('#addAlbumToFavs').click(function(e) {
                
                var favAlbumReturned = $( "#favouriteAlbumSelect option:selected" ).text();

                $.ajax({
                url: '../process_files/addAlbumToFavs.php',
                type: 'POST',
                data: jQuery.param({ favAlbumReturn: favAlbumReturned, username: <?php echo "\"$userName\""; ?>},) ,
                contentType: 'application/x-www-form-urlencoded; charset=UTF-8',
                success: function (response) {

                    window.location.reload();

                },
                error: function () {
                    alert("error");
                    
                }
                }); 
            });

            $('#favAlbumEditButton').click(function(e) {

                    var albumFavEdit = $( "#FavAlbumEditSelect option:selected").val();

                    $.ajax({
                        url: '../process_files/favAlbumEditButton.php',
                        type: 'POST',
                        data: jQuery.param({ albumFavForEdit: albumFavEdit, username: <?php echo "\"$userName\""; ?>},) ,
                        contentType: 'application/x-www-form-urlencoded; charset=UTF-8',
                        success: function (response) {

                            window.location.reload();

                        },
                        error: function () {
                            alert("error");
                            
                        }
                    }); 
                
            });

            $('#userOwnedSearchButton').click(function(e) {
                
                var ownedAlbumSearch = $('#userOwnedSearch').val();
                $(".listAllAlbumsOwned").hide();
                $(".ownedAlbumsSearchedOptions").hide();

                $.ajax({
                url: '../process_files/userOwnedSearchButton.php',
                type: 'POST',
                data: jQuery.param({ ownedAlbum: ownedAlbumSearch, username: <?php echo "\"$userName\""; ?>},) ,
                contentType: 'application/x-www-form-urlencoded; charset=UTF-8',
                success: function (response) {
                    ownedAlbumReturned = JSON.parse(response);
                    
                    changeSelectionsOwnedAlbum(ownedAlbumReturned); 

                },
                error: function () {
                    alert("error");
                }
                }); 
            });

            $('#showAllPotentialOwned').click(function(e) {
                
                $(".listAllAlbumsOwned").show();
                $(".ownedAlbumsSearchedOptions").hide();
            });

            $('#addAlbumToOwned').click(function(e) {
                
                var ownedAlbumReturned = $('#potentialOwnedAlbumsSelect option:selected').text();
                
                $.ajax({
                url: '../process_files/addAlbumToOwned.php',
                type: 'POST',
                data: jQuery.param({ ownedAlbumToAdd: ownedAlbumReturned, username: <?php echo "\"$userName\""; ?>},) ,
                contentType: 'application/x-www-form-urlencoded; charset=UTF-8',
                success: function (response) {
                    window.location.reload();

                },
                error: function () {
                    alert("error");
                    
                }
                }); 
            });
            
            $('#ownedAlbumEditButton').click(function(e) {

                var albumOwnedEdit = $( "#ownedAlbumEditSelect option:selected").val();

                $.ajax({
                    url: '../process_files/ownedAlbumEdit.php',
                    type: 'POST',
                    data: jQuery.param({ albumOwnedForEdit: albumOwnedEdit, username: <?php echo "\"$userName\""; ?>},) ,
                    contentType: 'application/x-www-form-urlencoded; charset=UTF-8',
                    success: function (response) {

                        window.location.reload();

                    },
                    error: function () {
                        alert("error");
                        
                    }
                }); 
                
            });

            $("#updateSettingsButton").click(function(e) {
                var usernameEntered = $("#usernameChange").val();
                var emailEntered = $("#emailChange").val();
                var passwordEntered = $("#passwordChange").val();
                
                var usernameValidate = window.prompt("Enter your username","");
                var passwordValidate = window.prompt("Enter your password","");

                if((usernameValidate != false) && (passwordValidate != false)) {

                $.ajax({
                    url: '../process_files/signupvalidate.php',
                    type: 'POST',
                    data: jQuery.param({ usernameCheck: usernameEntered, emailCheck: emailEntered, passwordCheck: passwordEntered, usernameValidate: usernameValidate, passwordValidate: passwordValidate, profileUpdateValidate: true},) ,
                    contentType: 'application/x-www-form-urlencoded; charset=UTF-8',
                    success: function (response) {
                        result = jQuery.parseJSON(response)
                        if(result.userValidated != true) {
                            alert("Credentials incorrect. Try again.")
                        } else if(result.userExists == true) {
                            alert("Username already exists, please choose a different username");
                        } else if(result.emailExists == true) {
                            alert("Email already exists. Please login to account or choose a different email.")
                        } else {
                            alert("settings updated")
                            $('#updateSettingsButton').attr('data-dismiss', 'modal');
                            $('#profileSettingsForm').submit();
                        }
                    },
                    error: function () {
                        alert("error");
                    }
                    });
                } else {
                    alert("Please enter details.");
                }
                e.preventDefault();
            
            
            });

            $("#deleteAccountButton").click(function(e) {

                if (window.confirm("Are you sure you want to delete your account?")) {
                
                    $.ajax({
                        url: '../process_files/deleteAccount.php',
                        type: 'POST',
                        data: jQuery.param({ usernameToDelete: <?php echo "\"$userName\""; ?>},) ,
                        contentType: 'application/x-www-form-urlencoded; charset=UTF-8',
                        success: function (response) {
                            alert("Account Deleted.");
                            del_cookie('Username');
                            del_cookie('LoggedIn');
                            window.location.assign("index.php");
                        },
                        error: function () {
                            alert("error");
                        }
                        });
                    e.preventDefault();
                    
                } else {
                    window.location.reload();
                }
            
            
            });

            $("#viewReviewsButton").click(function(e) {

                window.location.assign("userReviews.php");
            });

            $("#profilePicture").click(function(e){
                e.preventDefault();
                $("#upload").trigger('click');

                document.getElementById("upload").onchange = function() {
                    document.getElementById("profileSettingsForm").submit();
                };
            });


        });

        
        
</script>

<body>
    <?php 
    $navItem1 = "Home";
    $navItem1Link = "index.php";

    $navItem2 = "Album List";
    $navItem2Link = "albumList.php";

    if(isset($_COOKIE["Username"])){
                
            $navItem3 = "Profile Page <span class='sr-only'>(current)</span>";
            $navItem3Active = "active";
            $navItem3Link = "profile.php";

            $navItem5 = "Log Out";
            $navItem5Link = "logout.php";
    }

    require("../phpTemplates/navbar.php"); 
    ?>

    <!-- ************** CONTAINER ************* -->
    <div class="container" id="profileH1">

        <!-- HEADER -->
        <h1 class="text-center"><?php echo $userName ?> Profile</h1>

        <!-- IMAGE -->
        <div class="row justify-content-center mb-4" style="cursor: pointer;">
            <img src="<?php echo $userPicture; ?>" alt="Please upload a picture!" height="500" width="150" class="d-block w-50 rounded-circle" id="profilePicture">
        </div>

        <!-- BIO HEADER -->
        <div class="row justify-content-center">
            <h2 class="col-4 border-bottom text-center"><?php echo $userName ?> bio</h2>
        </div>

        <!-- BIO TEXT CONTAINER -->
        <div class="row mt-2 mb-5 justify-content-center">
            <div class="col-10">

                <!-- BIO TEXT P  -->
                <p class="text-center">

                    <!-- BIO TEXT -->
                    <span class="text-justify justify-content-center" id="bioText">
                        <?php echo "$userbiography"; ?>
                        <?php 
                            $counter = 0;
                            echo nl2br("\r\n");
                            echo nl2br("\r\n");
                            echo $userName."'s fav genres: ";
                            while($dataFavGenres != false) {
                                $row = array_shift($dataFavGenres);
                                echo $row['genre_name'];
                                echo ", ";

                                if(($counter==4 && $counter>0) || ($counter%4==0 && $counter>0)) {
                                    echo nl2br("\r\n");
                                }

                                $counter++;
                            }
                        ?>

                    </span>

                    <span class="text-center mx-auto" >

                        <!-- BIO INPUT -->
                        <input type="text" class="" placeholder='Enter your bio...' id="editBioInput" name="biography">
                        
                        <!-- SUBMIT BUTTON -->
                        <button type="submit" class="btn btn-secondary" id="saveBioChanges" value="Submit">Save Changes</button>
                    </span>

                    <!-- ICON + EDIT -->
                    <span id="editBioIcon" class='bioShown editDiv'>
                        <span class="editDiv m-3" id="edit-text">Edit</span>
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pencil fa-lg editDiv" viewBox="0 0 16 16" id="bioIconSVG">
                            <path d="M12.146.146a.5.5 0 0 1 .708 0l3 3a.5.5 0 0 1 0 .708l-10 10a.5.5 0 0 1-.168.11l-5 2a.5.5 0 0 1-.65-.65l2-5a.5.5 0 0 1 .11-.168l10-10zM11.207 2.5 13.5 4.793 14.793 3.5 12.5 1.207 11.207 2.5zm1.586 3L10.5 3.207 4 9.707V10h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.293l6.5-6.5zm-9.761 5.175-.106.106-1.528 3.821 3.821-1.528.106-.106A.5.5 0 0 1 5 12.5V12h-.5a.5.5 0 0 1-.5-.5V11h-.5a.5.5 0 0 1-.468-.325z" id="bioIconPath"/>
                        </svg>
                    </span>

                    <!-- SUCCESS -->
                    <p id="successBio" class="h4 text-white bg-secondary text-center inline-block w-25 mx-auto rounded-pill">Bio Saved!</p>
                </p>
            </div>
        </div>

        <!-- FAV + OWNED ALBUMS HEADER-->
        <div class="row justify-content-between">

            <!-- FAV HEADER -->
            <h2 class="col-4 border-bottom text-center">Favourite Albums

                <!-- Button trigger modal -->
                <button type="button" class="btn btn-dark" data-toggle="modal" data-target="#favouritesModal" id="favAlbumsModalButton">
                    <!-- FAV ICON + EDIT -->
                    <span id="editBioIconTwo" class=''>
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pencil float-right mt-2 fa-lg" viewBox="0 0 16 16" id="bioIconSVGTwo">
                            <path d="M12.146.146a.5.5 0 0 1 .708 0l3 3a.5.5 0 0 1 0 .708l-10 10a.5.5 0 0 1-.168.11l-5 2a.5.5 0 0 1-.65-.65l2-5a.5.5 0 0 1 .11-.168l10-10zM11.207 2.5 13.5 4.793 14.793 3.5 12.5 1.207 11.207 2.5zm1.586 3L10.5 3.207 4 9.707V10h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.293l6.5-6.5zm-9.761 5.175-.106.106-1.528 3.821 3.821-1.528.106-.106A.5.5 0 0 1 5 12.5V12h-.5a.5.5 0 0 1-.5-.5V11h-.5a.5.5 0 0 1-.468-.325z" id="bioIconPathTwo"/>
                        </svg>
                    </span>
                </button>

                <!-- Modal -->
                <div class="modal fade" id="favouritesModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">

                    <!-- MODAL DIALOGUE -->
                    <div class="modal-dialog modal-dialog-centered">

                        <!-- MODAL CONTENT -->
                        <div class="modal-content">

                            <!-- MODAL HEADER DIV -->
                            <div class="modal-header">

                                <!-- MODAL HEADER TXT -->
                                <h5 class="modal-title text-dark" id="exampleModalLabel"><?php echo "$userName" ?> Favourite Albums </h5>

                                <!-- MODAL BUTTON (EXIT) -->
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                                </button>
                            </div>

                            <!-- MODAL BODY -->
                            <div class="modal-body mx-auto">

                                <!-- MODAL FORM -->
                                <form class="form-inline" id="favAlbumForm">

                                    <!-- USER SEARCH -->
                                    <p class="h4 text-center text-dark">Search for new favourites</p>
                                    <input class="form-control mr-sm-2" type="text" placeholder="Search" aria-label="Search" id="userFavSearch"> 

                                    <!-- USER SUBMIT -->
                                    <button class="btn btn-outline-success my-2 my-sm-0" type="button" id="userFavSearchButton">Search</button> 
                                    <button class="btn btn-outline-success my-2 my-sm-0 ml-3" type="button" id="showAllPotentialFavourites">Show all</button> 
                                </form>

                                <div class="form-group mt-5">
                                        <select multiple class="form-control" id="favouriteAlbumSelect">
                                            <?php
                                                $endpointAll = "http://droddy03.webhosting6.eeecs.qub.ac.uk/top500API/api.php";

                                                $resourceAll = file_get_contents($endpointAll);

                                                $dataAll = json_decode($resourceAll, true);

                                                while($dataAll != false){


                                                    $row = array_shift($dataAll);
                                                    $albumName = $row['album_name'];

                                                    echo "<option class='listAllAlbumsFav'>$albumName</option>";
                                                }
                                                $albumName = null;
                                            ?>
                                            <option id="returnedAlbumSelection"></option>
                                        </select>
                                </div>

                                <div class="form-group mt-5">
                                        <p class="text-dark h4">Current favourites</p>
                                        <select multiple class="form-control" id="FavAlbumEditSelect">
                                            <?php 
                                            showUserFavouriteAlbums($userName,"option");
                                            ?>
                                        </select>
                                </div>

                            </div>

                            <!-- MODAL FOOTER -->
                            <div class="modal-footer">

                                <!-- EDIT BUTTON -->
                                <div class="mr-auto">
                                    <button type="button" class="btn btn-secondary pull-left" id="favAlbumEditButton">Delete from Favourites</button>
                                </div>

                                <!-- CLOSE BUTTON -->
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>

                                <!-- SAVE BUTTON -->
                                <button type="submit" class="btn btn-primary" data-dismiss="modal" id="addAlbumToFavs">Add album</button>
                            </div>
                        </div>
                    </div>
                </div>
                
            </h2>
                
            <!-- OWNED HEADER -->
            <h2 class="col-4 border-bottom text-center">Owned Albums

                <!-- Button trigger modal -->
                <button type="button" class="btn btn-dark" data-toggle="modal" data-target="#ownedModal" id="favAlbumsModalButton">
                    <!-- FAV ICON + EDIT -->
                    <span id="editBioIconTwo" class=''>
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pencil float-right mt-2 fa-lg" viewBox="0 0 16 16" id="bioIconSVGTwo">
                            <path d="M12.146.146a.5.5 0 0 1 .708 0l3 3a.5.5 0 0 1 0 .708l-10 10a.5.5 0 0 1-.168.11l-5 2a.5.5 0 0 1-.65-.65l2-5a.5.5 0 0 1 .11-.168l10-10zM11.207 2.5 13.5 4.793 14.793 3.5 12.5 1.207 11.207 2.5zm1.586 3L10.5 3.207 4 9.707V10h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.293l6.5-6.5zm-9.761 5.175-.106.106-1.528 3.821 3.821-1.528.106-.106A.5.5 0 0 1 5 12.5V12h-.5a.5.5 0 0 1-.5-.5V11h-.5a.5.5 0 0 1-.468-.325z" id="bioIconPathTwo"/>
                        </svg>
                    </span>
                </button>

                <!-- Modal -->
                <div class="modal fade" id="ownedModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">

                    <!-- MODAL DIALOGUE -->
                    <div class="modal-dialog modal-dialog-centered">

                        <!-- MODAL CONTENT -->
                        <div class="modal-content">

                            <!-- MODAL HEADER DIV -->
                            <div class="modal-header">

                                <!-- MODAL HEADER TXT -->
                                <h5 class="modal-title text-dark" id="exampleModalLabel"><?php echo "$userName" ?> Owned Albums </h5>

                                <!-- MODAL BUTTON (EXIT) -->
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                                </button>
                            </div>

                            <!-- MODAL BODY -->
                            <div class="modal-body mx-auto">
                                
                                <!-- MODAL FORM -->
                                <form class="form-inline" id="ownedAlbumForm">
                                    <!-- USER SEARCH -->
                                    <p class="h4 text-center text-dark">Search for albums you own</p>
                                    <input class="form-control mr-sm-2" type="text" placeholder="Search" aria-label="Search" id="userOwnedSearch"> 

                                    <!-- USER SUBMIT -->
                                    <button class="btn btn-outline-success my-2 my-sm-0" type="button" id="userOwnedSearchButton">Search</button> 
                                    <button class="btn btn-outline-success my-2 my-sm-0 ml-3" type="button" id="showAllPotentialOwned">Show All</button> 
                                </form>

                                <div class="form-group mt-5">
                                        <select multiple class="form-control" id="potentialOwnedAlbumsSelect">
                                            <?php
                                                $endpointAll = "http://droddy03.webhosting6.eeecs.qub.ac.uk/top500API/api.php";

                                                $resourceAll = file_get_contents($endpointAll);

                                                $dataAll = json_decode($resourceAll, true);

                                                while($dataAll != false){


                                                    $row = array_shift($dataAll);
                                                    $albumName = $row['album_name'];

                                                    echo "<option class='listAllAlbumsOwned'>$albumName</option>";
                                                }
                                            ?>
                                            <option disabled id="returnedOwnedAlbumSelection"></option>
                                        </select>
                                </div>

                                <div class="form-group mt-5">
                                        <p class="text-dark h4">Current owned list</p>
                                        <select multiple class="form-control" id="ownedAlbumEditSelect">
                                            <?php 
                                            showUserOwnedAlbums($userName,"option");
                                            ?>
                                        </select>
                                </div>

                            </div>

                            <!-- MODAL FOOTER -->
                            <div class="modal-footer">

                                <!-- EDIT BUTTON -->
                                <div class="mr-auto">
                                    <button type="button" class="btn btn-secondary pull-left" id="ownedAlbumEditButton">Delete from owned</button>
                                </div>

                                <!-- CLOSE BUTTON -->
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>

                                <!-- SAVE BUTTON -->
                                <button type="submit" class="btn btn-primary" data-dismiss="modal" id="addAlbumToOwned">Add album</button>
                            </div>
                        </div>
                    </div>
                </div>
            </h2>
        </div>

        <!-- FAV + OWNED ALBUMS CONTENT -->
        <div class="row justify-content-between">

            <!-- FAVOURITE ALBUMS -->
            <?php 
                echo "<ul class='col-4'>";
                    showUserFavouriteAlbums($userName, "li");
                echo "</ul>";   
            ?>
            

            <!-- OWNED ALBUMS -->
            <?php
                echo "<ul class='col-4'>";
                    showUserOwnedAlbums($userName, "li");
                echo "</ul>";   
            ?>
        </div>
    
        <!-- Button trigger modal -->
        
        <div class="text-center mt-5">
            <button type="button" class="btn btn-dark" data-toggle="modal" data-target="#SettingsModal" id="SettingsModalButton">
                <h3>Settings</h3>
                <svg xmlns="http://www.w3.org/2000/svg" width="50" height="50" fill="white" class="bi bi-gear" viewBox="0 0 16 16">
                    <path d="M8 4.754a3.246 3.246 0 1 0 0 6.492 3.246 3.246 0 0 0 0-6.492zM5.754 8a2.246 2.246 0 1 1 4.492 0 2.246 2.246 0 0 1-4.492 0z"/>
                    <path d="M9.796 1.343c-.527-1.79-3.065-1.79-3.592 0l-.094.319a.873.873 0 0 1-1.255.52l-.292-.16c-1.64-.892-3.433.902-2.54 2.541l.159.292a.873.873 0 0 1-.52 1.255l-.319.094c-1.79.527-1.79 3.065 0 3.592l.319.094a.873.873 0 0 1 .52 1.255l-.16.292c-.892 1.64.901 3.434 2.541 2.54l.292-.159a.873.873 0 0 1 1.255.52l.094.319c.527 1.79 3.065 1.79 3.592 0l.094-.319a.873.873 0 0 1 1.255-.52l.292.16c1.64.893 3.434-.902 2.54-2.541l-.159-.292a.873.873 0 0 1 .52-1.255l.319-.094c1.79-.527 1.79-3.065 0-3.592l-.319-.094a.873.873 0 0 1-.52-1.255l.16-.292c.893-1.64-.902-3.433-2.541-2.54l-.292.159a.873.873 0 0 1-1.255-.52l-.094-.319zm-2.633.283c.246-.835 1.428-.835 1.674 0l.094.319a1.873 1.873 0 0 0 2.693 1.115l.291-.16c.764-.415 1.6.42 1.184 1.185l-.159.292a1.873 1.873 0 0 0 1.116 2.692l.318.094c.835.246.835 1.428 0 1.674l-.319.094a1.873 1.873 0 0 0-1.115 2.693l.16.291c.415.764-.42 1.6-1.185 1.184l-.291-.159a1.873 1.873 0 0 0-2.693 1.116l-.094.318c-.246.835-1.428.835-1.674 0l-.094-.319a1.873 1.873 0 0 0-2.692-1.115l-.292.16c-.764.415-1.6-.42-1.184-1.185l.159-.291A1.873 1.873 0 0 0 1.945 8.93l-.319-.094c-.835-.246-.835-1.428 0-1.674l.319-.094A1.873 1.873 0 0 0 3.06 4.377l-.16-.292c-.415-.764.42-1.6 1.185-1.184l.292.159a1.873 1.873 0 0 0 2.692-1.115l.094-.319z"/>
                </svg>
            </button>
        </div>
        

        <!-- Modal -->
        <div class="modal fade" id="SettingsModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">

            <!-- MODAL DIALOGUE -->
            <div class="modal-dialog modal-dialog-centered">

                <!-- MODAL CONTENT -->
                <div class="modal-content">

                    <!-- MODAL HEADER DIV -->
                    <div class="modal-header">

                        <!-- MODAL HEADER TXT -->
                        <h5 class="modal-title text-dark" id="exampleModalLabel"><?php echo "$userName" ?> Settings </h5>

                        <!-- MODAL BUTTON (EXIT) -->
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        </button>
                    </div>

                    <!-- MODAL FORM -->
                    <form class="form-inline text-center" role="form" id="profileSettingsForm" action="updateDetails.php" method="POST" enctype="multipart/form-data">
                        <!-- MODAL BODY -->
                        <div class="modal-body mx-auto">
                            
                            
                                <!-- USERNAME CHANGE -->
                                <p class="h5 text-center text-dark w-50 d-inline">Change username: </p>
                                <input class="form-control mx-auto w-50 d-inline" type="text" placeholder="enter desired username..." aria-label="Search" id="usernameChange" name="newUsername"> 
                                <br>

                                <!-- EMAIL CHANGE -->
                                <p class="h5 text-center text-dark w-50 mt-2 d-inline ml-2">Change email: </p>
                                <input class="form-control mx-auto w-50 mt-2 d-inline" type="email" placeholder="enter desired email..." aria-label="Search" id="emailChange" name="newEmail"> 
                                <br>

                                <!-- PASSWORD CHANGE -->
                                <p class="h5 text-center text-dark w-50 mt-2 d-inline">Change password: </p>
                                <input class="form-control mx-auto w-50 mt-2 d-inline" type="password" placeholder="enter new password..." aria-label="Search" id="passwordChange" name="newPassword"> 
                                <br>

                                <label for="upload" class="h5 text-center text-dark w-50 mt-2 d-inline" id="formInput4">Change Display Pic: </label>
                                <input type="file" class="mx-auto w-50 mt-2 d-inline" id="upload" onChange="" name="profilepic" accept="image/*">
                                <br>

                                <label for="favGenre" class="h5 text-center text-dark w-50 mt-2 d-inline">Favourite Genres: </label>
                                <select class="selectpicker mx-auto w-50 mt-2 d-inline" multiple="multiple" name="genre[]" id="formInput5">
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


                                <p class="h5 text-dark mt-4">Leave empty any fields you wish to remain unchanged</p>

                                <div class="text-center mt-3">
                                    <button type="button" class="btn btn-warning" id="deleteAccountButton">
                                        <h5>Delete Account</h5>
                                        <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" fill="red" class="bi bi-x-octagon-fill" viewBox="0 0 16 16">
                                            <path d="M11.46.146A.5.5 0 0 0 11.107 0H4.893a.5.5 0 0 0-.353.146L.146 4.54A.5.5 0 0 0 0 4.893v6.214a.5.5 0 0 0 .146.353l4.394 4.394a.5.5 0 0 0 .353.146h6.214a.5.5 0 0 0 .353-.146l4.394-4.394a.5.5 0 0 0 .146-.353V4.893a.5.5 0 0 0-.146-.353L11.46.146zm-6.106 4.5L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 1 1 .708-.708z"/>
                                        </svg>
                                    </button>
                                </div>
                        </div>

                        <!-- MODAL FOOTER -->
                        <div class="modal-footer">

                            <div class="mr-auto">
                                <!-- CLOSE BUTTON -->
                                <button type="button" class="btn btn-secondary pull-left" data-dismiss="modal">Close</button>
                            </div>

                            <!-- SAVE BUTTON -->
                            <button type="submit" class="btn btn-primary" id="updateSettingsButton">Save changes</button>
                        </div>
                    </form>
                </div>
            </div>


            
        </div>

        <div class="text-center mt-5">
            <button type="button" class="btn btn-dark" id="viewReviewsButton">
                <h3>View Your Comments</h3>
                <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" fill="currentColor" class="bi bi-book" viewBox="0 0 16 16">
                    <path d="M1 2.828c.885-.37 2.154-.769 3.388-.893 1.33-.134 2.458.063 3.112.752v9.746c-.935-.53-2.12-.603-3.213-.493-1.18.12-2.37.461-3.287.811V2.828zm7.5-.141c.654-.689 1.782-.886 3.112-.752 1.234.124 2.503.523 3.388.893v9.923c-.918-.35-2.107-.692-3.287-.81-1.094-.111-2.278-.039-3.213.492V2.687zM8 1.783C7.015.936 5.587.81 4.287.94c-1.514.153-3.042.672-3.994 1.105A.5.5 0 0 0 0 2.5v11a.5.5 0 0 0 .707.455c.882-.4 2.303-.881 3.68-1.02 1.409-.142 2.59.087 3.223.877a.5.5 0 0 0 .78 0c.633-.79 1.814-1.019 3.222-.877 1.378.139 2.8.62 3.681 1.02A.5.5 0 0 0 16 13.5v-11a.5.5 0 0 0-.293-.455c-.952-.433-2.48-.952-3.994-1.105C10.413.809 8.985.936 8 1.783z"/>
                </svg>
            </button>
        </div>
    </div>
    <?php require("../phpTemplates/footer.php"); ?>

</body>

</html>