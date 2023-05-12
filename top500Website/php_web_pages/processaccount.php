<?php   
        session_start();
        $sessionID = session_id();
        if(!isset($_POST['username']) || !isset($_POST['email']) || !isset($_POST['password']) || !isset($sessionID)) {
            header("Location: logout.php");
        }
        include("../functions/functions.php");
        
    
        $username = $_POST['username']; 
        $email = $_POST['email'];
        $password = $_POST['password'];

        if(isset($_POST['genre'])) {
            $favgenre = $_POST['genre'];
            $userFavGenreIDArray = array();
            $loopCount = count($favgenre);
        } else {
            $favgenre = null;
            $loopCount = null;
            $userFavGenreIDArray = null;
        }
        
        if(isset($_FILES['profilepic']['name'])) {
            $fname = $_FILES['profilepic']['name'];
            $temp = $_FILES['profilepic']['tmp_name'];
            $fsize = $_FILES['profilepic']['size'];
            $ftype = $_FILES['profilepic']['type'];

        } else {
            $fname = null;
            $temp = null;
            $fsize = null;
            $ftype = null;
            $userFavGenreIDArray = null;
            $loopCount = null;
        }
        


        if(($_FILES["profilepic"]["size"] > 1800000) || ($_FILES["profilepic"]["size"] == 0)) {
            echo "
                    <script>
                    alert('file upload exceeded the maximum of 1.8Mb! Please reduce the file size or try a different picture in profile settings.')
                    </script>
                ";
            
            $fname = null;
            $temp = null;
            $fsize = null;
            $ftype = null;

        } else if(isset($_FILES["profilepic"]["name"])) {
            $fname = $_FILES["profilepic"]["name"];
            $temp = $_FILES["profilepic"]["tmp_name"];
            $fsize = $_FILES["profilepic"]["size"];
            $ftype = $_FILES["profilepic"]["type"];

            $fileNameNoSpace = str_replace(' ', '', $fname);

            if($fname != false) {
                $fname = $fileNameNoSpace;

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

            $endpoint = "http://droddy03.webhosting6.eeecs.qub.ac.uk/top500API/api.php?addAccount";

            $postdata = http_build_query(
                array(
                    'username' => $username,
                    'email' => $email,
                    'password' => $password,
                    'favgenre' => $favgenre,
                    'fname' => $fname,
                    'temp' => $temp,
                    'fsize' => $fsize,
                    'ftype' => $ftype,
                    'sessionID' => $sessionID,
                    'loopCount' => $loopCount,
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
<!DOCTYPE html>
<html lang="en">

<?php 
$pageTitle = "Process Account";
include("../phpTemplates/header.php"); 

$navItem1 = "Home";
$navItem1Link = "index.php";

$navItem2 = "Album List";
$navItem2Link = "albumList.php";

if(isset($_COOKIE["Username"])){
            
        $navItem3 = "Profile Page <span class='sr-only'>(current)</span>";
        $navItem3Active = "active";
        $navItem3Link = "profile.php";

        $navItem4 = "Log Out";
        $navItem4Link = "logout.php";
}
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
            $navItem5Link = "index.php";
    }
    include("../phpTemplates/navbar.php"); 
    ?>
    
    <!-- Container -->
    <div class="container">
        <!-- Card -->
        <div class="card cardsClass text-center mt-2 top-buffer" id="signupSuccessCard">
            <!-- Card Header -->
            <div class="card-header" id="accountCreationCardHeaderTxt">
                Account Creation
            </div>
            <!-- Card Body -->
            <div class="card-body">
                    <?php
                        echo "<h1 class='text-center sign-up-success' id='accountSuccessH1Text'> Success! </h1>";
                    ?>
                <div class="mt-5 sign-up-success">
                    <?php 
                        echo "<h2 class=\"text-center\">Welcome $username</h2> <p id='accountSuccessPText'>Your account has successfully been created!</p>";
                        echo "<h2> Your favourite genres are:</h2>";

                        if($favgenre != null) {
                            echo "<ul>";
                            foreach($favgenre as $favgenreRow) {
                                echo "<li><h2>$favgenreRow</h2></li>";
                            }
                        
                            echo "<ul>";
                        } else {
                            echo "<ul>";
                                echo "<li><h2>No fav genres selected</h2></li>";
                        
                            echo "<ul>";
                        }

                        

                    ?>
                </div>
            </div>
            <a href="profile.php" class="btn btn-dark btn-lg">Continue to profile</a>
            <!-- Card footer -->
            <div class="card-footer text-muted">
                
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.slim.min.js"
        integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj"
        crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-fQybjgWLrvvRgtW6bFlB7jaZrFsaBXjsOMm/tB9LTS58ONXgqbR9W8oWht/amnpF"
        crossorigin="anonymous"></script>
    </script>

    
</body>

</html>