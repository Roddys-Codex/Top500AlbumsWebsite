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
    
    include('../functions/functions.php');
?>

<?php
    $userToModify = $_POST['userToModify'];
?>

<!DOCTYPE html>
<?php 
$pageTitle = "Modify Account";
require("../phpTemplates/header.php"); 

$navItem1 = "Home";
$navItem1Link = "index.php";

$navItem2 = "Album List";
$navItem2Link = "albumList.php";

if(isset($_COOKIE["Username"])){
            
        $navItem3 = "Profile Page <span class='sr-only'>(current)</span>";
        $navItem3Active = "active";
        $navItem3Link = "profile.php";

        $navItem4 = null;
        $navItem4Link = null;

        $navItem5 = "Log Out";
        $navItem5Link = "logout.php";
    }
?>

<body>

    <?php require("../phpTemplates/navbar.php"); ?>

    <h1 id="albumListHeaderOne" class="text-center">Modify/Delete Album</h1>
    <h5 class="text-center">Leave empty any fields you wish to remain unchanged.</h5>
    
    <form class='text-center' role='form' id='modifyAlbumForm' action='modifyAccountProcess.php' method='POST'>
            <!-- FORM BODY -->
            <div class="form-group mx-auto d-hidden">
                <label for="userToBeModified" class="d-hidden"></label>
                <input type='hidden' name='userToBeModified' id='userToBeModified' value='<?php echo $userToModify; ?>'>
            </div>
            <div class="form-group mx-auto">
            <label for="modifyUsername" class="d-inline-block w-25">Username name: </label>
            <input type='text' name='modifyUsername' id='modifyUsername' value=''>
            </div>

            <div class="form-group mx-auto">
            <label for="modifyEmail" class="d-inline-block w-25">Email name: </label>
            <input type='text' name='modifyEmail' id='modifyEmail' value=''>
            </div>

            <div class="form-group mx-auto">
            <label for="modifyPassword" class="d-inline-block w-25">Password: </label>
            <input type='text' name='modifyPassword' id='modifyPassword' value=''>
            </div>

            <div class="form-group mx-auto">
            <label for="modifyProfilePic" class="d-inline-block w-25">Profile Pic URL: </label>
            <input type='text' name='modifyProfilePic' id='modifyProfilePic' value=''>
            </div>
            
            <div class="form-group mx-auto">
            <label for="modifyBio" class="d-inline-block w-25">Biography: </label>
            <input type='text' name='modifyBio' id='modifyBio' value=''>
            </div>
            
            <div class="form-group mx-auto">
            <label for="modifyUserRole" class="d-inline-block w-25">User Role: </label>
            <input type='text' name='modifyUserRole' id='modifyUserRole' value=''>
            </div>

            <div class="form-check">
            <label class="form-check-label d-inline-block w-25" for="deleteAccountCheck">
                SELECT ACCOUNT FOR DELETION
            </label>
            <input class="form-check-input" type="checkbox" value="true" id="deleteAccountCheck" name="deleteAccountBool">
            </div>

            <button type="submit">Submit changes</button>
    </form>
</body>
</html>