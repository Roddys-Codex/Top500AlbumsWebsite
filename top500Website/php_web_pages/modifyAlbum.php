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
    $albumToModify = $_POST['albumToModify'];
?>

<!DOCTYPE html>
<?php 
$pageTitle = "Modify Album";
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

    <h1 id="albumListHeaderOne" class="text-center">Modify/Delete Album</h1>
    <h5 class="text-center">Leave empty any fields you wish to remain unchanged.</h5>
    
    <form class='text-center' role='form' id='modifyAlbumForm' action='modifyAlbumProcess.php' method='POST'>
            <!-- FORM BODY -->
            <div class="form-group mx-auto d-hidden">
                <label for="albumToBeModified" class="d-hidden"></label>
                <input type='hidden' name='albumToBeModified' id='albumToBeModified' value='<?php echo $albumToModify; ?>'>
            </div>
            <div class="form-group mx-auto">
            <label for="modifyAlbumName" class="d-inline-block w-25">Album name: </label>
            <input type='text' name='modifyAlbumName' id='modifyAlbumName' value=''>
            </div>

            <div class="form-group mx-auto">
            <label for="modifyAlbumArtistName" class="d-inline-block w-25">Artist name: </label>
            <input type='text' name='modifyAlbumArtistName' id='modifyAlbumArtistName' value=''>
            </div>

            <div class="form-group mx-auto">
            <label for="modifyAlbumYear" class="d-inline-block w-25">Album year: </label>
            <input type='text' name='modifyAlbumYear' id='modifyAlbumYear' value=''>
            </div>

            <div class="form-group mx-auto">
            <label for="modifyAlbumRank" class="d-inline-block w-25">Album rank: </label>
            <input type='text' name='modifyAlbumRank' id='modifyAlbumRank' value=''>
            </div>
            
            <div class="form-group mx-auto">
            <label for="modifyAlbumImgUrl" class="d-inline-block w-25">Album img URL: </label>
            <input type='text' name='modifyAlbumImgUrl' id='modifyAlbumImgUrl' value=''>
            </div>
            
            <div class="form-group mx-auto">
            <label for="modifyAlbumPreviewUrl" class="d-inline-block w-25">Album preview URL: </label>
            <input type='text' name='modifyAlbumPreviewUrl' id='modifyAlbumPreviewUrl' value=''>
            </div>

            <div class="form-group mx-auto">
            <label for="modifyAlbumCollectionViewUrl" class="d-inline-block w-25">Album collection view URL: </label>
            <input type='text' name='modifyAlbumCollectionViewUrl' id='modifyAlbumCollectionViewUrl' value=''>
            </div>  

            <div class="form-group mx-auto">
            <label for="modifyAlbumCollectionPrice" class="d-inline-block w-25">Album collection price: </label>
            <input type='text' name='modifyAlbumCollectionPrice' id='modifyAlbumCollectionPrice' value=''>
            </div>

            <div class="form-group mx-auto">
            <label for="modifyAlbumSpotify" class="d-inline-block w-25">Spotify Image URL: </label>
            <input type='text' name='modifyAlbumSpotifyURL' id='modifyAlbumSpotify' value=''>
            </div>

            <div class="form-check">
            <label class="form-check-label d-inline-block w-25" for="deleteAlbumCheck">
                SELECT ALBUM FOR DELETION
            </label>
            <input class="form-check-input" type="checkbox" value="true" id="deleteAlbumCheck" name="deleteAlbumBool">
            </div>

            <button type="submit">Submit changes</button>
    </form>
</body>
</html>