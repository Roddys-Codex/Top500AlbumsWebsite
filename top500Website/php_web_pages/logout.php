<?php

    session_start();

    $_SESSION = array();

    if (ini_get("session.use_cookies")) {
        $params = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000,
            $params["path"], $params["domain"],
            $params["secure"], $params["httponly"]
        );
    }

    session_destroy();
    
    header('HTTP/1.1 401 Unauthorized');
    $pageTitle = "logOut";
    include("../phpTemplates/header.php");
    echo "<script>
        del_cookie('Username');
        del_cookie('LoggedIn');
        window.location.assign('index.php');
    </script>";

?>

