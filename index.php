<?php
session_start();
$callMark=true;
if(isset( $_SESSION['stokman']) && $_SESSION['stokman']['login']=="admin"){
    //sudah login
    include("frontend/home.php");
}else{
    //belum login
    include("frontend/login.php");
}

