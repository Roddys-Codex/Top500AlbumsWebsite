<?php
        if(isset($_COOKIE['Username'])){
            $user = $_COOKIE['Username'];
            $loggedIn = $_COOKIE['LoggedIn'];
            
            if(isset($_POST['rememberMe'])) {
                $rememberUser = createCookie("RememberMe", true);
            }
        } else {
            echo "<script>";
            echo "window.location.assign(\"index.php\")";
            echo "</script>";
        }    
?>

<?php
    echo "
    <script>
        window.location.assign('profile.php');
    </script>
    ";
?>