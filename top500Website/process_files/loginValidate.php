<?php
    session_start();
    session_regenerate_id(true);
    $sessionID = session_id();
    include("../functions/functions.php");
    
    $userNameCheck = $_POST['usernameCheck'];
    $passwordCheck = $_POST['passwordCheck'];

    // CONFIRM USERNAME ENTERED EXISTS
    if($userNameCheck == false) {

        $userValid = "No entry";
        
    } else {

        $usernameSearchResult = searchForUserBool($userNameCheck);
        
        if(($usernameSearchResult === true)) {

            $userValid = true;
        
        } else if($usernameSearchResult === false) {
                
            $userValid = false;
                
        }
    }

    // CONFIRM PASSWORD IS CORRECT
    if(($passwordCheck == false)) {

        $passwordValid = "No entry";

    } else {

        $passwordResult = validatePassword($userNameCheck, $passwordCheck);

        if(($passwordResult === true)) {

            $passwordValid = true;
            
        } else if($passwordResult === false) {
                
            $passwordValid = false;
                
        }

    }
    
    $returnResult = array(
        'userValid' => $userValid,
        'passwordValid' => $passwordValid
    );
    
    echo json_encode($returnResult);

    if(($returnResult['userValid'] === true) && ($returnResult['passwordValid'] === true)) {

        $endpointSessionInsert = "http://droddy03.webhosting6.eeecs.qub.ac.uk/top500API/api.php?addSessionToAccount";

        $postdata = http_build_query(
            array(
                'sessionID' => $sessionID,
                'userName' => $userNameCheck
            )
        );

        $opts = array(

            'http' => array(
                'method' => 'POST',
                'header' => 'Content-Type: application/x-www-form-urlencoded',
                'content'=> $postdata
            )
        );
        
        $contextSession = stream_context_create($opts);
        $resourceJwt = file_get_contents($endpointSessionInsert, false, $contextSession);

        createCookie("Username", $userNameCheck);
        createCookie("LoggedIn", true);
        $_SESSION['authenticated'] = true;

    }

?>