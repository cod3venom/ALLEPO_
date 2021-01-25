<?php

    require_once 'Kernel/AutoLoader.php';
    require_once 'Kernel/requires.php';
    $cerber->Session_startup();

    if($cerber->LoggedIN() === TRUE)
    {
   
        if(isset($_POST['setStore']))
        {
            $store_id = $cerber->sqlsafe($_POST['setStore']);
            if(!empty($store_id))
            {
                $cerber->session_add("STORE_ID",$store_id);
            }
        }
        else if(isset($_POST["addStore"]) && isset($_POST['name']) && isset($_POST['email']) && isset($_POST['pass']) && isset($_POST['repeat']))
        {
            $NAME = $cerber->sqlsafe($_POST['name']); $EMAIL = $cerber->sqlsafe($_POST['email']);
            $PASSWORD = $cerber->sqlsafe($_POST['pass']); $REPEAT = $cerber->sqlsafe($_POST['repeat']);
            if(!empty($NAME) && !empty($EMAIL) && !empty($PASSWORD) && !empty($REPEAT))
            {
                if($PASSWORD === $REPEAT)
                {
                    $store->Store_INIT($NAME, $EMAIL, $PASSWORD);
                }
                else
                {
                    header("Location:index.php?stores&repeated_pass=FALSE");
                }
            }
            
            else
            {
                header("Location:index.php?stores&Empty=True");
            }
        }
        else if(isset($_POST['store']) && isset($_POST['delete']))
        {
            $id = $cerber->sqlsafe((int)$_POST['delete']);
            if(!empty($id))
            {
                $store->Delete($id);
            }
        }
        else if(isset($_POST['TopuserOptions']))
        {
            $store->StoreList();
        }
        
        else if(isset($_POST['customers']) && isset($_POST['limit']) && isset($_POST['start']))
        {
            $LIMIT = (int)$cerber->sqlsafe($_POST['limit']); $START = (int)$cerber->sqlsafe($_POST['start']);
            $customer->Load($LIMIT, $START);
        }
        else if(isset($_POST['products']) && isset($_POST['limit']) && isset($_POST['start']))
        {
            $LIMIT = $cerber->sqlsafe((int)$_POST['limit']); $START = $cerber->sqlsafe((int)$_POST['start']);
            $product->Load($LIMIT, $START);
        }
        else if(isset($_POST['add_customer']) && isset($_POST['store_id']) && isset($_POST['title']) && isset($_POST['username'])
        && isset($_POST['email']) && isset($_POST['price']) && isset($_POST['currency']))
        {
            $STORE_ID = (int)$_POST['store_id']; $TITLE = $cerber->sqlsafe($_POST['title']);
            $USERNAME = $cerber->sqlsafe($_POST['username']); $EMAIL = $cerber->sqlsafe($_POST['email']);
            $PRICE = $cerber->sqlsafe((int)$_POST['price']); $CURRENCY = $cerber->sqlsafe($_POST['currency']);
            if(!empty($STORE_ID) && !empty($TITLE) && !empty($USERNAME) && !empty($EMAIL) && !empty($PRICE) && !empty($CURRENCY))
            {
                $customer->Add($STORE_ID, $TITLE, $USERNAME, $EMAIL, $PRICE, $CURRENCY);
            }
        }
        else if(isset($_POST['add_product']) && isset($_POST['store_id']) && isset($_POST['title']) && isset($_POST['email']) && isset($_POST['password']))
        {
            $STORE_ID = (int)$_POST['store_id']; $TITLE = $cerber->sqlsafe($_POST['title']);
            $EMAIL = $cerber->sqlsafe($_POST['email']); $PASSWORD = $cerber->sqlsafe($_POST['password']); 
            if(!empty($STORE_ID) && !empty($TITLE) && !empty($EMAIL) && !empty($PASSWORD))
            {
                $product->Add($STORE_ID, $TITLE, $EMAIL, $PASSWORD);
            }
        }
       else  if(isset($_POST['product']) && isset($_POST['delete']))
        {
            $id = $cerber->sqlsafe((int)$_POST['delete']);
            if(!empty($id))
            {
                $product->Delete($id);
            }
        }
        else if(isset($_POST['customer']) && isset($_POST['delete']))
        {
            $id = $cerber->sqlsafe((int)$_POST['delete']);
            if(!empty($id))
            {
                $customer->Delete($id);
            }
        }
        else if (isset($_POST['updateInfo'])&& isset($_POST['username']) && isset($_POST['email']))
        {
            $USERNAME = $cerber->sqlsafe($_POST['username']); $EMAIL = $cerber->sqlsafe($_POST['email']);
            $acc->Update_info($USERNAME, $EMAIL);
        }
        else if (isset($_POST['updateSec'])&& isset($_POST['password']) && isset($_POST['repeat']))
        {
            $PASSWORD = $cerber->sqlsafe($_POST['password']); $REPEAT = $cerber->sqlsafe($_POST['repeat']);
            $acc->Update_pass($PASSWORD, $REPEAT);
        }
        else if(isset($_POST['export']) && isset($_POST['param']))
        {
            $param = $cerber->sqlsafe($_POST['param']);
            if($param === 'Email' || $param === "Username" || $param === 'Email, Username' || $param === 'Produkt, Username, Email')
            {
                if($param === 'Email')
                {
                    $export->Email();
                }
                if($param === 'Username')
                {
                    $export->Username();
                }
                if($param === 'Email, Username')
                {
                    $export->EmailUname();
                }
                if($param === 'Produkt, Username, Email')
                {
                    $export->ProduktUnameEmail();
                }
            }
        }
        else if(isset($_POST['truncate']))
        {
            $table = $cerber->sqlsafe($_POST['truncate']);
            $settings->TableTruncate($table);
        }
        else if(isset($_GET['factory']) && isset($_GET['reset']))
        {
            $settings->Restore();
        }
    }
   
    if(isset($_POST['install']) && isset($_POST['username']) && isset($_POST['email']) && isset($_POST['password']) && isset($_POST['repeat']))
    {
        $USERNAME = $cerber->sqlsafe($_POST['username']); $EMAIL = $cerber->sqlsafe($_POST['email']);
        $PASSWORD = $cerber->sqlsafe($_POST['password']); $REPEAT = $cerber->sqlsafe($_POST['repeat']);
        if($PASSWORD === $REPEAT)
        {
                $settings->Install($USERNAME,$EMAIL, $PASSWORD, $REPEAT);
        }
        else
        {
                header('Location:index.php?Repeat&Password=False');
        }
            
    }
    if(isset($_POST['microMachine']) && isset($_POST['product'])&&isset($_POST['update'])&&isset($_POST['email']))
    {
        $machine = $_POST['microMachine'];
        if($machine === '$2y$10$RU0skdxtIaLwzbS3YB90a.y/SCnp.ZEEyDsUuCXZean6R50Wg2PDq')
        {
            $STATUS = $cerber->sqlsafe($_POST['update']); $EMAIL = $cerber->sqlsafe($_POST['email']);
            if(!empty($STATUS) && !empty($EMAIL))
            {
                $product->UpdateStatus($STATUS, $EMAIL);
            }
            
        }
    }
    if(isset($_POST['allegroMachine']) &&isset($_POST['storename']) && isset($_POST['title']) && isset($_POST['username'])  && isset($_POST['email']) 
    && isset($_POST['price']) && isset($_POST['currency']))
    {
        $machine = $_POST['allegroMachine'];
        if($machine === '$2y$10$RU0skdxtIaLwzbS3YB90a.y/SCnp.ZEEyDsUuCXZean6R50Wg2PDq')
        {
            $storename = $cerber->sqlsafe($_POST['storename']);
            $STORE_ID = $store->IdByName($storename);
            $TITLE = $cerber->sqlsafe($_POST['title']); $USERNAME = $cerber->sqlsafe($_POST['username']);
            $EMAIL = $cerber->sqlsafe($_POST['email']); $PRICE = $cerber->sqlsafe((int)$_POST['price']);
            $CURRENCY = $cerber->sqlsafe($_POST['currency']);
            $customer->Add($STORE_ID, $TITLE, $USERNAME, $EMAIL, $PRICE, $CURRENCY);
            echo $storename .'   '.$STORE_ID;
        }
    }
    if(isset($_POST['allegroMachine']) && isset($_POST["addStore"]) && isset($_POST['name']) && isset($_POST['email']) && isset($_POST['pass']) && isset($_POST['repeat']))
    {
        $machine = $_POST['allegroMachine'];
        if($machine === '$2y$10$RU0skdxtIaLwzbS3YB90a.y/SCnp.ZEEyDsUuCXZean6R50Wg2PDq')
        {
            $NAME = $cerber->sqlsafe($_POST['name']); $EMAIL = $cerber->sqlsafe($_POST['email']);
            $PASSWORD = $cerber->sqlsafe($_POST['pass']); $REPEAT = $cerber->sqlsafe($_POST['repeat']);
            if(!empty($NAME) && !empty($EMAIL) && !empty($PASSWORD) && !empty($REPEAT))
            {
                if($PASSWORD === $REPEAT)
                {
                    $store->Store_INIT($NAME, $EMAIL, $PASSWORD);
                }
                else
                {
                    header("Location:index.php?stores&repeated_pass=FALSE");
                }
            }
            
            else
            {
                header("Location:index.php?stores&Empty=True");
            }
        }
    }
    if(isset($_POST['allegroMachine']) && isset($_POST['getHash']))
    {
        $machine = $_POST['allegroMachine'];
        if($machine === '$2y$10$RU0skdxtIaLwzbS3YB90a.y/SCnp.ZEEyDsUuCXZean6R50Wg2PDq')
        {
            $TITLE = $cerber->sqlsafe($_POST['getHash']);
            $customer->GET_ACTIVATION_HASH($TITLE);
        }
    }
    if(isset($_POST['microMachine']) && isset($_POST['email']))
    {
        $machine = $_POST['microMachine'];
        $email = $cerber->sqlsafe($_POST['email']);
        if($machine === '$2y$10$RU0skdxtIaLwzbS3YB90a.y/SCnp.ZEEyDsUuCXZean6R50Wg2PDq')
        {
            echo $customer->Long_ACTIVATION_URL($email);
        }
         
    }
?>