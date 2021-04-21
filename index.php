<?php


    require_once 'Kernel/AutoLoader.php';
    require_once 'Kernel/requires.php';
    $cerber->Session_startup();
    ob_start();
    ob_clean();
    if(isset($_GET['activation']) && isset($_GET['long']) && isset($_GET['title']))
    {
        $hash = $cerber->sqlsafe($_GET['long']);
        $title = $cerber->sqlsafe($_GET['title']);
        echo $desktop->Activation_hash($title, $hash);
    }
    else if(isset($_POST['activateHash']) && isset($_POST['long']) && isset($_POST['title']) && isset($_POST['code']))
    {
            
        $CONTACT = "kazlucgames@gmail.com"; #$cerber->sqlsafe($_POST['email']); 
        $HASH = $_POST['long'];
        $TITLE = $_POST['title'];
        $CODE = $_POST['code'];
        $micro = new Microsoft($HASH,$TITLE, $CONTACT,$CODE);
        
    }
    else if($settings->isInstalled())
    {
        if($cerber->LoggedIN())
        {
            if(isset($_GET['Logout']))
            {
                $user->Logout();
            }
        }
        if($cerber->LoggedIN() === TRUE && $cerber->isAdmin())
        {
            if(isset($_POST['Desktop']))
            {
                $desktop->show();
            }
            else if(isset($_GET['switchStore']))
            {
                $store_id = $cerber->sqlsafe($_GET['switchStore']);
                $cerber->session_add("STORE_ID",$store_id);
                header("Location:index.php?#customers");
            }
            else if(isset($_GET['stores']))
            {
                $store->Load();
            }
            else if(isset($_GET['Customers']))
            {
                echo $desktop->Customers_page();
            }
            else if(isset($_GET['Products']))
            {
                echo $desktop->Products_page();
            }
            else if (isset($_GET['Stats']))
            {
                echo $desktop->Stats_page();
            }
            else if (isset($_GET['Recomended']))
            {
                $desktop->Recomended_page();
            }
            else if (isset($_GET['Settings']))
            {
                $desktop->Settings_page();
            }
            else if (isset($_GET['Users']))
            {
                $desktop->Users_page();
            }
            else
            {
                $store->Load();
            }
        }
        if($cerber->LoggedIN() === TRUE && $cerber->isAdmin()===FALSE)
        {
            if(isset($_GET['My']))
            {
                $desktop->My_gamesBoard();
            }
            else
            {
                $desktop->My_gamesBoard();
            }
        }
        else
        {
            if(isset($_POST['auth']) && isset($_POST['Username']) && isset($_POST['Password']))
            {
                $username = $cerber->sqlsafe($_POST['Username']); $password = $cerber->sqlsafe($_POST['Password']);
                $user->Login($username,$password);
            }
         
            
            else
            {
                echo $html->Login();
            }
        }
    }
    else  if(!$settings->isInstalled())
    {
        $html->Install();
        
    }
    
     
?>