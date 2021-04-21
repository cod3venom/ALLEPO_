<?php

    require_once 'Kernel/AutoLoader.php';
    require_once 'Kernel/requires.php';
    $cerber->Session_startup();

    if($cerber->LoggedIN() === TRUE && $cerber->isAdmin())
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
        else if(isset($_POST['recomended']) && isset($_POST['limit']) && isset($_POST['start']))
        {
            $LIMIT = $cerber->sqlsafe((int)$_POST['limit']); $START = $cerber->sqlsafe((int)$_POST['start']);
            $rec->Load($LIMIT, $START);
        }
        else if(isset($_POST['Users']) && isset($_POST['limit']) && isset($_POST['start']))
        {
            $LIMIT = (int)$cerber->sqlsafe($_POST['limit']); $START = (int)$cerber->sqlsafe($_POST['start']);
            $acc->Load($LIMIT, $START);
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
        else if(isset($_POST['add_product']) && isset($_POST['store_id']) && isset($_POST['title']) && isset($_POST['email']) && isset($_POST['password']) && isset($_POST['img']))
        {
            $STORE_ID = (int)$_POST['store_id']; $TITLE = $_POST['title'];
            $EMAIL = $cerber->sqlsafe($_POST['email']); $PASSWORD = $cerber->sqlsafe($_POST['password']); 
            $IMAGE = $_POST['img'];
            if(!empty($STORE_ID) && !empty($TITLE) && !empty($EMAIL) && !empty($PASSWORD))
            {
                $product->Add($STORE_ID, $TITLE, $EMAIL, $PASSWORD, $IMAGE);
            }
        }
        else if(isset($_POST['update_product']) && isset($_POST['title']) && isset($_POST['email']) && isset($_POST['password'])&& isset($_POST['img']))
        {
            $ID = (int)$_POST['update_product'];
            $TITLE = $_POST['title']; $EMAIL = $cerber->sqlsafe($_POST['email']); $PASSWORD = $cerber->sqlsafe($_POST['password']); 
            $IMAGE = $_POST['img'];
            $product-> Update($TITLE,$EMAIL,$PASSWORD,$IMAGE,$ID);
            echo $IMAGE;
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
        else if(isset($_POST['searchCustomer']))
        {
            $username = $_POST['searchCustomer'];
            $customer->Search($username);
        }
        else if(isset($_POST["assignment"]))
        {
            $input = $cerber->sqlsafe($_POST["assignment"]);
            if($input === "users")
            {
                $acc->Assignmnet_users();
            }
            if($input === "games")
            {
                $acc->Assignmnet_products();
            }
             
        }
        else if(isset($_POST["assign"]) && isset($_POST["customer"]) && isset($_POST["product"]))
        {
            $username = $_POST["customer"]; $product_name = $_POST["product"];
            $customer->ProductAssignment($username,$product_name);
        }
        else if(isset($_POST["targetGames"]))
        {
            $username = $_POST['targetGames'];
            $acc->UserGames($username);
        }
        else if(isset($_POST["username"]) && isset($_POST["deletGame"]))
        {
            $username = $_POST['username']; $game_hash = $_POST["deletGame"];
            $acc->UserDeleteGame($username, $game_hash);
        }
        else if(isset($_POST["usersearch"]) && isset($_POST["username"]))
        {
            $username = $_POST["username"];
            $acc->SearchUser($username);
        }
        else if(isset($_POST["userCardSearch"]) && isset($_POST["username"]))
        {
            $username = $_POST["username"];
            $acc->SearchUserCard($username);
        }
        else if(isset($_POST['addRecomended']) && isset($_POST['allegro']) &&isset($_POST['imglink']) && isset($_POST['title']) && isset($_POST['price'])
        && isset($_POST['currency']))
        {
            $allegro = $_POST['allegro'];  $img = $_POST['imglink']; $title = $cerber->sqlsafe($_POST['title']);
            $price = (int)$_POST['price']; $currency = $cerber->sqlsafe($_POST['currency']);
            $rec->add($allegro,$img,$title,$price,$currency);
        }
        else if(isset($_POST['recomended']) && isset($_POST['check']))
        {
            $id = $cerber->sqlsafe((int)$_POST['check']);
            $rec->Check($id);
        }
        else if(isset($_POST['recomended']) && isset($_POST['uncheck']))
        {
            $id = $cerber->sqlsafe((int)$_POST['uncheck']);
            $rec->Uncheck($id);
        }
        else if(isset($_POST['profile']) && isset($_POST['delete']))
        {
            $userid = $_POST['delete'];
            $acc->Delete($userid);
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
        else if(isset($_POST['BrandNameSet']) && isset($_POST['name']))
        {
            $name = $cerber->sqlsafe($_POST['name']);
            $settings->BrandName_set($name);
        }
        else if(isset($_POST['generate']) && isset($_POST['email']))
        {
            $email = $cerber->sqlsafe($_POST['email']);
            $micro = new Microsoft("","", "", "");
            echo $micro->ActivationHash("EMAIL",$email); 
        }
        else if(isset($_POST['block']))
        {
            $hash = $cerber->sqlsafe($_POST['block']);
            $micro = new Microsoft("","", "", "");
            echo $micro->Block($hash);
        }
    }
    if($cerber->LoggedIN() === TRUE && $cerber->isAdmin() === FALSE)
    {
        if(isset($_POST['games']) && isset($_POST['limit']) && isset($_POST['start']))
        {
             
            $LIMIT = (int)$cerber->sqlsafe($_POST['limit']); $START = (int)$cerber->sqlsafe($_POST['start']);
            $games->Load($LIMIT, $START);
        }
    }
    if(isset($_POST['register']) && isset($_POST['username']) && isset($_POST['email']) && isset($_POST['password']))
    {
        $USERNAME = $cerber->sqlsafe($_POST['username']); $EMAIL = $cerber->sqlsafe($_POST['email']);
        $PASSWORD = $cerber->sqlsafe($_POST['password']); 
        $LEVEL = 'PROFILE';
        $user->Register_INIT($USERNAME,$EMAIL,$PASSWORD, $PASSWORD, $LEVEL);
    }
    if(isset($_POST['editUser']) && isset($_POST['username']) && isset($_POST['email']) && isset($_POST['password']))
    {
        $USERID = $_POST['editUser'];
        $USERNAME = $cerber->sqlsafe($_POST['username']); $EMAIL = $cerber->sqlsafe($_POST['email']);
        $PASSWORD = $cerber->sqlsafe($_POST['password']); 
        $LEVEL = 'PROFILE';
        $user->updateUser($USERID, $USERNAME,$EMAIL,$PASSWORD);
    }
    if(isset($_POST['recomended']) && isset($_POST['activation']))
    {
        $rec->Load_activation();
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
    if(isset($_POST['allegroMachine']) &&isset($_POST['storename']) && isset($_POST['hash']) && isset($_POST['title']) && isset($_POST['username'])  && isset($_POST['email']) 
    && isset($_POST['price']) && isset($_POST['currency']))
    {

        $machine = $_POST['allegroMachine'];
        if($machine === '$2y$10$RU0skdxtIaLwzbS3YB90a.y/SCnp.ZEEyDsUuCXZean6R50Wg2PDq')
        {
            $storename = $cerber->sqlsafe($_POST['storename']);$STORE_ID = $store->IdByName($storename);
            $HASH = $cerber->sqlsafe($_POST['hash']);$TITLE = $cerber->sqlsafe($_POST['title']); 
            $USERNAME = $cerber->sqlsafe($_POST['username']);$EMAIL = $cerber->sqlsafe($_POST['email']);
            $PRICE = $cerber->sqlsafe((int)$_POST['price']); $CURRENCY = $cerber->sqlsafe($_POST['currency']); 
            $LEVEL = 'Profile';           
            $customer->Add($STORE_ID, $HASH , $TITLE, $USERNAME, $EMAIL, $PRICE, $CURRENCY);
            
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
            $micro = new Microsoft("","", "", "");
            echo $micro->ActivationHash("TITLE",$TITLE);
        }
    }
    if(isset($_POST['allegroMachine']) && isset($_POST['username']))
    {
        $machine = $_POST['allegroMachine'];
        if($machine === '$2y$10$RU0skdxtIaLwzbS3YB90a.y/SCnp.ZEEyDsUuCXZean6R50Wg2PDq')
        {
            $USERNAME = $cerber->sqlsafe($_POST['username']);
            echo $customer->isCustomer($USERNAME);
            
        }
    }
    if(isset($_POST['allegroMachine']) && isset($_POST['readsalt']))
    {
        $machine = $_POST['allegroMachine'];
        if($machine === '$2y$10$RU0skdxtIaLwzbS3YB90a.y/SCnp.ZEEyDsUuCXZean6R50Wg2PDq')
        {
            $HASH = $_POST['readsalt'];
            echo $user->ReadDec($HASH);
        }
    }
    if(isset($_POST['microMachine']) && isset($_POST['email']))
    {
        $machine = $_POST['microMachine'];
        $email = $cerber->sqlsafe($_POST['email']);
        if($machine === '$2y$10$RU0skdxtIaLwzbS3YB90a.y/SCnp.ZEEyDsUuCXZean6R50Wg2PDq')
        {
            $micro = new Microsoft("","", "", "");
            echo $micro->ActivationHash("EMAIL",$email);
        }
         
    }
?>