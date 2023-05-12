<?php
    session_start();
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
    include("../functions/functions.php");

    if(isset($_COOKIE["Username"])){
        $userName = $_COOKIE["Username"];
        $loggedIn = createCookie("LoggedIn", true);
        
    } else {
        echo "<script>";
        echo "window.location.assign(\"index.php\")";
        echo "</script>";
    }

    $endpointComments = "http://droddy03.webhosting6.eeecs.qub.ac.uk/top500API/api.php?userSpecificReview=true&username=$userName";

    $resource = file_get_contents($endpointComments, false, stream_context_create());

    $commentData = json_decode($resource, true);
    

?>  

<!DOCTYPE html>
<html lang="en">

<?php 
$pageTitle = "User Reviews";
require("../phpTemplates/header.php"); 
?>

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

    <h1 id="albumListHeaderOne" class="text-center"><?php echo $userName; ?>'s Comment Board</h1>

    <div class="text-center" style="position: absolute">
        <a href="profile.php">
            <p class="ml-5 mb-0">Go back</p>
            <svg xmlns="http://www.w3.org/2000/svg" width="100" height="100" fill="white" class="bi bi-skip-backward-btn ml-5" viewBox="0 0 16 16">
                <path d="M11.21 5.093A.5.5 0 0 1 12 5.5v5a.5.5 0 0 1-.79.407L8.5 8.972V10.5a.5.5 0 0 1-.79.407L5 8.972V10.5a.5.5 0 0 1-1 0v-5a.5.5 0 0 1 1 0v1.528l2.71-1.935a.5.5 0 0 1 .79.407v1.528l2.71-1.935z"/>
                <path d="M0 4a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v8a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V4zm15 0a1 1 0 0 0-1-1H2a1 1 0 0 0-1 1v8a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1V4z"/>
            </svg>
        </a>
    </div>

    <!-- COMMENT SECTION -->
    <div class="container mt-5 mb-5">

        <!-- ROW -->
        <div class="d-flex justify-content-center row">

            <!-- COLUMN -->
            <div class="d-flex flex-column col-md-8">

                <!-- COMMENT -->
                <div class="coment-bottom bg-dark p-2 px-4">

                        <!-- OTHER USER COMMENTS -->
                        <?php
                        $i = 0; 
                        
                        $commentArrayForReview = array();

                        if(!empty($commentData)) {
                            while($commentData != false) {

                                $row = array_shift($commentData);
                                
                                $count = strval($i);

                                $commentArrayForReview[] = $row;

                                $commentTime = date('M j Y g:i A', strtotime($row['comment_time']));
                                
                                    echo "
                                    <div class='d-flex flex-row align-items-center commented-user' id='userCommentDetails$i'>
                                        <h5 class='mr-2'>{$row['username']}</h5>
                                        <span class='dot mb-1'></span>
                                        <span class='mb-1 ml-2'>album: {$row['album_name']}, comment time: </span>
                                        <span class='mb-1 ml-2'>$commentTime,</span>";
                                        if($row['comment_approved'] == 1) {
                                        echo 
                                        "<span class='mb-1 ml-2'><em>LIVE</em></span>";
                                        } else {
                                            echo 
                                            "<span class='mb-1 ml-2'><em>PENDING</em></span>";
                                        }

                                    echo
                                    "</div>

                                    <div class='comment-text-sm mb-5' id='userComment$i'>
                                        <span class='d-none' id='user_review_id_$i'>{$row['user_album_comment_id']}</span>
                                        <span id='userReviewContent$i'>
                                            {$row['user_review']}
                                        </span>
                                        <div class='float-right'>

                                            <button type='button' class='btn btn-dark' data-toggle='modal' data-target='#reviewEditModal$i' id='editReviewsModalButton$i'>
                                                <svg xmlns='http://www.w3.org/2000/svg' width='30' height='30' fill='currentColor' class='bi bi-pencil float-right mt-2 fa-lg' viewBox='0 0 16 16' id='bioIconSVGTwo'>
                                                    <path d='M12.146.146a.5.5 0 0 1 .708 0l3 3a.5.5 0 0 1 0 .708l-10 10a.5.5 0 0 1-.168.11l-5 2a.5.5 0 0 1-.65-.65l2-5a.5.5 0 0 1 .11-.168l10-10zM11.207 2.5 13.5 4.793 14.793 3.5 12.5 1.207 11.207 2.5zm1.586 3L10.5 3.207 4 9.707V10h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.293l6.5-6.5zm-9.761 5.175-.106.106-1.528 3.821 3.821-1.528.106-.106A.5.5 0 0 1 5 12.5V12h-.5a.5.5 0 0 1-.5-.5V11h-.5a.5.5 0 0 1-.468-.325z' id='bioIconPathTwo'/>
                                                </svg>
                                            </button>

                                            <button type='button' class='btn btn-dark mr-3' id='commentDeleteButton$i'>
                                                <svg xmlns='http://www.w3.org/2000/svg' width='30' height='30' fill='red' class='bi bi-x-circle-fill mr-2' viewBox='0 0 16 16'>
                                                    <path d='M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zM5.354 4.646a.5.5 0 1 0-.708.708L7.293 8l-2.647 2.646a.5.5 0 0 0 .708.708L8 8.707l2.646 2.647a.5.5 0 0 0 .708-.708L8.707 8l2.647-2.646a.5.5 0 0 0-.708-.708L8 7.293 5.354 4.646z'/>
                                            </button>
                                        </div>
                                    </div>
                                    <hr/>

                                    <!-- Modal -->
                                    <div class='modal fade' id='reviewEditModal$i' tabindex='-1' aria-labelledby='exampleModalLabel' aria-hidden='true'>

                                        <!-- MODAL DIALOGUE -->
                                        <div class='modal-dialog modal-dialog-centered'>

                                            <!-- MODAL CONTENT -->
                                            <div class='modal-content'>

                                                <!-- MODAL HEADER DIV -->
                                                <div class='modal-header'>

                                                    <!-- MODAL HEADER TXT -->
                                                    <h5 class='modal-title text-dark' id='exampleModalLabel'><?php echo '$userName' ?> Comment </h5>

                                                    <!-- MODAL BUTTON (EXIT) -->
                                                    <button type='button' class='close' data-dismiss='modal' aria-label='Close'>
                                                    <span aria-hidden='true'>&times;</span>
                                                    </button>
                                                </div>

                                                <!-- MODAL FORM -->
                                                <form class='form-inline text-center' role='form' id='commentForm$i' action='userReviews.php' method='POST'>
                                                    <!-- MODAL BODY -->
                                                    <div class='modal-body mx-auto'>
                                                        
                                                        <input type='text' name='newReviewDetail$i' id='newReviewDetail$i' value=''>
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
                                    </div>";

                                    $i++;
                            
                            }
                        } else {
                            echo 
                            "
                            <div class='comment-text-sm text-center'>
                                <span>
                                    No comments to edit.
                                </span>
                            </div>
                            ";
                        }
                        ?>
                    </div>
                </div>
            </div>
        </div>

        

    <?php require("../phpTemplates/footer.php"); ?>

    <script>
    $(document).ready(function(){

        <?php 
            for($printNum = 0; $printNum < count($commentArrayForReview); $printNum++) {
                echo
                "
                $('#editReviewsModalButton$printNum').click(function(){
                        var userComment = $('#userReviewContent$printNum').text();
                        userComment = $.trim(userComment);
                        $('#newReviewDetail$printNum').attr('value', userComment);
                });
                ";

            }
        ?>

        <?php
        for($printNumTwo = 0; $printNumTwo < count($commentArrayForReview); $printNumTwo++) {
            echo
            "
            $('#updateCommentButton$printNumTwo').click(function(e) {
                    
                    var newComment = $('#newReviewDetail$printNumTwo').val();
                    var userReviewID = $('#user_review_id_$printNumTwo').text();
                    
                    $.ajax({
                    url: '../process_files/updateComment.php',
                    type: 'POST',
                    data: jQuery.param({ commentUpdate: newComment, username: \"$userName\", reviewID: userReviewID},) ,
                    contentType: 'application/x-www-form-urlencoded; charset=UTF-8',
                    success: function (response) {
                        alert('comment updated');
                        e.submit();

                    },
                    error: function () {
                        alert('error');
                        
                    }
                    }); 
                });
            ";
        }
        
        for($printNumThree = 0; $printNumThree < count($commentArrayForReview); $printNumThree++) {
            echo
            "
            $('#commentDeleteButton$printNumThree').click(function(e) {
                    
                    var userReviewID = $('#user_review_id_$printNumThree').text();
                    
                    $.ajax({
                    url: '../process_files/deleteComment.php',
                    type: 'POST',
                    data: jQuery.param({username: \"$userName\", reviewID: userReviewID},) ,
                    contentType: 'application/x-www-form-urlencoded; charset=UTF-8',
                    success: function (response) {
                        alert('comment deleted');
                        window.location.reload();

                    },
                    error: function () {
                        alert('error');
                        
                    }
                    }); 
                });
            ";
        }

        ?>
    });
    </script>

</body>

</html>