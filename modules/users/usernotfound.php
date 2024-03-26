<?php
    layouts('header', ['pageTitle' => 'NOT FOUND']);

    require_once "modules/home/navbar.php";
?>
<div style="margin-top:120px;" class="d-flex justify-content-center flex-column align-items-center">
    <h1 class="text-danger">User Not Found</h1>
    <a href="?module=home&action=dashboard" class="btn btn-primary">Go to newsfeed</a>
</div>