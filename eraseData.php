<!DOCTYPE html>

<?php
    session_start();
    $page = $_SESSION["page"];  //comme ça on peut l'appeller de nimporte où

    session_destroy();
    foreach ($_POST as $value) {
        unset($value);
    }

    echo "<script>window.location.replace('" . $page . "')</script>;";
?>