<?php
$callMark=true;
if(isset($_SESSION['login']) && $_SESSION['login']=="admin"){
    //sudah login
    include("frontend/home.php");
}else{
    //belum login
    include("frontend/login.php");
}

