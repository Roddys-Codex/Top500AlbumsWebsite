<!DOCTYPE html>
<html lang="en">
<?php
    $pageTitle = "Home Page";
    require("../phpTemplates/header.php");
?>
<?php

    $navItem1 = "Home <span class='sr-only'>(current)</span>";
    $navItem1Active = "active";
    $navItem1Link = "index.php";

    $navItem2 = "Album List";
    $navItem2Link = "albumList.php";

    $navItem3 = "Register";
    $navItem3Link = "signup.php";
    
    $navItem4 = "Log In";
    $navItem4Link = "login.php";



    if(isset($_COOKIE["Username"])){
        $userName = $_COOKIE["Username"];
        $navItem3 = "Profile Page";
        $navItem3Link = "profile.php";

        $navItem4 = null;
        $navItem4Link = null;
        
        $navItem5 = "Log Out";
        $navItem5Link = "logout.php";

        echo "<script>
                $(document).ready(function() {
                    $('#createAccountButton').hide();
                    $('#signUpNowTxt').hide();
                    $('#continueAsGuestButton').hide();
                });
                </script>";

        $endpointFavGenres = "http://droddy03.webhosting6.eeecs.qub.ac.uk/top500API/api.php?favouriteGenres=true&userName=$userName";

        $resourceFavGenres = file_get_contents($endpointFavGenres, false, stream_context_create());

        $dataFavGenres = json_decode($resourceFavGenres, true);

        $cardPrint = "<!-- Sub Heading - Best Of -->
        <div class='row'>
            <h1 class='col-12 text-center display-2 glitch'>Based on your favourite genres...</h1>
        </div>";

    } else {

        $cardPrint = "<!-- Sub Heading - Best Of -->
        <div class='row'>
            <h1 class='col-12 text-center display-2 glitch'>Pick your poison...</h1>
        </div>

        <!-- ROW - Best Of Content  -->
        <div class='row justify-content-center'>

            <!-- Best Blues -->
            <div class='p-4 col-12 col-md-5 col-lg-4'>
                <div class='card' style='width: 18rem;'>
                    <img src='../img/bass_guitar.jpg' class='card-img-top border' alt='...'>
                    <div class='card-body text-center'>
                        <p class='card-text text-center text-dark'>Blues</p>
                        <a href='albumList.php?genreFilter=Funk+%2F+Soul' class='btn btn-dark btn-sm stretched-link text-'>Best Funk/Soul</a>
                    </div>
                </div>
            </div>

            <!-- Best Rock -->
            <div class='p-4 col-12 col-md-5 col-lg-4'>
                <div class='card' style='width: 18rem;'>
                    <img src='../img/Guitar_player.jpg' class='card-img-top border' alt='...'>
                    <div class='card-body text-center'>
                        <p class='card-text text-center text-dark'>Rock</p>
                        <a href='albumList.php?genreFilter=Rock' class='btn btn-dark btn-sm stretched-link'>Best Rock</a>
                    </div>
                </div>
            </div>

            <!-- Best Electronic -->
            <div class='p-4 col-12 col-md-5 col-lg-4'>
                <div class='card' style='width: 18rem;'>
                    <img src='../img/DJ.jpg' class='card-img-top border' alt='...'>
                    <div class='card-body text-center'>
                        <p class='card-text text-center text-dark'>Electronic</p>
                        <a href='albumList.php?genreFilter=Electronic' class='btn btn-dark btn-sm stretched-link'>Best Electronic</a>
                    </div>
                </div>
            </div>
        </div>";
    }
?>  

<body>
    <?php require("../phpTemplates/navbar.php"); ?>

    <!-- Master Header -->
    <header class="masterheader sliding-background border">

        <!-- Container -->
        <div class="container">
            
            <!-- Flicker Div -->
            <div class="row sign fast-flicker" id="flashingHeader">
            
                <!-- MAIN LOGO -->
                <div class="masterheader-main text-uppercase shine" id="shiningHeader"><span class="fast-flicker" id="rollingHeaderText">Rolling Stone</span></div>
            </div>
            
            <!-- Flicker Div -->
            <div class="row sign" id="flashingSubHeader">
            
                <!-- SUB-HEADING -->
                <div class="masterheader-sub sub-flicker-fast">Top 500 Albums of All Time</div>
            </div>
        </div>
    </header>

    <!-- ************** CONTAINER ***************** -->
    <div class="container px-4" id="indexBody">

        <?php echo $cardPrint; 
        if(isset($_COOKIE["Username"])) {

            while($dataFavGenres != false) {
                $row = array_shift($dataFavGenres);

                if($row != false) {
                    $urlEncodedGenre = urlencode($row['genre_name']);
                    echo "
                    <!-- ROW - Best Of Content  -->
                <div class='row justify-content-center'>";

                    echo "
                    <div class='p-4 col-12 col-md-5 col-lg-4'>
                        <div class='card h-100' style='width: 18rem;'>
                            <img src='{$row['genre_img_url']}' class='card-img-top border' alt='...'>
                            <div class='card-body text-center'>
                                <p class='card-text text-center text-dark'>{$row['genre_name']}</p>
                                <a href='albumList.php?genreFilter=$urlEncodedGenre' class='btn btn-dark btn-sm stretched-link text-'>Best {$row['genre_name']}</a>
                            </div>
                        </div>
                    </div>
                    ";
                }
                
                $row = array_shift($dataFavGenres);

                if($row != false) {
                    $urlEncodedGenre = urlencode($row['genre_name']);

                    echo "
                    <div class='p-4 col-12 col-md-5 col-lg-4'>
                        <div class='card h-100' style='width: 18rem;'>
                            <img src='{$row['genre_img_url']}' class='card-img-top border' alt='...'>
                            <div class='card-body text-center'>
                                <p class='card-text text-center text-dark'>{$row['genre_name']}</p>
                                <a href='albumList.php?genreFilter=$urlEncodedGenre' class='btn btn-dark btn-sm stretched-link'>Best {$row['genre_name']}</a>
                            </div>
                        </div>
                    </div>
                    ";
                }

                $row = array_shift($dataFavGenres);

                if($row != false) {
                    $urlEncodedGenre = urlencode($row['genre_name']);

                    echo "
                    <div class='p-4 col-12 col-md-5 col-lg-4'>
                        <div class='card h-100' style='width: 18rem;'>
                            <img src='{$row['genre_img_url']}' class='card-img-top border' alt='...'>
                            <div class='card-body text-center'>
                                <p class='card-text text-center text-dark'>{$row['genre_name']}</p>
                                <a href='albumList.php?genreFilter=$urlEncodedGenre' class='btn btn-dark btn-sm stretched-link'>Best {$row['genre_name']}</a>
                            </div>
                        </div>
                    </div>
                </div>";
                }
            }
        }
        ?>
        
        <!-- ROW - CAROUSEL -->
        <div class="row">

            <!-- CAROUSEL CONTAINER -->
            <div id="carouselExampleFade" class="carousel slide carousel-fade" data-ride="carousel">

                <!-- INNER CAROUSEL -->
                <div class="carousel-inner">

                    <!-- IMAGE ONE -->
                    <div class="carousel-item active">
                        <img src="../img/Guitar_player.jpg" class="d-block w-100" alt="...">
                    </div>

                    <!-- IMAGE TWO -->
                    <div class="carousel-item">
                        <img src="../img/live_concert3.jpg" class="d-block w-100" alt="...">
                    </div>

                    <!-- IMAGE THREE -->
                    <div class="carousel-item">
                        <img src="../img/rolling_stones.jpg" class="d-block w-100" alt="...">
                    </div>
                </div>

                <!-- CAROUSEL PREVIOUS -->
                <button class="carousel-control-prev" type="button" data-target="#carouselExampleFade" data-slide="prev">
                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                    <span class="sr-only">Previous</span>
                </button>

                <!-- CAROUSEL NEXT -->
                <button class="carousel-control-next" type="button" data-target="#carouselExampleFade" data-slide="next">
                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                    <span class="sr-only text-primary">Next</span>
                </button>
            </div>
        </div>

        <!-- ROW - SIGNUP -->
        <div class='row'>

            <!-- SIGNUP CARD CONTAINER -->
            <div class='p-4 col-12 card'>

                <!-- CARD BODY -->
                <div class='card-body text-center'>

                    <!-- CARD TEXT -->
                    <p class='card-text text-center text-dark' id='signUpNowTxt'>Sign up now!</p>

                    <!-- TEXT LINK -->
                    <a href='signup.php' class='btn btn-dark btn-sm stretched-link' id='createAccountButton'>create account</a>
                </div>
            </div>
        </div>

        <!-- GUEST ACCESS -->
        <div class='row'>

            <!-- CONTAINER -->
            <div class='p-4 col-2 mx-auto'>

                    <!-- LINK -->
                    <a href='albumList.php' class='btn btn-dark btn-sm stretched-link text-center' id='continueAsGuestButton'>continue as guest</a>
            </div>
        </div>

    </div>

    <?php require("../phpTemplates/footer.php"); ?>

    <script>
        $(document).ready(function () {
            $('.carousel').carousel({
                interval: 2000
            })
            
            $("#flashingHeader").click(function(){

                if($("#flashingHeader").hasClass("fast-flicker")) {

                    $("#flashingHeader").removeClass("fast-flicker");
                    $("#flashingHeader").removeClass("glitchMain");
                    $("#flashingHeader").removeClass("sign");
                    $("#rollingHeaderText").removeClass("fast-flicker");
                    $("#rollingHeaderText").removeClass("glitch");
                } else {
                    $("#flashingHeader").addClass("fast-flicker");
                    $("#flashingHeader").addClass("glitchMain");
                    $("#flashingHeader").addClass("sign");
                    $("#rollingHeaderText").addClass("fast-flicker");
                    $("#rollingHeaderText").addClass("glitch");
                }
            });
            

            $("#flashingSubHeader").click(function(){
                if ($("#shiningHeader").hasClass("shine")) {

                    $("#shiningHeader").removeClass("shine");
                } else {
                    $("#shiningHeader").addClass("shine");
                }
            });
                
        });
</script>
    
</body>

</html>