<?php
if(session_id() == '') {
    session_start();
}
$logged = isset($_SESSION['logged']) and $_SESSION['logged'];
?>