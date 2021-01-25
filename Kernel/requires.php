<?php
 
    $db = new DB();
    $settings = new Settings();
    $cerber = new Cerber();
    $user = new User();
    $acc = new Account();
    $html = new HTML();
    $desktop = new Desktop();
    $store = new Store();
    $product = new Products();
    $customer = new Customers();
    $export = new Export();
    $DEFAULT_AVATAR = 'https://cdn5.vectorstock.com/i/1000x1000/51/99/icon-of-user-avatar-for-web-site-or-mobile-app-vector-3125199.jpg';
    if(!defined("DB_SERVER")){define("DB_SERVER", "localhost");}
    if(!defined("DB_USER")){define("DB_USER", "root");}
    if(!defined("DB_PASS")){define("DB_PASS", "");}
    if(!defined("DB_NAME")){ define("DB_NAME", "ALLEPOV1");}
    
    $conn = mysqli_connect(DB_SERVER, DB_USER, DB_PASS, DB_NAME);
    if(!$conn)
    {
        die(mysqli_connect_error());
        exit();
    }
    
    
?>