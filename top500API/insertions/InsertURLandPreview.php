<?php 
    include("dbconn.php");

    $read = "SELECT * FROM `album`";

    $dbresult = $conn->query($read);

    if(!$dbresult) {
        echo $conn->error;
    }

    // Pagination
    // Store no rows per page
    $limit = 24; 

    // Total Rows
    $total_rows = $dbresult->num_rows; 

    // Required no of pages
    $total_pages = ceil($total_rows / $limit);   

    // Get current page
    if (!isset ($_GET['page']) ) {  

        $page_number = 1;  

    } else {  

        $page_number = $_GET['page'];  

    } 

    // Initial page number
    $initial_page = ($page_number-1) * $limit; 

    // Data for selected rows per page  
    $getQuery = "SELECT * FROM `album` LIMIT $initial_page, $limit";

    $rowsPerPageResult = $conn->query($getQuery);

    function searchTwo($searchTerm, $artistName){

                $url = 'https://itunes.apple.com/search?term=' . urlencode($searchTerm).'&limit=50';

                $result = file_get_contents($url);

                $json = json_decode($result);
                $first = $json->results;
                foreach($first as $objRow) {
                    if((stristr($artistName, $objRow->artistName)) && ($objRow->artworkUrl100 != false) && (strlen($objRow->artworkUrl100)>20)) {
                        $obj = $objRow;
                        break;
                    } else if((stristr($objRow->artistName, $artistName)) && ($objRow->artworkUrl100 != false) && (strlen($objRow->artworkUrl100)>20)) {
                        $obj = $objRow;
                        break;
                    } else {

                        $url = 'https://itunes.apple.com/search?term=' . urlencode($artistName).'&media=musicentity=allArtist&attribute=allArtistTerm';

                        $result = file_get_contents($url);

                        $json = json_decode($result);
                        $second = $json->results;
                        foreach($second as $objRowTwo) {
                            if((stristr($artistName, $objRowTwo->artistName)) && ($objRowTwo->artworkUrl100 != false) && (strlen($objRowTwo->artworkUrl100)>20)) {
                                $obj = $objRowTwo;
                                break;
                            } else if((stristr($objRowTwo->artistName, $artistName)) && ($objRowTwo->artworkUrl100 != false) && (strlen($objRowTwo->artworkUrl100)>20)) {
                                $obj = $objRowTwo;
                                break;
                            } 
                        }
                    }
                }

                $pic = $obj->artworkUrl100;

                return $pic;
                }
    function searchThree($searchTerm, $artistName){

                $url = 'https://itunes.apple.com/search?term=' . urlencode($searchTerm).'&limit=50';

                $result = file_get_contents($url);

                $json = json_decode($result);
                $first = $json->results;
                foreach($first as $objRow) {
                    if((stristr($artistName, $objRow->artistName)) && ($objRow->previewUrl != false) && (strlen($objRow->artworkUrl100)>20)) {
                        $obj = $objRow;
                        break;
                    } else if((stristr($objRow->artistName, $artistName)) && ($objRow->previewUrl != false) && (strlen($objRow->artworkUrl100)>20)) {
                        $obj = $objRow;
                        break;
                    } else {

                        $url = 'https://itunes.apple.com/search?term=' . urlencode($artistName).'&media=musicentity=allArtist&attribute=allArtistTerm';

                        $result = file_get_contents($url);

                        $json = json_decode($result);
                        $second = $json->results;
                        foreach($second as $objRowTwo) {
                            if((stristr($artistName, $objRowTwo->artistName)) && ($objRowTwo->previewUrl != false)) {
                                $obj = $objRowTwo;
                                break;
                            } else if((stristr($objRowTwo->artistName, $artistName)) && ($objRowTwo->previewUrl != false)) {
                                $obj = $objRowTwo;
                                break;
                            } 
                        }
                    }
                }

                $preview = $obj->previewUrl;

                return $preview;
                }

    function searchFour($searchTerm, $artistName){

                $url = 'https://itunes.apple.com/search?term=' . urlencode($searchTerm).'&limit=50';

                $result = file_get_contents($url);

                $json = json_decode($result);
                $first = $json->results;
                foreach($first as $objRow) {
                    if((stristr($artistName, $objRow->artistName)) && ($objRow->collectionViewUrl != false)) {
                        $obj = $objRow;
                        break;
                    } else if((stristr($objRow->artistName, $artistName)) && ($objRow->collectionViewUrl != false)) {
                        $obj = $objRow;
                        break;
                    } else {

                        $url = 'https://itunes.apple.com/search?term=' . urlencode($artistName).'&media=musicentity=allArtist&attribute=allArtistTerm';

                        $result = file_get_contents($url);

                        $json = json_decode($result);
                        $second = $json->results;
                        foreach($second as $objRowTwo) {
                            if((stristr($artistName, $objRowTwo->artistName)) && ($objRowTwo->collectionViewUrl != false)) {
                                $obj = $objRowTwo;
                                break;
                            } else if((stristr($objRowTwo->artistName, $artistName)) && ($objRowTwo->collectionViewUrl != false)) {
                                $obj = $objRowTwo;
                                break;
                            } 
                        }
                    }
                }

                $collectionViewURL = $obj->collectionViewUrl;

                return $collectionViewURL;
                }

    function searchFive($searchTerm, $artistName){

                $url = 'https://itunes.apple.com/search?term=' . urlencode($searchTerm).'&limit=50';

                $result = file_get_contents($url);

                $json = json_decode($result);
                $first = $json->results;
                foreach($first as $objRow) {
                    if((stristr($artistName, $objRow->artistName)) && ($objRow->collectionPrice != false)) {
                        $obj = $objRow;
                        break;
                    } else if((stristr($objRow->artistName, $artistName)) && ($objRow->collectionPrice != false)) {
                        $obj = $objRow;
                        break;
                    } else {

                        $url = 'https://itunes.apple.com/search?term=' . urlencode($artistName).'&media=musicentity=allArtist&attribute=allArtistTerm';

                        $result = file_get_contents($url);

                        $json = json_decode($result);
                        $second = $json->results;
                        foreach($second as $objRowTwo) {
                            if((stristr($artistName, $objRowTwo->artistName)) && ($objRowTwo->collectionPrice != false)) {
                                $obj = $objRowTwo;
                                break;
                            } else if((stristr($objRowTwo->artistName, $artistName)) && ($objRowTwo->collectionPrice != false)) {
                                $obj = $objRowTwo;
                                break;
                            } 
                        }
                    }
                }

                $collectionPrice = $obj->collectionPrice;

                return $collectionPrice;
                }

    function searchSix($searchTerm, $artistName){

                $url = 'https://itunes.apple.com/search?term=' . urlencode($searchTerm).'&limit=50';

                $result = file_get_contents($url);
                $json = json_decode($result);
                $first = $json->results;

                foreach($first as $objRow) {
                    if((stristr($artistName, $objRow->artistName)) && ($objRow->artistViewUrl != false)) {
                        $obj = $objRow;
                        break;
                    } else if((stristr($objRow->artistName, $artistName)) && ($objRow->artistViewUrl != false)) {
                        $obj = $objRow;
                        break;
                    } else {
                        $url = 'https://itunes.apple.com/search?term=' . urlencode($artistName).'&media=musicentity=allArtist&attribute=allArtistTerm';
                        $result = file_get_contents($url);
                        $json = json_decode($result);
                        $second = $json->results;
                        foreach($second as $objRowTwo) {
                            if((stristr($artistName, $objRowTwo->artistName)) && ($objRowTwo->artistViewUrl != false)) {
                                $obj = $objRowTwo;
                                break;
                            } else if((stristr($objRowTwo->artistName, $artistName)) && ($objRowTwo->artistViewUrl != false)) {
                                $obj = $objRowTwo;
                                break;
                            } 
                        }
                    }
                }
                $artistViewURL = $obj->artistViewUrl;

                return $artistViewURL;
                }

?>

<!DOCTYPE html>
<head>
    <meta charset='UTF-8'>
    <meta http-equiv='X-UA-Compatible' content='IE=edge'>
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <title>$pageTitle</title>

    <!-- jQUERY -->
    <script src='https://code.jquery.com/jquery-3.6.0.js'
    integrity='sha256-H+K7U5CnXl1h5ywQfKtSj8PCmoN9aaq30gDh27Xc0jk='
    crossorigin='anonymous'></script>

    <!-- Font Awesome -->
    <script src='https://use.fontawesome.com/releases/v5.15.4/js/all.js' crossorigin='anonymous'></script>

    <!-- Google Fonts -->
    <link href='https://fonts.googleapis.com/css?family=Montserrat:400,700' rel='stylesheet' type='text/css' />

    <link href='https://fonts.googleapis.com/css?family=Roboto+Slab:400,100,300,700' rel='stylesheet' type='text/css' />

    <link rel='preconnect' href='https://fonts.googleapis.com'>

    <link rel='preconnect' href='https://fonts.gstatic.com' crossorigin>
    <link href='https://fonts.googleapis.com/css2?family=The+Nautigal&display=swap' rel='stylesheet'>

    <link rel='preconnect' href='https://fonts.googleapis.com'>
    
    <link rel='preconnect' href='https://fonts.gstatic.com' crossorigin>
    <link href='https://fonts.googleapis.com/css2?family=Metal+Mania&display=swap' rel='stylesheet'>

    <!-- Bootstrap -->
    <link rel='stylesheet' href='https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/css/bootstrap.min.css'
        integrity='sha384-zCbKRCUGaJDkqS1kPbPd7TveP5iyJE0EjAuZQTgFLD2ylzuqKfdKlfG/eSrtxUkn' crossorigin='anonymous'>

    <!-- JS, Popper.js -->
    <script src='https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js'
        integrity='sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo'
        crossorigin='anonymous'></script>
    <script src='https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js'
        integrity='sha384-OgVRvuATP1z7JjHLkuOU7Xw704+h835Lr+6QL9UvYjZE3Ipu6Tp75j7Bh/kR0JKI'
        crossorigin='anonymous'></script>

    <!-- Latest compiled and minified CSS -->
    <link rel='stylesheet' href='https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.14/dist/css/bootstrap-select.min.css'>
    
    <!-- Latest compiled and minified JavaScript -->
    <script src='https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.14/dist/js/bootstrap-select.min.js'></script>
    
    <link rel='stylesheet' href='../app.css'>
    <!-- <link rel='stylesheet' href='app_first_draft.css'> -->

    <!-- SCSS STAR RATING -->
    <link rel='stylesheet' href='../scss/appSCSSS.css'>

</head>
<script>
    $(document).ready(function(){
        <?php $nextPage = $page_number+1; ?>
        var nextPage = <?php echo $nextPage ?>;
        var windowURL = "http://droddy03.webhosting6.eeecs.qub.ac.uk/projectcode/insertions/InsertURLandPreview.php?".concat("page=",nextPage);

        window.onload = function() {
            window.location.assign(windowURL);
        };

    });
</script>
<body>

    <?php 
        
        while($row = $rowsPerPageResult->fetch_assoc()){

            $albumName = $row['album_name'];
            $albumRank = $row['album_ranking'];
            $albumID = (int)$row['album_id'];
            $albumArtistID = (int)$row['artist_id'];

            $readArtist = "SELECT * FROM `artist` WHERE artist_id=$albumArtistID";

            $artistResult = $conn->query($readArtist);

            if(!$artistResult) {
                echo $conn->error;
            }

            $rowArist = $artistResult->fetch_assoc();
            $artistName = $rowArist['artist_name'];

            $searchQuery = $albumName;
            
            $pic = searchTwo($searchQuery, $artistName);
            $preview = searchThree($searchQuery, $artistName);
            $collectionView = searchFour($searchQuery, $artistName);
            $collectionPriceItem = searchFive($searchQuery, $artistName); 
            $artistViewURLItem = searchSix($searchQuery, $artistName); 

            if($pic != false) {
                $albumURLInsertQuery = "UPDATE album SET album_image_url = '$pic' WHERE album_id = $albumID";
                $albumURLinsertResult = $conn->query($albumURLInsertQuery);
                if(!$albumURLinsertResult) {
                    echo $conn->error;
                } else {
                    echo nl2br("$albumName was updated with picture URL $pic\n\n");
                }
            }

            if($preview != false) {
                $albumPreviewInsertQuery = "UPDATE album SET album_preview_url = '$preview' WHERE album_id = $albumID";
                $albumPreviewinsertResult = $conn->query($albumPreviewInsertQuery);
                if(!$albumPreviewInsertQuery) {
                    echo $conn->error;
                } else {
                    echo nl2br("$albumName preview was updated with URL $preview\n\n");
                }
            }

            if($collectionView != false) {
                $albumCollectionInsertQuery = "UPDATE album SET album_collection_view_url = '$collectionView' WHERE album_id = $albumID";
                $albumCollectioninsertResult = $conn->query($albumCollectionInsertQuery);
                if(!$albumCollectioninsertResult) {
                    echo $conn->error;
                } else {
                    echo nl2br("$albumName collection view was updated with URL $collectionView\n\n");
                }
            }

            if($collectionPriceItem != false) {
                $albumCollectionPriceInsertQuery = "UPDATE album SET album_collection_price = '$collectionPriceItem' WHERE album_id = $albumID";
                $albumCollectionPriceinsertResult = $conn->query($albumCollectionPriceInsertQuery);
                if(!$albumCollectionPriceinsertResult) {
                    echo $conn->error;
                } else {
                    echo nl2br("$albumName collection price was updated with price: $collectionPriceItem\n\n");
                }
            }
            
            if($artistViewURLItem != false) {
                $albumartistViewURLInsertQuery = "UPDATE artist SET artist_view_url = '$artistViewURLItem' WHERE artist_id = $albumArtistID";
                $albumArtistViewURLinsertResult = $conn->query($albumartistViewURLInsertQuery);
                if(!$albumArtistViewURLinsertResult) {
                    echo $conn->error;
                } else {
                    echo nl2br("$artistName artist view was updated with url: $artistViewURLItem\n\n");
                }
            }

        }

        for($page_number = 1; $page_number<= $total_pages; $page_number++) {  

        echo '<a href = "InsertURLandPreview.php?page=' . $page_number . '">' . $page_number . ' </a>';  

        } 
    ?>

    

    
</body>
</html>