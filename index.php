<?php


    require_once 'Kernel/AutoLoader.php';
    require_once 'Kernel/requires.php';
    $cerber->Session_startup();
    if($settings->isInstalled())
    {
       
        if($cerber->LoggedIN() === TRUE)
        {
            if(isset($_GET['Logout']))
            {
                $user->Logout();
            }
            else if(isset($_POST['Desktop']))
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
            else if (isset($_GET['Settings']))
            {
                $desktop->Settings_page();
            }
            else
            {
                $store->Load();
            }
        }
        else
        {
            if(isset($_POST['auth']) && isset($_POST['Email']) && isset($_POST['Password']))
            {
                $email = $cerber->sqlsafe($_POST['Email']); $password = $cerber->sqlsafe($_POST['Password']);
                $user->Login($email,$password);
            }
            //else if(isset($_GET['activation']) && isset($_GET['title']))
            //{
            //    $title = $cerber->sqlsafe($_GET['title']);
            //    echo $desktop->Activation_page($title);
            //    
            //}
            else if(isset($_POST['activate']) && isset($_POST['title']) && isset($_POST['email']) && isset($_POST['code']))
            {
                $CONTACT = $cerber->sqlsafe($_POST['email']); 
                $TITLE = $cerber->sqlsafe($_POST['title']);
                $CODE = $cerber->sqlsafe($_POST['code']);
                #$micro->SelectAccount($CONTACT,$TITLE, $CODE);
                $micro = new Microsoft($TITLE,$CONTACT,$CODE);
                 

            }
            else if(isset($_GET['activation']) && isset($_GET['long']) && isset($_GET['title']))
            {
                $hash = $cerber->sqlsafe($_GET['long']);
                $title = $cerber->sqlsafe($_GET['title']);
                echo $desktop->Activation_hash($title, $hash);
            }
            
            if(isset($_POST['activateHash']) && isset($_POST['long']) && isset($_POST['title']) && isset($_POST['email']) && isset($_POST['code']))
            {
                 
                $CONTACT = $cerber->sqlsafe($_POST['email']); 
                $HASH = $cerber->sqlsafe($_POST['long']);
                $TITLE = $cerber->sqlsafe($_POST['title']);
                $CODE = $cerber->sqlsafe($_POST['code']);
                #$micro = new Microsoft($TITLE,$CONTACT,$CODE);
                $longactivator = new Long_activator($HASH,$CONTACT,$CODE,$TITLE);
                #echo $CONTACT.PHP_EOL.$HASH.PHP_EOL.$CODE;
                 
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