<?php
    session_start();
    if (!isset($_SERVER['PHP_AUTH_USER'])) {
        header('WWW-Authenticate: Basic realm="My Realm"');
        header('HTTP/1.0 401 Unauthorized');
        echo 'Credentials required. Please log in to access the admin panel.';
            exit;
    } else if((isset($_SERVER['PHP_AUTH_USER'])) && ($_SERVER['PHP_AUTH_PW'] != "secretPassword")) {
        header('WWW-Authenticate: Basic realm="My Realm"');
        header('HTTP/1.0 401 Unauthorized');
        echo 'Incorrect credentials.';
            exit;
    }
    
    
    if(isset($_SESSION['authenticated']) && $_COOKIE['Username']) {

        $userName = $_COOKIE['Username'];
        $sessionID = session_id();
        $endpointUser = "http://droddy03.webhosting6.eeecs.qub.ac.uk/top500API/api.php?returnUser=true&userName=$userName";

        $resourceUser = file_get_contents($endpointUser, false, stream_context_create());

        $dataUser = json_decode($resourceUser, true);

        $sessionIDStored = $dataUser["sessionID"];
        $userRole = (int)$dataUser["user_role_id"];

        if(($sessionID !== $sessionIDStored) || ($userRole !== 2)) {
            
            header('HTTP/1.0 401 Unauthorized');
            echo 'User does not have admin privileges';
            exit;
        }

        $navItem1 = "Home";
        $navItem1Link = "index.php";

        $navItem2 = "Album List";
        $navItem2Link = "albumList.php";
        
        $navItem3 = "Profile Page";
        $navItem3Link = "profile.php";

        $navItem4 = "Admin Panel <span class='sr-only'>(current)</span>";
        $navItem4Active = "active";
        $navItem4Link = "adminPage.php";

        $navItem5 = "Log Out";
        $navItem5Link = "logout.php";

    } else {
        header("Location: logout.php");
        die();
    }
    include("../functions/functions.php");
    // Albums
    $endpointAll = "http://droddy03.webhosting6.eeecs.qub.ac.uk/top500API/api.php";

    $resourceAll = file_get_contents($endpointAll);

    $dataAll = json_decode($resourceAll, true);

    // UserAccounts
    $endpointAccounts = "http://droddy03.webhosting6.eeecs.qub.ac.uk/top500API/api.php?userAccounts";

    $resourceAccounts = file_get_contents($endpointAccounts);

    $dataAccounts = json_decode($resourceAccounts, true);

    // Comments
    $endpointComments = "http://droddy03.webhosting6.eeecs.qub.ac.uk/top500API/api.php?pendingComments";

    $resource = file_get_contents($endpointComments, false, stream_context_create());

    $commentData = json_decode($resource, true);
?>

<!DOCTYPE html>

<?php 
$pageTitle = "Admin Page";
require("../phpTemplates/header.php"); 
?>



<body>

    <?php require("../phpTemplates/navbar.php"); ?>

    <h1 id="albumListHeaderOne" class="text-center">Admin Panel</h1>

    <h3 class="text-center mt-5">Add new album information</h3>

    <button class=" d-block mx-auto mt-2 mb-5"  data-toggle='modal' data-target='#addAlbumModal' id='addAlbumModalButton'>
        <svg xmlns="http://www.w3.org/2000/svg" width="45" height="45" fill="currentColor" class="bi bi-plus-square" viewBox="0 0 16 16">
            <path d="M14 1a1 1 0 0 1 1 1v12a1 1 0 0 1-1 1H2a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1h12zM2 0a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2H2z"/>
            <path d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4z"/>
        </svg>
    </button>
    
    <!-- Modal -->
    <div class='modal fade' id='addAlbumModal' tabindex='-1' aria-labelledby='exampleModalLabel' aria-hidden='true'>

        <!-- MODAL DIALOGUE -->
        <div class='modal-dialog modal-dialog-centered'>

            <!-- MODAL CONTENT -->
            <div class='modal-content'>

                <!-- MODAL HEADER DIV -->
                <div class='modal-header'>

                    <!-- MODAL HEADER TXT -->
                    <h5 class='modal-title text-dark' id='exampleModalLabel'>Add Album Information</h5>

                    <!-- MODAL BUTTON (EXIT) -->
                    <button type='button' class='close' data-dismiss='modal' aria-label='Close'>
                    <span aria-hidden='true'>&times;</span>
                    </button>
                </div>
                <div class="container-fluid">
                    <!-- MODAL FORM -->
                    <form class='text-center text-dark' role='form' id='addAlbumForm' action='addAlbum.php' method='POST'>
                        <!-- MODAL BODY -->
                        <div class='modal-body mx-auto form-row'>
                            
                            <div class="form-group mx-auto">
                            <label for="newAlbumName" class="d-inline-block w-25">Album name: </label>
                            <input type='text' name='newAlbumName' id='newAlbumName' value=''>
                            </div>

                            <div class="form-group mx-auto">
                            <label for="newAlbumArtistName" class="d-inline-block w-25">Artist name: </label>
                            <input type='text' name='newAlbumArtistName' id='newAlbumArtistName' value=''>
                            </div>

                            <div class="form-group mx-auto">
                            <label for="newAlbumYear" class="d-inline-block w-25">Album year: </label>
                            <input type='text' name='newAlbumYear' id='newAlbumYear' value=''>
                            </div>

                            <div class="form-group mx-auto">
                            <label for="newAlbumRank" class="d-inline-block w-25">Album rank: </label>
                            <input type='text' name='newAlbumRank' id='newAlbumRank' value=''>
                            </div>

                            <p class="text-dark mx-auto">Input fields below are optional </p>
                            
                            <div class="form-group mx-auto">
                            <label for="albumImgUrl" class="d-inline-block w-25">Album img URL: </label>
                            <input type='text' name='albumImgUrl' id='albumImgUrl' value=''>
                            </div>
                            
                            <div class="form-group mx-auto">
                            <label for="albumPreviewUrl" class="d-inline-block w-25">Album preview URL: </label>
                            <input type='text' name='albumPreviewUrl' id='albumPreviewUrl' value=''>
                            </div>

                            <div class="form-group mx-auto">
                            <label for="albumCollectionViewUrl" class="d-inline-block w-25">Album collection view URL: </label>
                            <input type='text' name='albumCollectionViewUrl' id='albumCollectionViewUrl' value=''>
                            </div>  

                            <div class="form-group mx-auto">
                            <label for="albumCollectionPrice" class="d-inline-block w-25">Album collection price: </label>
                            <input type='text' name='albumCollectionPrice' id='albumCollectionPrice' value=''>
                            </div>

                            <div class="form-group mx-auto">
                            <label for="spotifyImage" class="d-inline-block w-25">Spotify Image URL: </label>
                            <input type='text' name='spotifyImage' id='spotifyImage' value=''>
                            </div>
                        </div>

                        <!-- MODAL FOOTER -->
                        <div class='modal-footer'>

                            <div class='mr-auto'>
                                <!-- CLOSE BUTTON -->
                                <button type='button' class='btn btn-secondary pull-left' data-dismiss='modal'>Close</button>
                            </div>

                            <!-- SAVE BUTTON -->
                            <button type='submit' class='btn btn-primary' id='updateCommentButton$i'>Save changes</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <h3 class="text-center mt-5">Modify/delete album information</h3>

    <button class=" d-block mx-auto mt-2 mb-5"  data-toggle='modal' data-target='#modifyAlbumModal' id='modifyAlbumModalButton'>
        <svg xmlns="http://www.w3.org/2000/svg" width="45" height="45" fill="currentColor" class="bi bi-pencil" viewBox="0 0 16 16">
            <path d="M12.146.146a.5.5 0 0 1 .708 0l3 3a.5.5 0 0 1 0 .708l-10 10a.5.5 0 0 1-.168.11l-5 2a.5.5 0 0 1-.65-.65l2-5a.5.5 0 0 1 .11-.168l10-10zM11.207 2.5 13.5 4.793 14.793 3.5 12.5 1.207 11.207 2.5zm1.586 3L10.5 3.207 4 9.707V10h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.293l6.5-6.5zm-9.761 5.175-.106.106-1.528 3.821 3.821-1.528.106-.106A.5.5 0 0 1 5 12.5V12h-.5a.5.5 0 0 1-.5-.5V11h-.5a.5.5 0 0 1-.468-.325z"/>
        </svg>
    </button>
    
    <!-- Modal -->
    <div class='modal fade' id='modifyAlbumModal' tabindex='-1' aria-labelledby='exampleModalLabel' aria-hidden='true'>

        <!-- MODAL DIALOGUE -->
        <div class='modal-dialog modal-dialog-centered'>

            <!-- MODAL CONTENT -->
            <div class='modal-content'>

                <!-- MODAL HEADER DIV -->
                <div class='modal-header'>

                    <!-- MODAL HEADER TXT -->
                    <h5 class='modal-title text-dark' id='exampleModalLabel'>Modify Album Information</h5>

                    <!-- MODAL BUTTON (EXIT) -->
                    <button type='button' class='close' data-dismiss='modal' aria-label='Close'>
                    <span aria-hidden='true'>&times;</span>
                    </button>
                </div>
                <div class="container-fluid">
                    <!-- MODAL FORM -->
                    <form class='text-center text-dark' role='form' id='modifyAlbumForm' action='modifyAlbum.php' method='POST'>
                        <!-- MODAL BODY -->
                        <div class='modal-body mx-auto form-row'>
                            <select class="w-75" name="albumToModify">
                        <?php 
                            while($dataAll != false) {
                                $row = array_shift($dataAll);

                                $albumName = $row['album_name'];
                                
                                echo
                                "
                                    <option value='{$row['album_id']}'>$albumName</option>
                                ";
                            }
                        ?>
                            </select>

                        </div>

                        <!-- MODAL FOOTER -->
                        <div class='modal-footer'>

                            <div class='mr-auto'>
                                <!-- CLOSE BUTTON -->
                                <button type='button' class='btn btn-secondary pull-left' data-dismiss='modal'>Close</button>
                            </div>

                            <!-- SAVE BUTTON -->
                            <button type='submit' class='btn btn-primary' id='updateCommentButton$i'>Select for change</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <h3 class="text-center mt-5">Modify/delete user accounts</h3>

    <button class=" d-block mx-auto mt-2 mb-5"  data-toggle='modal' data-target='#modifyUserAccountModal' id='modifyUserAccountModalButton'>
        <svg xmlns="http://www.w3.org/2000/svg" width="45" height="45" fill="currentColor" class="bi bi-pencil" viewBox="0 0 16 16">
            <path d="M12.146.146a.5.5 0 0 1 .708 0l3 3a.5.5 0 0 1 0 .708l-10 10a.5.5 0 0 1-.168.11l-5 2a.5.5 0 0 1-.65-.65l2-5a.5.5 0 0 1 .11-.168l10-10zM11.207 2.5 13.5 4.793 14.793 3.5 12.5 1.207 11.207 2.5zm1.586 3L10.5 3.207 4 9.707V10h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.293l6.5-6.5zm-9.761 5.175-.106.106-1.528 3.821 3.821-1.528.106-.106A.5.5 0 0 1 5 12.5V12h-.5a.5.5 0 0 1-.5-.5V11h-.5a.5.5 0 0 1-.468-.325z"/>
        </svg>
    </button>
    
    <!-- Modal -->
    <div class='modal fade' id='modifyUserAccountModal' tabindex='-1' aria-labelledby='exampleModalLabel' aria-hidden='true'>

        <!-- MODAL DIALOGUE -->
        <div class='modal-dialog modal-dialog-centered'>

            <!-- MODAL CONTENT -->
            <div class='modal-content'>

                <!-- MODAL HEADER DIV -->
                <div class='modal-header'>

                    <!-- MODAL HEADER TXT -->
                    <h5 class='modal-title text-dark' id='exampleModalLabel'>Modify User Account Information</h5>

                    <!-- MODAL BUTTON (EXIT) -->
                    <button type='button' class='close' data-dismiss='modal' aria-label='Close'>
                    <span aria-hidden='true'>&times;</span>
                    </button>
                </div>
                <div class="container-fluid">
                    <!-- MODAL FORM -->
                    <form class='text-center text-dark' role='form' id='modifyAccountForm' action='modifyAccount.php' method='POST'>
                        <!-- MODAL BODY -->
                        <div class='modal-body mx-auto form-row'>
                            <select class="w-75" name="userToModify">
                        <?php 
                            while($dataAccounts != false) {
                                $row = array_shift($dataAccounts);

                                $userName = $row['username'];
                                
                                echo
                                "
                                    <option value='{$row['user_id']}'>$userName</option>
                                ";
                            }
                        ?>
                            </select>

                        </div>

                        <!-- MODAL FOOTER -->
                        <div class='modal-footer'>

                            <div class='mr-auto'>
                                <!-- CLOSE BUTTON -->
                                <button type='button' class='btn btn-secondary pull-left' data-dismiss='modal'>Close</button>
                            </div>

                            <!-- SAVE BUTTON -->
                            <button type='submit' class='btn btn-primary' id='updateCommentButton$i'>Select for change</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <h3 class="text-center mt-5">Delete user comments</h3>
    
    <a href="adminCommentReviewPage.php">
        <button class="d-block mx-auto mt-2 mb-5">
            <svg xmlns="http://www.w3.org/2000/svg" width="45" height="45" fill="currentColor" class="bi bi-pencil" viewBox="0 0 16 16">
                <path d="M12.146.146a.5.5 0 0 1 .708 0l3 3a.5.5 0 0 1 0 .708l-10 10a.5.5 0 0 1-.168.11l-5 2a.5.5 0 0 1-.65-.65l2-5a.5.5 0 0 1 .11-.168l10-10zM11.207 2.5 13.5 4.793 14.793 3.5 12.5 1.207 11.207 2.5zm1.586 3L10.5 3.207 4 9.707V10h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.293l6.5-6.5zm-9.761 5.175-.106.106-1.528 3.821 3.821-1.528.106-.106A.5.5 0 0 1 5 12.5V12h-.5a.5.5 0 0 1-.5-.5V11h-.5a.5.5 0 0 1-.468-.325z"/>
            </svg>
        </button>
    </a>


    <?php
        echo "<h3 class='text-center'>Approve or reject these comments</h3>";
    ?>

    <!-- COMMENT SECTION -->
    <div class="container mt-3 mb-5">

        <!-- ROW -->
        <div class="d-flex justify-content-center row">

            <!-- COLUMN -->
            <div class="d-flex flex-column col-md-8">

                <!-- COMMENT -->
                <div class="coment-bottom bg-dark p-2 px-4">

                        <!-- OTHER USER COMMENTS -->
                        <?php
                        $i = 0; 
                        
                        $commentArrayForCheck = array();

                        if(!empty($commentData)) {
                            while($commentData != false) {

                                $row = array_shift($commentData);
                                
                                $count = strval($i);

                                $commentArrayForCheck[] = $row;

                                $commentTime = date('M j Y g:i A', strtotime($row['comment_time']));
                                
                                    echo "
                                    <div class='d-flex flex-row align-items-center commented-user' id='userCommentDetails$i'>
                                        <h5 class='mr-2'>{$row['username']}</h5>
                                        <span class='dot mb-1'></span>
                                        <span class='mb-1 ml-2'>album: {$row['album_name']}, comment time: </span>
                                        <span class='mb-1 ml-2'>$commentTime</span>
                                    </div>

                                    <div class='comment-text-sm mb-5' id='userComment$i'>
                                        <span>
                                            {$row['user_review']}
                                        </span>
                                        <div class='float-right'>
                                            <a href='adminPage.php?rejectButton=$count' style='color: red' id='rejectButton$count' onclick=''>
                                                <svg xmlns='http://www.w3.org/2000/svg' width='30' height='30' fill='currentColor' class='bi bi-x-circle-fill mr-2' viewBox='0 0 16 16'>
                                                    <path d='M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zM5.354 4.646a.5.5 0 1 0-.708.708L7.293 8l-2.647 2.646a.5.5 0 0 0 .708.708L8 8.707l2.646 2.647a.5.5 0 0 0 .708-.708L8.707 8l2.647-2.646a.5.5 0 0 0-.708-.708L8 7.293 5.354 4.646z'/>
                                            </a>
                                            </svg>
                                            <a href='adminPage.php?approveButton=$count' style='color: #66ff00' id='approveButton$count'>
                                                <svg xmlns='http://www.w3.org/2000/svg' width='30' height='30' fill='currentColor' class='bi bi-check-circle mr-5' viewBox='0 0 16 16'>
                                                    <path d='M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z'/>
                                                    <path d='M10.97 4.97a.235.235 0 0 0-.02.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-1.071-1.05z'/>
                                                </svg>
                                            </a>
                                        </div>
                                    </div>
                                    <hr/>";

                                    $i++;
                            
                            }
                        } else {
                            echo 
                            "
                            <div class='comment-text-sm text-center'>
                                <span>
                                    No comments to approve.
                                </span>
                            </div>
                            ";
                        }
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
    $(document).ready(function(){

        <?php

        if (isset($_GET['rejectButton'])) {

        ?>

                <?php $rejectButtonNumber = $_GET['rejectButton']; ?>

                <?php $commentNumberReject = $commentArrayForCheck[$rejectButtonNumber]['user_album_comment_id']; ?>
                <?php $userNameOfCommentReject = $commentArrayForCheck[$rejectButtonNumber]['username']; ?>

                $.ajax({
                url: '../process_files/rejectComment.php',
                type: 'POST',
                data: jQuery.param({ commentNumberToReject: <?php echo "\"$commentNumberReject\""; ?>, },) ,
                contentType: 'application/x-www-form-urlencoded; charset=UTF-8',
                success: function (response) {
                    alert("Comment number <?php echo $commentNumberReject ?> by user <?php echo $userNameOfCommentReject ?> deleted");
                    window.location.href = 'adminPage.php';

                },
                error: function () {
                    alert("error");
                }
                }); 
        <?php
        }
        ?>
            
        <?php

        if (isset($_GET['approveButton'])) {

        ?>
        
            
                <?php $approveButtonNumber = $_GET['approveButton']; ?>

                <?php $commentNumberApprove = $commentArrayForCheck[$approveButtonNumber]['user_album_comment_id']; ?>
                <?php $userNameOfCommentApprove = $commentArrayForCheck[$approveButtonNumber]['username']; ?>

                $.ajax({
                url: '../process_files/approveComment.php',
                type: 'POST',
                data: jQuery.param({ commentNumberToApprove: <?php echo "\"$commentNumberApprove\""; ?>, },) ,
                contentType: 'application/x-www-form-urlencoded; charset=UTF-8',
                success: function (response) {
                    alert("Comment number <?php echo $commentNumberApprove ?> by user <?php echo $userNameOfCommentApprove ?> approved");
                    window.location.href = 'adminPage.php';

                },
                error: function () {
                    alert("error");
                }
                }); 
        
        <?php
        }

        ?>
        
    });
    </script>
    
</body>
</html>