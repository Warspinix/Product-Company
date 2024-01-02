<?php
    session_start();
    session_unset();
    session_destroy();
    header("Location: 0_home.html");
?>