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

    $endpointAllComments = "http://droddy03.webhosting6.eeecs.qub.ac.uk/top500API/api.php?returnAllComments=true";

    $resourceAllComments = file_get_contents($endpointAllComments);

    $commentData = json_decode($resourceAllComments, true);

?>

<!DOCTYPE html>

<?php 
$pageTitle = "Admin Page";
require("../phpTemplates/header.php"); 
?>



<body>

    <?php require("../phpTemplates/navbar.php"); ?>

    <h1 id="albumListHeaderOne" class="text-center">Admin Panel</h1>


    <h3 class="text-center mt-5">Delete user comments</h3>

    <div class="text-center" style="position: absolute">
        <a href="adminPage.php">
            <p class="ml-5 mb-0">Go back</p>
            <svg xmlns="http://www.w3.org/2000/svg" width="100" height="100" fill="white" class="bi bi-skip-backward-btn ml-5" viewBox="0 0 16 16">
                <path d="M11.21 5.093A.5.5 0 0 1 12 5.5v5a.5.5 0 0 1-.79.407L8.5 8.972V10.5a.5.5 0 0 1-.79.407L5 8.972V10.5a.5.5 0 0 1-1 0v-5a.5.5 0 0 1 1 0v1.528l2.71-1.935a.5.5 0 0 1 .79.407v1.528l2.71-1.935z"/>
                <path d="M0 4a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v8a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V4zm15 0a1 1 0 0 0-1-1H2a1 1 0 0 0-1 1v8a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1V4z"/>
            </svg>
        </a>
    </div>
    
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
                                            <a href='adminCommentReviewPage.php?rejectButton=$count' style='color: red' id='rejectButton$count' onclick=''>
                                                <svg xmlns='http://www.w3.org/2000/svg' width='30' height='30' fill='currentColor' class='bi bi-x-circle-fill mr-2' viewBox='0 0 16 16'>
                                                    <path d='M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zM5.354 4.646a.5.5 0 1 0-.708.708L7.293 8l-2.647 2.646a.5.5 0 0 0 .708.708L8 8.707l2.646 2.647a.5.5 0 0 0 .708-.708L8.707 8l2.647-2.646a.5.5 0 0 0-.708-.708L8 7.293 5.354 4.646z'/>
                                            </a>
                                            </svg>
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
                                    No comments to review.
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
                    window.location.href = 'adminCommentReviewPage.php';

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