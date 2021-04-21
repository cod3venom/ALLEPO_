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
    $rec = new Recomended();
    $export = new Export();
    $games = new Games();
    $DEFAULT_AVATAR = 'ASSETS//IMG//user_2.png';
    if(!defined("DB_SERVER")){define("DB_SERVER", "localhost");}
    if(!defined("DB_USER")){define("DB_USER", "root");}
    if(!defined("DB_PASS")){define("DB_PASS", "");}
    if(!defined("DB_NAME")){ define("DB_NAME", "allepo");}
    
    $conn = mysqli_connect(DB_SERVER, DB_USER, DB_PASS, DB_NAME);
    if(!$conn)
    {
        die(mysqli_connect_error());
        exit();
    }
    
    
?>