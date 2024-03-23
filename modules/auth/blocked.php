<?php
    layouts('header', ['pageTitle' => 'Access denied']);
?>
<div class="login">
    <div class="col-4 bg-white border rounded p-4 shadow-sm">
        <form>
            <h1 class="h5 mb-3 fw-normal">Hello, Your Account Is Blocked By Admin</h1>

            <div class="mt-3 d-flex justify-content-between align-items-center">
                <a href="?module=auth&action=logout" class="btn btn-danger" type="submit">Logout</a>
            </div>
        </form>
    </div>
</div>