<?php
    session_start();
    include("../functions/functions.php");

    $albumID = $_GET['albumID'];
    $pageTitle = "Album View";
    $pageNumber = $_GET['page'];

    $navItem1 = "Home";
    $navItem1Link = "index.php";

    $navItem2 = "Album List <span class='sr-only'>(current)</span>";
    $navItem2Active = "active";
    $navItem2Link = "albumList.php";

    $navItem3 = "Register";
    $navItem3Link = "signup.php";

    if(isset($_SESSION['authenticated']) && isset($_COOKIE['Username'])) {

        $navItem3 = "Profile Page";
        $navItem3Link = "profile.php";

        $navItem5 = "Log Out";
        $navItem5Link = "logout.php";

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

        // QUERY IF USER HAS ALREADY FAVOURITED ALBUM
        $endpointFavourites = "http://droddy03.webhosting6.eeecs.qub.ac.uk/top500API/api.php?userFavouriteAlbumSearch=$userName&&albumID=$albumID";

        $resourceFavourites = file_get_contents($endpointFavourites);

        $dataFavourites = json_decode($resourceFavourites, true);

        // QUERY IF USER ALREADY HAS ALBUM IN OWNED
        $endpointOwned = "http://droddy03.webhosting6.eeecs.qub.ac.uk/top500API/api.php?userOwnedAlbumSearch=$userName&&albumID=$albumID";

        $resourceOwned = file_get_contents($endpointOwned);

        $dataOwned = json_decode($resourceOwned, true);

        // QUERY IF USER ALREADY HAS RATED ALBUM
        $endpointRating = "http://droddy03.webhosting6.eeecs.qub.ac.uk/top500API/api.php?getRating=true&&userName=$userName&&albumID=$albumID";

        $resourceRating = file_get_contents($endpointRating);

        $dataRating = json_decode($resourceRating, true);
        
        $starRatingPrint = "<div class='container-wrapper mt-4'>  
                <div class='container d-flex align-items-center justify-content-center'>
                    <div class='row justify-content-center'>    
                        
                        <!-- star rating -->
                        <div class='rating-wrapper'>
                            
                            <!-- star 5 -->
                            <input type='radio' id='5-star-rating' name='star-rating' value='5' class='starHide'>
                            <label for='5-star-rating' class='star-rating starHide' id='star-label-5'>
                            <i class='fas fa-star d-inline-block starHide'></i>
                            </label>
                            
                            <!-- star 4 -->
                            <input type='radio' id='4-star-rating' name='star-rating' value='4' class='starHide'>
                            <label for='4-star-rating' class='star-rating star starHide' id='star-label-4'>
                            <i class='fas fa-star d-inline-block starHide'></i>
                            </label>
                            
                            <!-- star 3 -->
                            <input type='radio' id='3-star-rating' name='star-rating' value='3' class='starHide'>
                            <label for='3-star-rating' class='star-rating star starHide' id='star-label-3'>
                            <i class='fas fa-star d-inline-block starHide'></i>
                            </label>
                            
                            <!-- star 2 -->
                            <input type='radio' id='2-star-rating' name='star-rating' value='2' class='starHide'>
                            <label for='2-star-rating' class='star-rating star starHide' id='star-label-2'>
                            <i class='fas fa-star d-inline-block starHide'></i>
                            </label>
                            
                            <!-- star 1 -->
                            <input type='radio' id='1-star-rating' name='star-rating' value='1' class='starHide'>
                            <label for='1-star-rating' class='star-rating star starHide' id='star-label-1'>
                            <i class='fas fa-star d-inline-block starHide'></i>
                            </label>

                        </div>
                    
                    </div>
                </div>
            </div>";

        

        $userOptionToCommentPrint = "<div class='d-flex flex-row add-comment-section mt-4 mb-4' id='userOptionToComment'>
                        <img class='img-responsive rounded-circle mr-2' width='55' height='45' src='{$dataUser['profile_picture']}' width='38'>
                        <input type='text' class='form-control mr-3' placeholder='Add comment' id='userAddCommentInput'>
                        <button class='btn btn-primary' type='button' id='userAddCommentBtn'>Comment</button></div>";

    } else {
            $starRatingPrint = "<div class='container-wrapper mt-4'>  
                <div class='container d-flex align-items-center justify-content-center'>
                    <div class='row justify-content-center'>    
                        
                        <h5>Sign in or register to add your own rating!</h5>
                    
                    </div>
                </div>
            </div>";

            

            $userOptionToCommentPrint = "<div class='d-flex flex-row add-comment-section mt-4 mb-4' id='userOptionToComment'>
                                            <div  class='mx-auto'>
                                                <a href='login.php'>Sign in </a> or <a href='signup.php'> Register </a>to add comments!
                                            </div>
                                        </div>";
    }

    // QUERY ALBUM WITH ID SENT FROM PREVIOUS PAGE
    $endpointAlbum = "http://droddy03.webhosting6.eeecs.qub.ac.uk/top500API/api.php?albumIDSearch=true&albumID=$albumID";

    $resource = file_get_contents($endpointAlbum, false, stream_context_create());

    $dataAlbum = json_decode($resource, true);
    $dataAlbumGET = urlencode($dataAlbum['album_name']);

    // RETRIEVE ANY PREVIOUS COMMENTS
    $endpointComments = "http://droddy03.webhosting6.eeecs.qub.ac.uk/top500API/api.php?comments=true&albumID=$albumID";

    $resource = file_get_contents($endpointComments, false, stream_context_create());

    $dataComment = json_decode($resource, true);
    
    // GET ALL RATINGS
    $endpointRatingAll = "http://droddy03.webhosting6.eeecs.qub.ac.uk/top500API/api.php?allRatings=true&&albumID=$albumID";

    $resourceRatingAll = file_get_contents($endpointRatingAll);

    $dataRatingAll = json_decode($resourceRatingAll, true);

    // CALCULATE AVERAGE
    $numberOfRatings = count($dataRatingAll);

    $sumOfRatings = 0;

    for($i=0; $i<$numberOfRatings; $i++) {
        $sumOfRatings+= (float)$dataRatingAll[$i]['user_rating'];
    }

    if($numberOfRatings > 0) {
        $albumRatingAverage = (float)$sumOfRatings / (float)$numberOfRatings;
        $albumRatingAverage = round($albumRatingAverage, 1, PHP_ROUND_HALF_UP);


        $albumRatingAverageMessage = $albumRatingAverage." "."/ 5";

    } else {
        $albumRatingAverageMessage = "Not yet rated";
    }
    
    
?>

<!DOCTYPE html>
<?php 
    require("../phpTemplates/header.php");
?>
<script>
    $(document).ready(function(){

        <?php
            if(isset($_COOKIE['Username']) && isset($_SESSION['authenticated'])) {
                if($dataFavourites == true) {
                    $albumFavourited = "Album in your favourites";
                    echo "$('#AddToFavouritesWrapper').hide();
                        $('#RemoveFromFavouritesWrapper').show();";
                } else {
                    $albumFavourited = "Album not in your favourites";
                    echo "$('#RemoveFromFavouritesWrapper').hide();
                        $('#AddToFavouritesWrapper').show();";
                }
                
                if($dataOwned == true) {
                    $albumOwned = "Album in your owned collection";
                    echo "$('#AddToOwnedWrapper').hide();
                        $('#RemoveFromOwnedWrapper').show();";
                } else {
                    $albumOwned = "Album not in your owned collection";
                    echo "$('#RemoveFromOwnedWrapper').hide();
                        $('#AddToOwnedWrapper').show();";
                }

                if($dataRating != false) {
                    for($counter = $dataRating["user_rating"]; $counter>=1; $counter--) {
                        echo "
                            $('#star-label-{$counter}').css('color', 'yellow');
                        ";
                    }
                }
    ?>    
            
    
                $('#userAddCommentBtn').click(function(e) {
                        
                        var commentToAdd = $('#userAddCommentInput').val();
                        
                        if(commentToAdd.length > 250) {

                            alert("Comment exceeds the maximum of 250 characters.");
                        } else {

                            $.ajax({
                            url: '../process_files/addComment.php',
                            type: 'POST',
                            data: jQuery.param({ comment: commentToAdd, userID: <?php echo "\"{$dataUser['user_id']}\""; ?>, albumID: <?php echo "\"$albumID\""; ?>},) ,
                            contentType: 'application/x-www-form-urlencoded; charset=UTF-8',
                            success: function (response) {
                                alert("Comment received. Now pending.");
                                window.location.reload();

                            },
                            error: function () {
                                alert("error");
                            }
                            });
                        }

                    });

                    <?php
                    if (isset($_GET['addToFav'])) {
                    ?>

                        <?php $chosenAlbum = $_GET['addToFav']; ?>

                        $.ajax({
                            url: '../process_files/addAlbumToFavs.php',
                            type: 'POST',
                            data: jQuery.param({ favAlbumReturn: <?php echo "\"$chosenAlbum\""; ?>, username: <?php echo "\"$userName\""; ?>},) ,
                            contentType: 'application/x-www-form-urlencoded; charset=UTF-8',
                            success: function (response) {
                                alert("Added to favourites!");
                                window.location.assign("albumView.php?albumID=<?php echo "$albumID&page=$pageNumber"; ?>");

                            },
                            error: function () {
                                alert("error");
                                
                            }
                        }); 
                        
                    <?php
                    }
                    ?>
                    
                    <?php
                    if (isset($_GET['deleteFromFav'])) {
                    ?>

                        <?php $chosenAlbum = $_GET['deleteFromFav']; ?>

                        $.ajax({
                                url: '../process_files/favAlbumEditButton.php',
                                type: 'POST',
                                data: jQuery.param({ albumFavForEdit: <?php echo "\"$chosenAlbum\""; ?>, username: <?php echo "\"$userName\""; ?>},) ,
                                contentType: 'application/x-www-form-urlencoded; charset=UTF-8',
                                success: function (response) {
                                    alert("Removed from favourites");
                                    window.location.assign("albumView.php?albumID=<?php echo "{$albumID}&page=$pageNumber"; ?>");

                                },
                                error: function () {
                                    alert("error");
                                    
                                }
                        }); 

                    <?php
                    }
                    ?>

                    <?php
                    if (isset($_GET['addToOwned'])) {
                    ?>

                        <?php $chosenAlbum = $_GET['addToOwned']; ?>

                        $.ajax({
                        url: '../process_files/addAlbumToOwned.php',
                        type: 'POST',
                        data: jQuery.param({ ownedAlbumToAdd: <?php echo "\"$chosenAlbum\""; ?>, username: <?php echo "\"$userName\""; ?>},) ,
                        contentType: 'application/x-www-form-urlencoded; charset=UTF-8',
                        success: function (response) {
                            alert("Album added to owned!");
                            window.location.assign("albumView.php?albumID=<?php echo "$albumID&page=$pageNumber"; ?>");

                        },
                        error: function () {
                            alert("error");
                            
                        }
                        });
                        
                    <?php
                    }
                    ?>
                    
                    <?php
                    if (isset($_GET['deleteFromOwned'])) {
                    ?>

                        <?php $chosenAlbum = $_GET['deleteFromOwned']; ?>

                        $.ajax({
                            url: '../process_files/ownedAlbumEdit.php',
                            type: 'POST',
                            data: jQuery.param({ albumOwnedForEdit: <?php echo "\"$chosenAlbum\""; ?>, username: <?php echo "\"$userName\""; ?>},) ,
                            contentType: 'application/x-www-form-urlencoded; charset=UTF-8',
                            success: function (response) {
                                alert("Album removed from favourites!");
                                window.location.assign("albumView.php?albumID=<?php echo "$albumID&page=$pageNumber"; ?>");

                            },
                            error: function () {
                                alert("error");
                                
                            }
                        });
                        
                    <?php
                    }
                    ?>

                $('.rating-wrapper').change(function(e) {
                        
                        var ratingSelected = e.target.value;

                        $.ajax({
                        url: '../process_files/alterRating.php',
                        type: 'POST',
                        data: jQuery.param({ rating: ratingSelected, userID: <?php echo "\"{$dataUser['user_id']}\""; ?>, albumID: <?php echo "\"$albumID\""; ?>},) ,
                        contentType: 'application/x-www-form-urlencoded; charset=UTF-8',
                        success: function (response) {
                            alert("Rating updated");
                            
                            window.location.reload();

                        },
                        error: function () {
                            alert("error");
                        }
                        }); 
                    });

                $('.upVote').click(function(e) {
                    var commentSelected = e.target.id.substring(6);
                    var commentSelector = "#commentID".concat(commentSelected);
                    var commentID = $(commentSelector).text();
                    if(!$(this).hasClass("votedUp")) {
                        
                        $.ajax({
                        url: '../process_files/upVoteComment.php',
                        type: 'POST',
                        data: jQuery.param({ comment: commentID, userID: <?php echo "\"{$dataUser['user_id']}\""; ?>,},) ,
                        contentType: 'application/x-www-form-urlencoded; charset=UTF-8',
                        success: function (response) {
                            alert("Comment liked");
                            
                            window.location.reload();

                        },
                        error: function () {
                            alert("error");
                        }
                        });

                    } else {

                        $.ajax({
                        url: '../process_files/upVoteComment.php',
                        type: 'POST',
                        data: jQuery.param({ comment: commentID, userID: <?php echo "\"{$dataUser['user_id']}\""; ?>, removeVote: true, },) ,
                        contentType: 'application/x-www-form-urlencoded; charset=UTF-8',
                        success: function (response) {
                            alert("Comment like removed");
                            
                            window.location.reload();

                        },
                        error: function () {
                            alert("error");
                        }
                        });
                    }

                });

                $('.downVote').click(function(e) {
                    var commentSelected = e.target.id.substring(8);
                    var commentSelector = "#commentID".concat(commentSelected);
                    var commentID = $(commentSelector).text();

                    if(!$(this).hasClass("votedDown")) {
                        
                        $.ajax({
                        url: '../process_files/downVoteComment.php',
                        type: 'POST',
                        data: jQuery.param({ comment: commentID, userID: <?php echo "\"{$dataUser['user_id']}\""; ?>,},) ,
                        contentType: 'application/x-www-form-urlencoded; charset=UTF-8',
                        success: function (response) {
                            alert("Comment disliked");
                            
                            window.location.reload();

                        },
                        error: function () {
                            alert("error");
                        }
                        });

                    } else {

                        $.ajax({
                        url: '../process_files/downVoteComment.php',
                        type: 'POST',
                        data: jQuery.param({ comment: commentID, userID: <?php echo "\"{$dataUser['user_id']}\""; ?>, removeVote: true, },) ,
                        contentType: 'application/x-www-form-urlencoded; charset=UTF-8',
                        success: function (response) {
                            alert("Comment dislike removed");
                            
                            window.location.reload();

                        },
                        error: function () {
                            alert("error");
                        }
                        });
                    }

                });
        
    <?php
    }
    ?>

    });

    <?php
        if(isset($_SESSION['authenticated']) && isset($_COOKIE['Username'])) {
        $addRemoveAlbumFromPersonalList = "<div class = 'container'>
                                                <div class='row mt-5'>
                                                    <div class='col-6'>
                                                        <h3 class='text-center'> 
                                                        Favourites 
                                                        </h3>
                                                        <h5 class='text-center'>$albumFavourited</h5>
                                                        <h4 class='text-center mt-4' id='AddToFavouritesWrapper'>
                                                            <a href='albumView.php?addToFav=$dataAlbumGET&albumID=$albumID&page=$pageNumber' style='text-decoration: none; color: green;' id='heartButton' class='heartButton'>

                                                                Add
                                                                
                                                                <svg xmlns='http://www.w3.org/2000/svg' width='30' height='25' fill='green' class='bi bi-heart d-inline' viewBox='0 0 16 16'>
                                                                <path d='m8 2.748-.717-.737C5.6.281 2.514.878 1.4 3.053c-.523 1.023-.641 2.5.314 4.385.92 1.815 2.834 3.989 6.286 6.357 3.452-2.368 5.365-4.542 6.286-6.357.955-1.886.838-3.362.314-4.385C13.486.878 10.4.28 8.717 2.01L8 2.748zM8 15C-7.333 4.868 3.279-3.04 7.824 1.143c.06.055.119.112.176.171a3.12 3.12 0 0 1 .176-.17C12.72-3.042 23.333 4.867 8 15z'/>
                                                                </svg>
                                                            </a>
                                                        </h4>
                                                        <br>
                                                        <h5 class='text-center' id='RemoveFromFavouritesWrapper'>
                                                            <a href='albumView.php?deleteFromFav=$dataAlbumGET&albumID=$albumID&page=$pageNumber' style='text-decoration: none; color: #8b0000;' id='heartButton' class='heartButton'>

                                                                Remove
                                                                
                                                                <svg xmlns='http://www.w3.org/2000/svg' width='30' height='25' fill='currentColor' class='bi bi-file-earmark-x' viewBox='0 0 16 16'>
                                                                <path d='M6.854 7.146a.5.5 0 1 0-.708.708L7.293 9l-1.147 1.146a.5.5 0 0 0 .708.708L8 9.707l1.146 1.147a.5.5 0 0 0 .708-.708L8.707 9l1.147-1.146a.5.5 0 0 0-.708-.708L8 8.293 6.854 7.146z'/>
                                                                <path d='M14 14V4.5L9.5 0H4a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2zM9.5 3A1.5 1.5 0 0 0 11 4.5h2V14a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1h5.5v2z'/>
                                                                </svg>
                                                            </a>
                                                        </h5>
                                                    </div>
                                                    
                                                    <div class='col-6'>
                                                        <h3 class='text-center'> 
                                                        Owned
                                                        </h3>
                                                        <h5 class='text-center'>$albumOwned</h5>

                                                        <h4 class='text-center mt-4'` id='AddToOwnedWrapper'>
                                                            <a href='albumView.php?addToOwned=$dataAlbumGET&albumID=$albumID&page=$pageNumber' style='text-decoration: none; color: green;' id='heartButton' class='heartButton'>

                                                                Add
                                                                
                                                                <svg xmlns='http://www.w3.org/2000/svg' width='30' height='25' fill='currentColor' class='bi bi-plus-circle' viewBox='0 0 16 16'>
                                                                    <path d='M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z'/>
                                                                    <path d='M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4z'/>
                                                                </svg>
                                                            </a>
                                                        </h4>

                                                        <br>

                                                        <h5 class='text-center' id='RemoveFromOwnedWrapper'>
                                                            <a href='albumView.php?deleteFromOwned=$dataAlbumGET&albumID=$albumID&page=$pageNumber' style='text-decoration: none; color: #8b0000;' id='heartButton' class='heartButton'>

                                                                Remove
                                                                
                                                                <svg xmlns='http://www.w3.org/2000/svg' width='30' height='25' fill='currentColor' class='bi bi-folder-x' viewBox='0 0 16 16'>
                                                                    <path d='M.54 3.87.5 3a2 2 0 0 1 2-2h3.672a2 2 0 0 1 1.414.586l.828.828A2 2 0 0 0 9.828 3h3.982a2 2 0 0 1 1.992 2.181L15.546 8H14.54l.265-2.91A1 1 0 0 0 13.81 4H2.19a1 1 0 0 0-.996 1.09l.637 7a1 1 0 0 0 .995.91H9v1H2.826a2 2 0 0 1-1.991-1.819l-.637-7a1.99 1.99 0 0 1 .342-1.31zm6.339-1.577A1 1 0 0 0 6.172 2H2.5a1 1 0 0 0-1 .981l.006.139C1.72 3.042 1.95 3 2.19 3h5.396l-.707-.707z'/>
                                                                    <path d='M11.854 10.146a.5.5 0 0 0-.707.708L12.293 12l-1.146 1.146a.5.5 0 0 0 .707.708L13 12.707l1.146 1.147a.5.5 0 0 0 .708-.708L13.707 12l1.147-1.146a.5.5 0 0 0-.707-.708L13 11.293l-1.146-1.147z'/>
                                                                </svg>
                                                            </a>
                                                        </h5>
                                                    </div>
                                                </div>
                                            </div>";
        } else {

            $addRemoveAlbumFromPersonalList = "<div class='container-wrapper mt-4'>  
                <div class='container d-flex align-items-center justify-content-center'>
                    <div class='row justify-content-center'>    
                        
                        <h5>You can add these to your favourites and owned music list when signed in</h5>
                    
                    </div>
                </div>
            </div>";

        }
    ?>
</script>
<?php 



?>

<body>

    <?php require("../phpTemplates/navbar.php"); ?>

    
    <h1 id="albumListHeaderOne" class="text-center" id="greatestAlbumsHeader">The 500 greatest albums of all time!</h1>

    <div class="text-center" style="position: absolute">
        <a href="albumList.php?page=<?php echo (string)$pageNumber; ?>">
            <p class="ml-5 mb-0">Go back</p>
            <svg xmlns="http://www.w3.org/2000/svg" width="100" height="100" fill="white" class="bi bi-skip-backward-btn ml-5" viewBox="0 0 16 16">
                <path d="M11.21 5.093A.5.5 0 0 1 12 5.5v5a.5.5 0 0 1-.79.407L8.5 8.972V10.5a.5.5 0 0 1-.79.407L5 8.972V10.5a.5.5 0 0 1-1 0v-5a.5.5 0 0 1 1 0v1.528l2.71-1.935a.5.5 0 0 1 .79.407v1.528l2.71-1.935z"/>
                <path d="M0 4a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v8a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V4zm15 0a1 1 0 0 0-1-1H2a1 1 0 0 0-1 1v8a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1V4z"/>
            </svg>
        </a>
    </div>

    <?php
        echo "<h2 class='text-center mt-4 w-50 mx-auto'>Album Rank: No. {$dataAlbum['album_ranking']}</h2>";
        echo "<h3 class='text-center w-50 mx-auto'>{$dataAlbum['album_name']} by {$dataAlbum['artist_name']}</h3>";
        echo "<img src='{$dataAlbum['album_image_spotify_url_size_one']}' class='mx-auto d-block'>";
        echo $starRatingPrint;

        echo "<h3 class='text-center mt-2'>Average rating by users: $albumRatingAverageMessage</h3>";

        echo $addRemoveAlbumFromPersonalList;
    ?>

    <!-- COMMENT SECTION -->
    <div class="container mt-5 mb-5">

        <!-- ROW -->
        <div class="d-flex justify-content-center row">

            <!-- COLUMN -->
            <div class="d-flex flex-column col-md-8">

                <!-- COMMENT -->
                <div class="coment-bottom bg-dark p-2 px-4">

                    <!-- USER OPTION TO COMMENT -->
                    <?php echo $userOptionToCommentPrint; ?>
                    <div class='commented-section mt-2'>
                        <!-- OTHER USER COMMENTS -->
                        <?php
                        if(!empty($dataComment)) {
                            $counter = 0;

                            $userCommentsPrinted = array();
                            while($dataComment != false) {
                                
                                $counter = strval($counter);
                                $row = array_shift($dataComment);
                                $commentID = $row['user_album_comment_id'];

                                $arraySearchResult = array_search($commentID, $userCommentsPrinted, false);

                                // $userCommentsPrinted[] = $row;
                                array_push($userCommentsPrinted, $commentID);

                                $commentTime = date('M j Y g:i A', strtotime($row['comment_time']));
                                
                                if(isset($dataUser)) {

                                
                                    // check if logged in user has liked the comment
                                    $endpointLikes = "http://droddy03.webhosting6.eeecs.qub.ac.uk/top500API/api.php?returnIfUserLikedComment=true&commentID=$commentID&userID={$dataUser['user_id']}";

                                    $resourceLikes = file_get_contents($endpointLikes, false, stream_context_create());

                                    $dataLikes = json_decode($resourceLikes, true);

                                    // check if logged in user has disliked the comment
                                    $endpointDislikes = "http://droddy03.webhosting6.eeecs.qub.ac.uk/top500API/api.php?returnIfUserDislikedComment=true&commentID=$commentID&userID={$dataUser['user_id']}";

                                    $resourceDislikes = file_get_contents($endpointDislikes, false, stream_context_create());

                                    $dataDislikes = json_decode($resourceDislikes, true);

                                        if($dataLikes[0] === true) {
                                            $stylePrintUpVote = "color: blue;";
                                            $classPrintUpVote = "votedUp";
                                        } else {
                                            $stylePrintUpVote = "";
                                            $classPrintUpVote = "";
                                        }

                                        if($dataDislikes[0] === true) {
                                            $stylePrintDownVote = "color: blue;";
                                            $classPrintDownVote = "votedDown";
                                        } else {
                                            $stylePrintDownVote = "";
                                            $classPrintDownVote = "";
                                        }

                                        $likesDislikesPrint = "<div class='reply-section mb-4'>
                                        <div class='d-flex flex-row align-items-center voting-icons'>
                                            <span id='upVote$counter' class='upVote {$classPrintUpVote} hit-voting' style='{$stylePrintUpVote} cursor: pointer; '>Like</span>
                                            <span> || </span>
                                            <span id='downVote$counter' class='downVote {$classPrintDownVote} hit-voting' style='{$stylePrintDownVote} cursor: pointer; '>Dislike</span>
                                            <span class='ml-2'>Likes: {$row['comment_likes']}</span>
                                            <span class='ml-2'>Dislikes: {$row['comment_dislikes']}</span>
                                            <span id='commentID$counter' hidden>{$row['user_album_comment_id']}</span>
                                            <span class='dot ml-2'></span>
                                            <h6 class='ml-2 mt-1'></h6>
                                        </div>
                                    </div>";

                                } else {
                                    $stylePrintUpVote = "";
                                    $classPrintUpVote = "";
                                    $stylePrintDownVote = "";
                                    $classPrintDownVote = "";
                                    $likesDislikesPrint = "";
                                }

                                
                                if(($row['comment_approved'] == true) && ($arraySearchResult === false)) {
                                    echo "
                                    <div class='d-flex flex-row align-items-center commented-user'>
                                        <img src='{$row['profile_picture']}' width='45' height='45' class='mr-2 d-inline-block align-top rounded-circle' alt=''>
                                        <h5 class='mr-2'>{$row['username']}</h5>
                                        <span class='dot'></span>
                                        <span class='ml-2'>$commentTime</span>
                                    </div>

                                    <div class='comment-text-sm'>
                                        <span>
                                            {$row['user_review']}
                                        </span>
                                    </div>";
                                    echo $likesDislikesPrint;
                                } else if($row['comment_approved'] == false && isset($dataUser)) {
                                    if(($row['user_id'] == $dataUser['user_id'])){
                                        
                                        echo "
                                        <div class='d-flex flex-row align-items-center commented-user'>
                                            <h5 class='mr-2'>{$row['username']}</h5>
                                            <span class='dot mb-1'></span>
                                            <span class='mb-1 ml-2'>$commentTime</span>
                                        </div>

                                        <div class='comment-text-sm'>
                                            <span>
                                                {$row['user_review']}
                                            </span>
                                            - COMMENT PENDING APPROVAL
                                        </div>
                                        <div class='reply-section'>
                                            <div class='d-flex flex-row align-items-center voting-icons'>
                                                <h6 class='ml-2 mt-1'></h6>
                                            </div>
                                        </div>";
                                    }
                                }
                                $counter++;
                            }
                        } else {
                            echo 
                            "
                            <div class='comment-text-sm text-center'>
                                <span>
                                    No comments added yet. 
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

    
</body>
</html>