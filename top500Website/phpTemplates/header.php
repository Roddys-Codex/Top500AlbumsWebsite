<?php
echo
"<head>
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

    <!-- SCSS STAR RATING -->
    <link rel='stylesheet' href='../scss/appSCSSS.css'>

</head>";

echo 
"<script src='https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/js/bootstrap.bundle.min.js' integrity='sha384-fQybjgWLrvvRgtW6bFlB7jaZrFsaBXjsOMm/tB9LTS58ONXgqbR9W8oWht/amnpF' crossorigin='anonymous'></script>";

echo "<script src='../functions/javascriptFunctions.js'></script>";

echo "<script>
        $(document).ready(function(){
            $('#logOutButton').click(function(){
                del_cookie('Username');
                del_cookie('LoggedIn');
                window.location.assign('../php_web_pages/logout.php');
                
            });
        });
    </script>";
?>

