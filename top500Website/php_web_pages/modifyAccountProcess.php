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

    $userToBeModified = false;
    $modifyUsername = false;
    $modifyEmail = false;
    $modifyPassword = false;
    $modifyProfilePic = false;
    $modifyBio = false;
    $modifyUserRole = false;
    $deleteAccountBool = false;

    if(isset($_POST['userToBeModified'])) {
        $userToBeModified = $_POST['userToBeModified'];
    }
    if(isset($_POST['modifyUsername'])) {
        $modifyUsername = $_POST['modifyUsername'];
    }
    if(isset($_POST['modifyEmail'])) {
        $modifyEmail = $_POST['modifyEmail'];
    }
    if(isset($_POST['modifyPassword'])) {
        $modifyPassword = $_POST['modifyPassword'];
    }
    if(isset($_POST['modifyProfilePic'])) {
        $modifyProfilePic = $_POST['modifyProfilePic'];
    }
    if(isset($_POST['modifyBio'])) {
        $modifyBio = $_POST['modifyBio'];
    }
    if(isset($_POST['modifyUserRole'])) {
        $modifyUserRole = $_POST['modifyUserRole'];
    }
    if(isset($_POST['deleteAccountBool'])) {
        $deleteAccountBool = $_POST['deleteAccountBool'];
    }

    $endpoint = "http://droddy03.webhosting6.eeecs.qub.ac.uk/top500API/api.php?modifyAccountAdmin";

    $postdata = http_build_query(
        array(
            'userToBeModified' => $userToBeModified,
            'modifyUsername' => $modifyUsername,
            'modifyEmail' => $modifyEmail,
            'modifyPassword' => $modifyPassword,
            'modifyProfilePic' => $modifyProfilePic,
            'modifyBio' => $modifyBio,
            'modifyUserRole' => $modifyUserRole,
            'deleteAccountBool' => $deleteAccountBool
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

<script>
    window.location.assign("adminPage.php");
</script>