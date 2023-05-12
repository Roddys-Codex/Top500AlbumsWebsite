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

    } 
    
    // Pagination
    // Store no rows per page
    $limit = 24; 


    // Get current page
    if (!isset ($_GET['page']) ) {  

        $page_number = 1;  

    } else {  

        $page_number = $_GET['page'];  

    } 

    // Initial page number
    $initial_page = ($page_number-1) * $limit; 

    if(((!isset($_GET['albumSearch'])) || ($_GET['albumSearch']==false)) && (!isset($_GET['genreFilter']) && (!isset($_GET['yearFilter'])))){
        // ALL ALBUMS
        $endpointAll = "http://droddy03.webhosting6.eeecs.qub.ac.uk/top500API/api.php";

        $resourceAll = file_get_contents($endpointAll, false, stream_context_create());

        $dataAll = json_decode($resourceAll, true);

        $endpoint = "http://droddy03.webhosting6.eeecs.qub.ac.uk/top500API/api.php?allAlbumsLimit=true&limit=$limit&initialPage=$initial_page";

        $resource = file_get_contents($endpoint, false, stream_context_create());

        // echo $resource;

        if($resource !== FALSE) {
            
            // header("Location: index.php");
            // exit();

        } else {
            // echo "Problem with INSERT!";
        }

        $data = json_decode($resource, true);

        // Total Rows
        $total_rows = count($dataAll); 

        // Required no of pages
        $total_pages = ceil($total_rows / $limit); 

    } else if(isset($_GET['albumSearch']) && (!isset($_GET['genreFilter']) && (!isset($_GET['yearFilter'])))) {
        
        $albumSearch = urlencode($_GET['albumSearch']);
        
        // SPECIFIC ALBUM
        $endpoint = "http://droddy03.webhosting6.eeecs.qub.ac.uk/top500API/api.php?albumSearched=$albumSearch";

        $resource = file_get_contents($endpoint);

        $data = json_decode($resource, true);

        // Total Rows
        $total_rows = count($data); 

        // Required no of pages
        $total_pages = ceil($total_rows / $limit); 

    } else if(isset($_GET['genreFilter'])) {

        $genreForFilter = urlencode($_GET['genreFilter']);

        // GENRE FILTER ALL FROM GENRE
        $endpointAllGenre = "http://droddy03.webhosting6.eeecs.qub.ac.uk/top500API/api.php?genreFilterAll=$genreForFilter";
        
        $resourceAllGenre = file_get_contents($endpointAllGenre);
        $dataAllGenre = json_decode($resourceAllGenre, true);


        // GENRE FILTER LIMIT
        $endpoint = "http://droddy03.webhosting6.eeecs.qub.ac.uk/top500API/api.php?genreFilterLimit=true&genreFilter=$genreForFilter&limit=$limit&initialPage=$initial_page";
        $resource = file_get_contents($endpoint);
        $data = json_decode($resource, true);



        // Total Rows
        $total_rows = count($dataAllGenre); 

        // Required no of pages
        $total_pages = ceil($total_rows / $limit);
        
    } else if(isset($_GET['yearFilter'])) {

        $yearForFilter = $_GET['yearFilter'];
        // GENRE FILTER ALL FROM GENRE
        $endpointYearFilterAll = "http://droddy03.webhosting6.eeecs.qub.ac.uk/top500API/api.php?yearFilter=$yearForFilter";

        // GENRE FILTER LIMIT
        $endpointYearFilterLimit = "http://droddy03.webhosting6.eeecs.qub.ac.uk/top500API/api.php?yearFilterLimit=$yearForFilter&limit=$limit&initialPage=$initial_page";

        $resourceAllYear = file_get_contents($endpointYearFilterAll);
        $dataAllYear = json_decode($resourceAllYear, true);

        $resource = file_get_contents($endpointYearFilterLimit);
        $data = json_decode($resource, true);

        // Total Rows
        $total_rows = count($dataAllYear); 

        // Required no of pages
        $total_pages = ceil($total_rows / $limit);
    }
    

?>

<!DOCTYPE html>
<?php 
$pageTitle = "Album List";
require("../phpTemplates/header.php"); 

$navItem1 = "Home";
$navItem1Link = "index.php";

$navItem2 = "Album List <span class='sr-only'>(current)</span>";
$navItem2Active = "active";
$navItem2Link = "albumList.php";

$navItem3 = "Register";
$navItem3Link = "signup.php";

$navItem5 = "Log In";
$navItem5Link = "login.php";

function addToOwnedOrFavPrint($counter, $page_number) {
        if(isset($_COOKIE["Username"])) {
            echo    "<span class='text-center text-dark h4 d-inline'>
                                        <a href='albumList.php?page={$page_number}&addToFavNo=$counter' style='text-decoration: none' id='heartButton$counter' class='heartButton'>
                                            <svg xmlns='http://www.w3.org/2000/svg' width='30' height='25' fill='black' class='bi bi-heart d-inline' viewBox='0 0 16 16'>
                                            <path d='m8 2.748-.717-.737C5.6.281 2.514.878 1.4 3.053c-.523 1.023-.641 2.5.314 4.385.92 1.815 2.834 3.989 6.286 6.357 3.452-2.368 5.365-4.542 6.286-6.357.955-1.886.838-3.362.314-4.385C13.486.878 10.4.28 8.717 2.01L8 2.748zM8 15C-7.333 4.868 3.279-3.04 7.824 1.143c.06.055.119.112.176.171a3.12 3.12 0 0 1 .176-.17C12.72-3.042 23.333 4.867 8 15z'/>
                                            </svg>
                                        </a>

                                        <a href='albumList.php?page={$page_number}&addToOwnedNo=$counter' id='ownedButton$counter' style='text-decoration: none'>
                                            <svg xmlns='http://www.w3.org/2000/svg' width='30' height='25' fill='currentColor' class='bi bi-plus-circle' viewBox='0 0 16 16'>
                                                <path d='M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z'/>
                                                <path d='M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4z'/>
                                            </svg>
                                        </a>
                    </span>";
        } 
}

if(isset($_COOKIE["Username"])){
            
        $navItem3 = "Profile Page";
        $navItem3Link = "profile.php";

        $navItem4 = null;
        $navItem4Link = null;

        $navItem5 = "Log Out";
        $navItem5Link = "logout.php";

    } 
?>

<body>

    <?php require("../phpTemplates/navbar.php");?>

    

    <h1 id="albumListHeaderOne" class="text-center">The 500 greatest albums of all time!</h1>
    
    <div class="container">

        <div class="row my-4 mt-5">
            <div class="col-12">

                <!-- SEARCH FOR ALBUM -->
                <div class="float-right">
                    <form action="albumList.php" method="get" class="d-inline">
                        <label for="albumSearchInput">Search for an album: </label>
                        <input type="search" name="albumSearch" id="albumSearchInput">
                        <button type="submit">Search</button>
                    </form>
                    <a href="albumList.php">
                        <button type="submit">Show All</button>
                    </a>
                </div>
            </div>

            <!-- FILTER BY GENRE -->
            <div class="col-12 mt-4 d-flex justify-content-end">

                    <form action="albumList.php" method="GET" class="d-inline float-right" id="genreFilterForm">
                        <input type="hidden" name="page" value="<?php echo $page_number; ?>"/>     
                        <label for="genreFilter" class="col-3 col-form-label">Genre: </label>
                        <select class="selectpicker col-8 ml-2" name="genreFilter" id="genreFilterSelector">

                            <?php

                                if(isset($genreForFilter)) {
                                    $genreForFilter = urldecode($genreForFilter);
                                    $defaultOption = "filtering by: ".$genreForFilter;
                                } else {
                                    $defaultOption = null;
                                }
                            echo
                            "<option value='--'>$defaultOption</option>
                            <option value='Rock'>Rock</option>
                            <option value='Blues'>Blues</option>
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
                    </form>
                    <form action="albumList.php" method="GET" class="d-inline float-left" id="yearFilterForm">
                            
                            <?php 
                                if(isset($_GET['yearFilter'])) {
                                    $rangeValue = $_GET['yearFilter'];
                                } else {
                                    $rangeValue = "Not Set";
                                }
                            ?>
                            <label for="yearFilter" class="col-3 col-form-label">Year: </label>

                            <input type="range" min="1955" max="2022" step='1' value="<?php echo $rangeValue; ?>"oninput="this.nextElementSibling.value = this.value" id="yearRangeInput" name="yearFilter">
                            <output><?php echo $rangeValue; ?></output>
                    </form>
            </div>
        </div>
    </div>
    
    <?php 
        
        $loopCount = count($data);
        $i = 0;
        $potentialFavourites = array();
        $potentialOwned = array();
        $counter = strval($i);

        if($data != false){
        
            while($data != false){

                $counter++;

                $row = array_shift($data);

                $albumName = $row['album_name'];
                $albumRank = $row['album_ranking'];
                $albumIDpass = $row['album_id'];
                $albumID = (int)$row['album_id'];
                $counter = $albumID;
                $albumArtistID = (int)$row['artist_id'];

                if($row['album_image_url'] != false) {
                    $pic = $row['album_image_url'];
                } else {
                    $pic = $row['album_image_spotify_url_size_one'];
                }
                $preview = $row['album_preview_url'];
                $price = $row['album_collection_price'];
                $albumCollectionViewURL = $row['album_collection_view_url'];
                

                $potentialFavourites[] = $row;
                $potentialOwned[] = $row;

                echo "<!-- CONTAINER -->
                    <div class='container-fluid px-4 d-flex justify-content-around'>

                        <!-- ROW -->
                        <div class='row'>
                            
                            <!-- COLUMN -->
                            <div class='p-4 col'>

                                <!-- CARD -->
                                <div class='card h-100' style='width: 18rem;'>
                                    <span class='text-center text-dark h2'>
                                        {$albumRank}
                                    </span>";
                                    addToOwnedOrFavPrint($counter, $page_number);
                                    echo "<div class ='container text-center'>
                                        <!-- IMG -->
                                        <a href='{$albumCollectionViewURL} class='inline_block' target='_blank'><img src=\"{$pic}\" class='card-img-top border albumSize mx-auto' alt='Image not yet loaded'></a>
                                    </div>
                                    <!-- CARD BODY -->
                                    <div class='card-body text-center text-dark'>
                                        <!-- CARD TEXT -->
                                        <a href='albumView.php?albumID={$albumIDpass}&page={$page_number}'>
                                            <p class='card-text text-center'>{$albumName}</p>
                                        </a>
                                        <!-- CARD BUTTON -->
                                        <audio src='{$preview}' type='audio/x-m4a' controls class='embed-responsive-item' style='width:100%'>
                                            <code> Your browser doesn't support audio tags</code>
                                        </audio>
                                    </div>
                                </div>
                            </div>";
                
                $row = array_shift($data);
                $counter++;
                $potentialFavourites[] = $row;
                $potentialOwned[] = $row;
                if($row != false) {
                
                    $albumName = $row['album_name'];
                    $albumRank = $row['album_ranking'];
                    $albumIDpass = $row['album_id'];
                    $albumID = (int)$row['album_id'];
                    $albumArtistID = (int)$row['artist_id'];
                    

                    if($row['album_image_url'] != false) {
                        $pic = $row['album_image_url'];
                    } else {
                        $pic = $row['album_image_spotify_url_size_one'];
                    }
                    $preview = $row['album_preview_url'];
                    $price = $row['album_collection_price'];
                    $albumCollectionViewURL = $row['album_collection_view_url'];

                    echo        "<!-- COLUMN -->
                                <div class='p-4 col'>

                                    <!-- CARD -->
                                    <div class='card h-100' style='width: 18rem;'>
                                        <span class='text-center h4 text-dark'>
                                            {$albumRank}
                                        </span>";
                                        addToOwnedOrFavPrint($counter, $page_number);
                                        echo "<div class ='container text-center'>
                                            <!-- IMG -->
                                            <a href='{$albumCollectionViewURL} class='inline_block' target='_blank'><img src=\"{$pic}\" class='card-img-top border albumSize mx-auto' alt='Image not yet loaded'></a>
                                        </div>
                                        <!-- CARD BODY -->
                                        <div class='card-body text-center text-dark'>
                                            <!-- CARD TEXT -->
                                            <a href='albumView.php?albumID={$albumIDpass}&page={$page_number}'>
                                                <p class='card-text text-center'>{$albumName}</p>
                                            </a>
                                            <!-- CARD BUTTON -->
                                            <audio src='{$preview}' type='audio/x-m4a' controls class='embed-responsive-item' style='width:100%'>
                                                <code> Your browser doesn't support audio tags</code>
                                            </audio>
                                        </div>
                                    </div>
                                </div>";
                } else {
                    echo "
                        </div>
                    </div>";
                    break;
                }

                $row = array_shift($data);
                $counter++;
                $potentialFavourites[] = $row;
                $potentialOwned[] = $row;

                if($row != false) {

                    $albumName = $row['album_name'];
                    $albumRank = $row['album_ranking'];
                    $albumIDpass = $row['album_id'];
                    $albumID = (int)$row['album_id'];
                    $albumArtistID = (int)$row['artist_id'];
                        
                    if($row['album_image_url'] != false) {
                        $pic = $row['album_image_url'];
                    } else {
                        $pic = $row['album_image_spotify_url_size_one'];
                    }
                    $preview = $row['album_preview_url'];
                    $price = $row['album_collection_price'];
                    $albumCollectionViewURL = $row['album_collection_view_url'];
                    

                    echo        "<!-- COLUMN -->
                                <div class='p-4 col'>

                                    <!-- CARD -->
                                    <div class='card h-100' style='width: 18rem;'>
                                        <span class='text-center h4 text-dark'>
                                            {$albumRank}
                                        </span>";
                                        addToOwnedOrFavPrint($counter, $page_number);
                                        echo "<div class ='container text-center'>
                                            <!-- IMG -->
                                            <a href='{$albumCollectionViewURL} class='inline_block' target='_blank'><img src=\"{$pic}\" class='card-img-top border albumSize mx-auto' alt='Image not yet loaded'></a>
                                        </div>
                                        <!-- CARD BODY -->
                                        <div class='card-body text-center text-dark'>
                                            <!-- CARD TEXT -->
                                            <a href='albumView.php?albumID={$albumIDpass}&page={$page_number}'>
                                                <p class='card-text text-center'>{$albumName}</p>
                                            </a>
                                            <!-- CARD BUTTON -->
                                            <audio src='{$preview}' type='audio/x-m4a' controls class='embed-responsive-item' style='width:100%'>
                                                <code> Your browser doesn't support audio tags</code>
                                            </audio>
                                        </div>
                                    </div>
                                </div>";
                } else {
                    echo "
                        </div>
                    </div>";
                    break;
                }

                $row = array_shift($data);
                $counter++;
                $potentialFavourites[] = $row;
                $potentialOwned[] = $row;

                if($row != false) {

                    $albumName = $row['album_name'];
                    $albumRank = $row['album_ranking'];
                    $albumIDpass = $row['album_id'];
                    $albumID = (int)$row['album_id'];
                    $albumArtistID = (int)$row['artist_id'];

                    if($row['album_image_url'] != false) {
                        $pic = $row['album_image_url'];
                    } else {
                        $pic = $row['album_image_spotify_url_size_one'];
                    }
                    $preview = $row['album_preview_url'];
                    $price = $row['album_collection_price'];
                    $albumCollectionViewURL = $row['album_collection_view_url'];

                    echo        "<!-- COLUMN -->
                                <div class='p-4 col'>

                                    <!-- CARD -->
                                    <div class='card h-100' style='width: 18rem;'>
                                        <span class='text-center h4 text-dark'>
                                            {$albumRank}
                                        </span>";
                                        addToOwnedOrFavPrint($counter, $page_number);
                                        echo "<div class ='container text-center'>
                                            <!-- IMG -->
                                            <a href='{$albumCollectionViewURL} class='inline_block' target='_blank'><img src=\"{$pic}\" class='card-img-top border albumSize mx-auto' alt='Image not yet loaded'></a>
                                        </div>
                                        <!-- CARD BODY -->
                                        <div class='card-body text-center text-dark'>
                                            <!-- CARD TEXT -->
                                            <a href='albumView.php?albumID={$albumIDpass}&page={$page_number}'>
                                                <p class='card-text text-center'>{$albumName}</p>
                                            </a>
                                            <!-- CARD BUTTON -->
                                            <audio src='{$preview}' type='audio/x-m4a' controls class='embed-responsive-item' style='width:100%'>
                                                <code> Your browser doesn't support audio tags</code>
                                            </audio>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>";
                } else {
                    echo "
                        </div>
                    </div>";
                    break;
                }

            }

            $currentPage = $page_number;
            $previousPage = $currentPage-1;
            $nextPage = $currentPage+1;

            if(!isset($genreForFilter)) {

                echo "<nav aria-label='Page navigation example'>";
                echo    "<ul class='pagination justify-content-center flex-wrap'>";
                
                if($currentPage!=1){
                echo "<li class='page-item'>";
                        echo '<a class="page-link" href = "albumList.php?page=' . $previousPage . '">' . 'Previous' . ' </a>'; 
                        echo            "</li>";
                }
                
                for($page_number = 1; $page_number<= $total_pages; $page_number++) {  
                    if($page_number==$currentPage) {
                        echo "<li class='page-item active'>";
                        echo '<a class="page-link" href = ""albumList.php?page=' . $page_number . '">' . $page_number . ' </a>'; 
                        echo            "</li>";
                    } else {
                        echo "<li class='page-item'>";
                            echo '<a class="page-link" href = "albumList.php?page=' . $page_number . '">' . $page_number . ' </a>'; 
                        echo            "</li>";
                    }
                }  

                if($currentPage!= 21 && $total_pages>1){
                    echo "<li class='page-item'>";
                    echo '<a class="page-link" href = "albumList.php?page=' . $nextPage . '">' . 'Next' . ' </a>'; 
                    echo            "</li>";
                }
                echo "</ul>";
                echo "</nav>";


            } else if(isset($genreForFilter)) {

                $currentPage = $page_number;
                $previousPage = $currentPage-1;
                $nextPage = $currentPage+1;

                echo "<nav aria-label='Page navigation example'>";
                echo    "<ul class='pagination justify-content-center flex-wrap'>";
                
                if($currentPage!=1){
                echo "<li class='page-item'>";
                        echo '<a class="page-link" href = "albumList.php?page=' . $previousPage . '&genreFilter=' . $genreForFilter . '">' . 'Previous' . ' </a>'; 
                        echo            "</li>";
                }
                
                for($page_number = 1; $page_number<= $total_pages; $page_number++) {  
                    if($page_number==$currentPage) {
                        echo "<li class='page-item active'>";
                        echo '<a class="page-link" href = ""albumList.php?page=' . $page_number . '&genreFilter=' . $genreForFilter . '">' . $page_number . ' </a>'; 
                        echo            "</li>";
                    } else {
                        echo "<li class='page-item'>";
                            echo '<a class="page-link" href = "albumList.php?page=' . $page_number . '&genreFilter=' . $genreForFilter . '">' . $page_number . ' </a>'; 
                        echo            "</li>";
                    }
                }  

                if($currentPage!= 21 && $total_pages>1){
                    echo "<li class='page-item'>";
                    echo '<a class="page-link" href = ""albumList.php?page=' . $nextPage . '&genreFilter=' . $genreForFilter . '">' . 'Next' . ' </a>'; 
                    echo            "</li>";
                }
                echo "</ul>";
                echo "</nav>";
                
            }

        } else {
            echo "
            <div class='container'>

                <div class='row'>
                    <div class='col-12 align-items-center mt-5'>
                        <h3 class='text-center'> No albums with that category </h3>
                    </div>
                </div>
            </div>";
        }
    ?>

    <script>

        $(document).ready(function() {

            $('#genreFilterSelector').change(function(e) {

                    $("#genreFilterForm").submit();
            });

            $('#yearFilterForm').change(function(e) {

                    $("#yearFilterForm").submit();
            });
            

            // ADDING TO FAVOURITES
            
            <?php

            if (isset($_GET['addToFavNo'])) {

            ?>

                <?php $heartButtonNumber = $_GET['addToFavNo']; 

                $key = array_search($heartButtonNumber, array_column($potentialFavourites, 'album_id'));

                $chosenAlbum = $potentialFavourites[$key]['album_name']; 
                ?>

                $.ajax({
                    url: '../process_files/addAlbumToFavs.php',
                    type: 'POST',
                    data: jQuery.param({ favAlbumReturn: <?php echo "\"$chosenAlbum\""; ?>, username: <?php echo "\"$userName\""; ?>},) ,
                    contentType: 'application/x-www-form-urlencoded; charset=UTF-8',
                    success: function (response) {
                        alert("Added to favourites!");
                        window.location.assign("albumList.php?page=<?php echo $currentPage; ?>");

                    },
                    error: function () {
                        alert("error");
                        
                    }
                }); 
            <?php
            }
            ?>

            // ADDING TO OWNED
            
            <?php

            if (isset($_GET['addToOwnedNo'])) {

            ?>

                <?php $OwnedButtonNumber = $_GET['addToOwnedNo']; 

                $key = array_search($OwnedButtonNumber, array_column($potentialOwned, 'album_id'));
                
                $chosenAlbum = $potentialOwned[$key]['album_name']; 
                
                ?>

                $.ajax({
                url: '../process_files/addAlbumToOwned.php',
                type: 'POST',
                data: jQuery.param({ ownedAlbumToAdd: <?php echo "\"$chosenAlbum\""; ?>, username: <?php echo "\"$userName\""; ?>},) ,
                contentType: 'application/x-www-form-urlencoded; charset=UTF-8',
                success: function (response) {
                    alert("Added to owned!");
                    window.location.assign("albumList.php?page=<?php echo $currentPage; ?>");

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