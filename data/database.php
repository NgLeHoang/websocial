<?php
    try {
        if (class_exists('PDO')) {
            $dsn = 'mysql:dbname=' . _DB . ';host='. _HOST;
            $connect = new PDO($dsn, _USER, _PASS);
        }
    }
    catch (Exception $ex) {
        echo '<div style="color: red; padding = 5px 15px; border: 1px solid red;">';
        echo $ex -> getMessage().'<br>';
        echo '</div>';
        die();
    }
?>