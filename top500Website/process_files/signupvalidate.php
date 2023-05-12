<?php
    session_start();
    if((!isset($_POST['profileUpdateValidate']))) {
        session_regenerate_id(true);
    }
    
    $sessionID = session_id();

    include("../functions/functions.php");

    $userNameCheck = $_POST['usernameCheck'];
    $emailCheck = $_POST['emailCheck'];
    $passwordCheck = $_POST['passwordCheck'];

    if(isset($_POST['usernameValidate']) && isset($_POST['passwordValidate'])) {
        $usernameValidate = $_POST['usernameValidate'];
        $passwordValidate = $_POST['passwordValidate'];

        $userCredentialsValidated = validatePassword($usernameValidate, $passwordValidate);
    }

    if($userNameCheck == false) {

        $userExists = "No entry";
        
    } else {

        $usernameSearchResult = searchForUserBool($userNameCheck);
        
        if(($usernameSearchResult == true)) {

            $userExists = true;
        
        } else if($usernameSearchResult == false) {
                
            $userExists = false;
                
        }
    }

    if(($emailCheck == false)) {

        $emailExists = "No entry";

    } else {

        $emailSearchResult = searchForEmail($emailCheck);

        if(($emailSearchResult == true)) {

            $emailExists = true;
            
        } else if($emailSearchResult == false) {
                
            $emailExists = false;
                
        }

    }

    if(($passwordCheck == false)) {

        $passwordExists = "No entry";

    } else {
        $passwordExists = true;
    }

    if(isset($_POST['usernameValidate']) && isset($_POST['passwordValidate'])) {
        $returnResult = array(
            'userExists' => $userExists,
            'emailExists' => $emailExists,
            'passwordExists' => $passwordExists,
            'userValidated' => $userCredentialsValidated
        );
    } else {
        $returnResult = array(
            'userExists' => $userExists,
            'emailExists' => $emailExists,
            'passwordExists' => $passwordExists
        );
    }
    
    if(($userExists === false) && ($emailExists === false) && ($passwordExists === true)) {

        createCookie("Username", $userNameCheck);
        createCookie("LoggedIn", true);
        $_SESSION['authenticated'] = true;
    }
    
    echo json_encode($returnResult);

?>