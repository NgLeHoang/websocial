<?php
    layouts('header');

    $userData = $_SESSION['userdata'];
    require_once "modules/action/postmodal.php";
    require "modules/action/notificationmodal.php";
    require "modules/action/messagemodal.php";
    require "modules/action/messagechat.php";
?>

<nav class="navbar navbar-expand-lg navbar-light bg-white border">
    <div class="container col-9 d-flex justify-content-between">
        <div class="d-flex justify-content-between col-8">
            <form class="d-flex">
                <input id="searchInput" class="form-control me-2" type="search" placeholder="looking for someone.." aria-label="Search">
                <div id="searchResult" class="search-result"></div>
            </form>
        </div>
        <ul class="navbar-nav">
            <a href="?module=home&action=dashboard" class="nav-link"><i class="fa-solid fa-house"></i></a>
            <a href="#" class="nav-link" data-bs-toggle="offcanvas" data-bs-target="#messages">
                <i class="fa-solid fa-message"></i>
                <span class="un-count position-absolute start-10 translate-middle badge p-1 rounded-pill bg-danger" id="msgcounter"></span>
            </a>
            <a href="#" class="nav-link" data-bs-toggle="offcanvas" data-bs-target="#notification">
                <i class="fa-solid fa-bell"></i>
                <span class="un-count position-absolute start-10 translate-middle badge p-1 rounded-pill bg-danger" id="noticounter"></span>
            </a>
            <a href="#" class="nav-link" data-bs-toggle="modal" data-bs-target="#postmodal"><i class="fa-regular fa-square-plus"></i></a>
        </ul>
        <ul class="navbar-nav  mb-2 mb-lg-0">
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown"
                    aria-expanded="false">
                    <img src="assets/img/profile/<?=$userData['profile_pic']; ?>" alt="" width="30" height="30" class="rounded-circle border">
                </a>
                <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                    <li><a class="dropdown-item" href="?module=users&action=profile&name=<?=$userData['username']?>">My Profile</a></li>
                    <li><a class="dropdown-item" href="?module=users&action=editprofile">Account Settings</a></li>
                    <li>
                        <hr class="dropdown-divider">
                    </li>
                    <li><a class="dropdown-item" href="?module=auth&action=logout">Logout</a></li>
                </ul>
            </li>
        </ul>
    </div>
</nav>
<?php
    layouts('footer');
?>