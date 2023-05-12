<?php

if((!isset($navItem1Active))) {
    $navItem1Active = null;
}
if((!isset($navItem1Link))) {
    $navItem1Link = null;
}
if((!isset($navItem1))) {
    $navItem1 = null;
}

if((!isset($navItem2Active))) {
    $navItem2Active = null;
}
if((!isset($navItem2Link))) {
    $navItem2Link = null;
}
if((!isset($navItem2))) {
    $navItem2 = null;
}

if((!isset($navItem3Active))) {
    $navItem3Active = null;
}
if((!isset($navItem3Link))) {
    $navItem3Link = null;
}
if((!isset($navItem3))) {
    $navItem3 = null;
}

if((!isset($navItem4Active))) {
    $navItem4Active = null;
}
if((!isset($navItem4Link))) {
    $navItem4Link = null;
}
if((!isset($navItem4))) {
    $navItem4 = null;
}

if((!isset($navItem5Active))) {
    $navItem5Active = null;
}
if((!isset($navItem5Link))) {
    $navItem5Link = null;
}
if((!isset($navItem5))) {
    $navItem5 = null;
}

echo
"<!-- **************************** NAV BAR *********************************************** -->
    <!-- NavBar -->
    <nav class='navbar navbar-expand-lg navbar-dark bg-dark fixed-top'>
        <!-- Rolling Logo -->
        <a class='navbar-brand rolling-logo' href='../php_web_pages/index.php'>
            <img src='../img/rolling-stones.svg' width='30' height='30' class='d-inline-block align-top' alt=''>
            Rolling Stone
        </a>
        <!-- NavBar Toggler -->
        <button class='navbar-toggler' type='button' data-toggle='collapse' data-target='#navbarSupportedContent'
            aria-controls='navbarSupportedContent' aria-expanded='false' aria-label='Toggle navigation'>
            <span class='navbar-toggler-icon'></span>
        </button>

        <!-- NavBar Collapse -->
        <div class='collapse navbar-collapse' id='navbarSupportedContent'>
            <ul class='navbar-nav ml-auto'>

                <!-- Nav Item -->
                <li class='nav-item $navItem1Active'>
                    <a class='nav-link' href='$navItem1Link'>$navItem1</a>
                </li>

                <!-- Nav Item -->
                <li class='nav-item $navItem2Active'>
                    <a class='nav-link' href='$navItem2Link'>$navItem2</a>
                </li>

                <!-- Nav Item -->
                <li class='nav-item $navItem3Active'>
                    <a class='nav-link' href='$navItem3Link'>$navItem3</a>
                </li>

                <!-- Nav Item -->
                <li class='nav-item $navItem4Active'>
                    <a class='nav-link' href='$navItem4Link'>$navItem4</a>
                </li>

                <!-- Nav Item -->
                <li class='nav-item $navItem5Active'>
                    <a class='nav-link' href='$navItem5Link' id='logOutButton'>$navItem5</a>
                </li>
            </ul>
        </div>
    </nav>";



?>