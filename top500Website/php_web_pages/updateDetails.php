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
    include("../functions/functions.php");

    if(isset($_POST['newUsername'])) {
        $newUsername = $_POST['newUsername'];
    } else {
        $newUsername = null;
    }
    if(isset($_POST['newEmail'])) {
        $newEmail = $_POST['newEmail'];
    } else {
        $newEmail = null;
    } 
    if(isset($_POST['newPassword'])) {
        $newPassword = $_POST['newPassword'];
    } else {
        $newPassword = null;
    }
    if(isset($_POST['genre'])) {
        $newFavgenre = $_POST['genre'];
    } else {
        $newFavgenre = null;
    }

    $fileNameNoSpace = false;
    $fname = null;
    $temp = null;
    $fsize = null;
    $ftype = null;
    
    if(($_FILES["profilepic"]["size"] > 1800000) || ($_FILES["profilepic"]["size"] == 0)) {
        
        if(isset($_FILES["profilepic"]["name"])) {
            echo "
            <script>
                alert('file upload exceeded the maximum of 1.8Mb! Please reduce the file size or try a different picture.')
            </script>
            ";
        }
        
    
        $fname = null;
        $temp = null;
        $fsize = null;
        $ftype = null;
        

    } else if(isset($_FILES["profilepic"]["name"])) {
        
        $fname = $_FILES["profilepic"]["name"];
        $temp = $_FILES["profilepic"]["tmp_name"];
        $fsize = $_FILES["profilepic"]["size"];
        $ftype = $_FILES["profilepic"]["type"];
        $fileNameNoSpace = $string = str_replace(' ', '', $fname);

        if($fname != false) {
        
        if(move_uploaded_file($temp, '/var/www/vhosts/droddy03.webhosting6.eeecs.qub.ac.uk/httpdocs/top500Website/uploads/'.$fileNameNoSpace)){

            error_log(print_r("THANKS FOR UPLOADING", true));
            
        } else{      

            error_log(print_r("PROBLEM WITH UPLOAD", true));
            
        }
    }
    } else {
        $fname = null;
        $temp = null;
        $fsize = null;
        $ftype = null;
    }
    
    if((isset($_POST['newUsername'])) && ($_POST['newUsername'] != false)) {
        createCookie("Username", $newUsername);
    } 

    $endpoint = "http://droddy03.webhosting6.eeecs.qub.ac.uk/top500API/api.php?updateSettings";

    $postdata = http_build_query(
        array(
            'currentUserID' => $dataUser['user_id'],
            'newUserName' => $newUsername,
            'newEmail' => $newEmail,
            'newPassword' => $newPassword,
            'newFavGenres' => $newFavgenre,
            'fname' => $fileNameNoSpace,
            'ftemp' => $temp,
            'fsize' => $fsize,
            'ftype' => $ftype
        )
    );

    $opts = array(

        'http' => array(
            'method' => 'POST',
            'header' => 'Content-Type: application/x-www-form-urlencoded',
            'content'=> $postdata
        )
    );

    $context = stream_context_create($opts);
    $resource = file_get_contents($endpoint, false, $context);

?>  

<!DOCTYPE html>
<html lang="en">

<?php 
$pageTitle = "Update Details";
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

    require("../phpTemplates/navbar.php"); ?>
    <script>
        $(document).ready(function(){
            
            window.location.href = "profile.php";

        });
    </script>
    <h1 class='text-center mx-auto'>Processing...</h1>

    
    
    <?php require("../phpTemplates/footer.php"); ?>

</body>

</html>