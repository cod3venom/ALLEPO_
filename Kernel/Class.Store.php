<?php

class Store extends DB
{
    public function isStore($name)
    {
        require "requires.php";
        $stmt = $this->STMT($conn,"SELECT NAME FROM STORE WHERE NAME = ?");
        $stmt->bind_param("s",$name); 
        return $this->Exists($stmt);
    }

    public function Store_INIT($NAME, $EMAIL, $PASSWORD)
    {
        require "requires.php";
        if($this->isStore($NAME) === FALSE)
        {
            $this->Add($NAME,$EMAIL,$PASSWORD);
        }
        else
        {
            header("Location:index.php?Store&Already_exists=TRUE");
        }
    }
    public function Add($NAME, $EMAIL,$PASSWORD)
    {
        require "requires.php";
        $stmt = $this->STMT($conn, "INSERT INTO STORE (NAME, EMAIL, PASSWORD) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $NAME, $EMAIL, $PASSWORD);
        if($db->Store($stmt) >= 1)
        {
            header("Location:index.php?stores&Added=TRUE");
        }
        else{
            header("Location:index.php?stores&Added=False");
        }
    }
    public function Delete($ID)
    {
        require 'requires.php';
        $stmt = $db->STMT($conn, 'DELETE FROM STORE WHERE ID = ?');
        $stmt->bind_param("s",$ID);
        $db->Store($stmt);
        header("Location:index.php?stores");
    }
    public function NameById($ID)
    {
        require "requires.php";
        $ID = (int)$ID;
        $stmt = $this->STMT($conn,"SELECT NAME FROM STORE WHERE ID = ? LIMIT 1");
        $stmt->bind_param("s",$ID); $stmt->execute();
        $result = $stmt->get_result();
        foreach($result as $name)
        {
            return $name['NAME'];
        }
    }
    public function IdByName($name)
    {
        require "requires.php";
        $name = $cerber->sqlsafe($name);
        $stmt = $this->STMT($conn,"SELECT ID FROM STORE WHERE NAME = ? LIMIT 1");
        $stmt->bind_param("s",$name); $stmt->execute();
        $result = $stmt->get_result();
        foreach($result as $id)
        {
            return $id['ID'];
        }
    }
    public function idByMail($NAME)
    {
        require "requires.php";
        $NAME = $cerber->sqlsafe($NAME);
        $stmt = $this->STMT($conn,"SELECT ID FROM STORE WHERE NAME = ? LIMIT 1");
        $stmt->bind_param("s",$NAME); $stmt->execute();
        $result = $stmt->get_result();
        foreach($result as $id)
        {
            return $id['ID'];
        }
    }
    public function Load()
    {
        require "requires.php";
        $stmt = $this->STMT($conn, "SELECT ID, NAME FROM STORE ORDER BY(ID) DESC");
        $stmt->execute(); $markets = $stmt->get_result();
        $desktop->Stores_page($markets);
    }
    public function StoreList()
    {
        require "requires.php";
        $stmt = $this->STMT($conn, "SELECT ID, NAME FROM STORE");
        $stmt->execute(); $result = $stmt->get_result();
        foreach($result as $markets)
        {
             echo $desktop->UserOptions($markets);
        }
        echo $html->Logout();
    }
}


class Products extends DB
{
    public function Add($STORE_ID, $TITLE, $EMAIL, $PASSWORD, $IMAGE)
    {
        require 'requires.php';
        $status = "Sprzedane";
        $hash = md5($EMAIL);
        $stmt = $this->STMT($conn,"INSERT INTO PRODUCTS(STORE_ID,PRODUCT_HASH, TITLE, EMAIL, PASSWORD, STATUS, IMAGE) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("sssssss", $STORE_ID, $hash, $TITLE, $EMAIL, $PASSWORD, $status, $IMAGE);
        $this->Store($stmt);
        echo "ok";
    }
    public function Delete($ID)
    {
        require 'requires.php';
        $stmt = $this->STMT($conn, 'DELETE FROM PRODUCTS WHERE ID = ?');
        $stmt->bind_param("s",$ID);
        $db->Store($stmt);
        header("Location:index.php");
    }
    public function Update($TITLE,$EMAIL,$PASSWORD,$IMAGE, $ID)
    {
        require 'requires.php';
        $status = "Sprzedane";
        $stmt = $this->STMT($conn, 'UPDATE  PRODUCTS SET TITLE = ?, EMAIL = ?, PASSWORD = ? , IMAGE = ? WHERE ID  = ?');
        $stmt->bind_param("sssss",$TITLE, $EMAIL,$PASSWORD,$IMAGE,$ID);
        echo $db->Store($stmt);
    }
    public function UpdateStatus($STATUS, $EMAIL)
    {
        require "requires.php";
        $stmt = $this->STMT($conn,"UPDATE PRODUCTS SET STATUS = ? WHERE EMAIL = ?");
        $stmt->bind_param("ss",$STATUS, $EMAIL);
        $this->Store($stmt);
        echo "UPDATED";
    }
    public function Load($LIMIT, $START)
    {
        require 'requires.php';
        $LIMIT = $cerber->sqlsafe((int)$LIMIT);
        $START = $cerber->sqlsafe((int)$START);
        $query = "SELECT * FROM PRODUCTS WHERE STORE_ID = ?  ORDER BY(TITLE) ASC LIMIT ".$START.", ".$LIMIT."";
        $stmt = $this->STMT($conn,$query); $stmt->bind_param("s",$_SESSION['STORE_ID']);
        $stmt->execute();  $items = $stmt->get_result();
        foreach($items as $item){
             echo $desktop->Products_list_gen($item);
        }
    }
    public function GetImage($TITLE)
    {
        require 'requires.php';
        $stmt = $this->STMT($conn,"SELECT IMAGE FROM PRODUCTS WHERE TITLE = ? LIMIT 1");
        $stmt->bind_param("s",$TITLE); $stmt->execute();
        $result = $stmt->get_result();
        foreach($result as $img)
        {
            return $img['IMAGE'];
        }
    }
     
}

class Customers extends DB
{
    
    public function Add($STORE_ID, $HASH ,$TITLE, $USERNAME, $EMAIL, $PRICE, $CURRENCY)
    {
        require "requires.php";
        $img = $product->GetImage($TITLE);
        $stmt = $this->STMT($conn,"INSERT INTO CUSTOMERS(STORE_ID, PRODUCT_HASH, TITLE, USERNAME, EMAIL, PRICE, CURRENCY,IMAGE, ACTIVE,
                            MONTH, YEAR) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $status = "Sprzedane"; $month = date("M"); $year = date("Y");
        $stmt->bind_param("sssssssssss", $STORE_ID, $HASH, $TITLE, $USERNAME, $EMAIL, $PRICE, $CURRENCY, $img, $status, $month, $year);
        $this->Store($stmt);
        #$product->Update($TITLE);

    }
    public function Load($LIMIT, $START)
    {
        require 'requires.php';
        $LIMIT = $cerber->sqlsafe((int)$LIMIT);
        $START = $cerber->sqlsafe((int)$START);        
        $query = "SELECT * FROM CUSTOMERS  WHERE STORE_ID = ? ORDER BY(ID) DESC LIMIT ".$START.", ".$LIMIT."";
        $stmt = $this->STMT($conn,  $query);  $stmt->bind_param("s",$_SESSION['STORE_ID']);
       $stmt->execute(); 
        $items = $stmt->get_result();
        foreach($items as $item)
        {
             echo $desktop->Customers_list_gen($item);     
        }
    }

    public function Delete($ID)
    {
        require 'requires.php';
        $stmt = $db->STMT($conn, 'DELETE FROM CUSTOMERS WHERE ID = ?');
        $stmt->bind_param("s",$ID);
        $db->Store($stmt);
        header("Location:index.php");
    }
    public function getHash($MAIL)
    {
         require "requires.php";
        $stmt = $this->STMT($conn,"SELECT HASH FROM ACTIVATOR WHERE GAME_MAIL = ? LIMIT 1");
        $stmt->bind_param("s",$MAIL);
        $stmt->execute(); $result = $stmt->get_result();
        foreach($result as $game)
        {
            return $game['HASH'];
        }
    }
    public function isCustomer($USERNAME)
    {
        require "requires.php";
        $stmt = $this->STMT($conn,"SELECT PASSWORD FROM USERS WHERE USERNAME = ? LIMIT 1");
        $stmt->bind_param("s",$USERNAME);
        $stmt->execute(); $result = $stmt->get_result();
        foreach($result as $db)
        {
            return $db['PASSWORD'];
        }
    }
    public function Search($username)
    {
        require "requires.php";
        $stmt = $this->STMT($conn,"SELECT * FROM CUSTOMERS WHERE USERNAME = ? LIMIT 1");
        $stmt->bind_param("s",$username); $stmt->execute();
        $result = $stmt->get_result();
        foreach($result as $client)
        {
            echo $desktop->Customer_search_result_gen($client);
        }
    }
    public function CustomerSelector($username)
    {
        require "requires.php";
        $stmt = $this->STMT($conn,"SELECT * FROM USERS WHERE USERNAME = ? LIMIT 1");
        $stmt->bind_param("s",$username); $stmt->execute();
        $result = $stmt->get_result();
        foreach($result as $client)
        {
            return      $client['USERNAME'].'$'.
                        $client['EMAIL'].'$';
        }
        
    }
    public function ProductSelector($product_name)
    {
        require "requires.php";
        $stmt = $this->STMT($conn,"SELECT * FROM PRODUCTS WHERE TITLE = ? LIMIT 1");
        $stmt->bind_param("s",$product_name); $stmt->execute();
        $result = $stmt->get_result();
        foreach($result as $client)
        {
            return $client['TITLE'].'$'. $client['PRODUCT_HASH'].'$';
        }
    }
    public function ProductAssignment($username,$product_name)
    {
        $user = $this->CustomerSelector($username);
        $product_data = $this->ProductSelector($product_name);
        $full = $user.$product_data;
        $spl = explode("$",$full);
        $USERNAME = $spl[0]; $EMAIL = $spl[1]; $TITLE = $spl[2]; $HASH = $spl[3]; 
        $this->Add($_SESSION["STORE_ID"], $HASH ,$TITLE, $USERNAME, $EMAIL, "0", "PLN");
         
    }
}
class Export extends DB
{
     
    public function Email()
    {
        require "requires.php";
        $stmt = $this->STMT($conn,"SELECT EMAIL FROM CUSTOMERS WHERE STORE_ID = ?");
        $stmt->bind_param("s",$_SESSION['STORE_ID']);    $stmt->execute(); 
        $result = $stmt->get_result();
        foreach($result as $data)
        {
          echo $data['EMAIL'].PHP_EOL; 
        }   
        
    }
    public function Username()
    {
        require "requires.php";
        $stmt = $this->STMT($conn,"SELECT USERNAME FROM CUSTOMERS WHERE STORE_ID = ?");
        $stmt->bind_param("s",$_SESSION['STORE_ID']); $stmt->execute(); 
        $result = $stmt->get_result();
        foreach($result as $data)
        {
          echo $data['USERNAME'].PHP_EOL; 
        }   
    }
    public function EmailUname()
    {
        require "requires.php";
        $stmt = $this->STMT($conn,"SELECT EMAIL, USERNAME FROM CUSTOMERS WHERE STORE_ID = ?");
        $stmt->bind_param("s",$_SESSION['STORE_ID']); $stmt->execute(); 
        $result = $stmt->get_result();
        foreach($result as $data)
        {
          echo $data['EMAIL'].','. $data['USERNAME'].PHP_EOL; 
        }   
    }
    public function ProduktUnameEmail()
    {
        require "requires.php";
        $stmt = $this->STMT($conn,"SELECT TITLE, EMAIL, USERNAME FROM CUSTOMERS WHERE STORE_ID = ?");
        $stmt->bind_param("s",$_SESSION['STORE_ID']); $stmt->execute();
        $result = $stmt->get_result();
        foreach($result as $data)
        {
          echo  $data['TITLE'].','.$data['EMAIL'].','. $data['USERNAME'].PHP_EOL; 
        }  
    }
}

class Recomended extends DB
{
    public function add($allegro,$img,$title,$price,$currency)
    {
        require 'requires.php';
        $show = (int)0;
        $stmt = $this->STMT($conn,'INSERT INTO RECOMENDED (ALLEGRO, STORE_ID,IMG, TITLE, PRICE, CURRENCY,SHOW_ITEM) VALUES (?, ?, ?, ?, ?, ? ,?)');
        $stmt->bind_param("sssssss",$allegro,$_SESSION['STORE_ID'],$img,$title,$price,$currency,$show);
        $this->Store($stmt);
    }
    public function Check($id)
    {
        require 'requires.php';
        $show = '1';
        $stmt = $this->STMT($conn,'UPDATE RECOMENDED SET SHOW_ITEM = ? WHERE ID = ?');
        $stmt->bind_param("si",$show,$id);
        $this->Store($stmt);
         
    }
    public function Uncheck($id)
    {
        require 'requires.php';
        $show = '0';
        $stmt = $this->STMT($conn,'UPDATE RECOMENDED SET SHOW_ITEM = ? WHERE ID = ?');
        $stmt->bind_param("si",$show,$id);
        $this->Store($stmt);
    }
    public function Load($LIMIT, $START)
    {
        require 'requires.php';
        $LIMIT = $cerber->sqlsafe((int)$LIMIT);
        $START = $cerber->sqlsafe((int)$START);
        $query = "SELECT * FROM RECOMENDED WHERE STORE_ID = ?  ORDER BY(ID) DESC LIMIT ".$START.", ".$LIMIT."";
        $stmt = $this->STMT($conn,$query); $stmt->bind_param("s",$_SESSION['STORE_ID']);
        $stmt->execute();  $items = $stmt->get_result();
        foreach($items as $item){
             echo $desktop->Recomended_item_gen($item);
        }
    }
    public function Load_activation()
    {
        require "requires.php";
        $show = '1';
        $query = "SELECT * FROM RECOMENDED WHERE SHOW_ITEM = ?  ORDER BY(ID) DESC LIMIT 10";
        $stmt = $this->STMT($conn,$query); $stmt->bind_param("s",$show);
        $stmt->execute();  $items = $stmt->get_result();
        foreach($items as $item){
             echo $desktop->Recomended_activation_item_gen($item);
        }
    }
}
 
class Microsoft extends DB
{
    public $GAME_TITLE;
    public $GAME_CONTACT;
    public $GAME_CODE;
    public $GAME_HASH;
    public $GAME_EMAIL;

    function __construct($HASH,$TITLE, $CONTACT, $CODE)
    {
        $this->GAME_TITLE = $TITLE;
        $this->GAME_CONTACT = $CONTACT;
        $this->GAME_CODE = $CODE; 
        $this->GAME_HASH = $HASH;
        $this->GAME_EMAIL = $this->GAME_EMAIL();
        $this->INIT();

    }
    public function STACK_SELECTOR($KEY)
    {
        if($KEY === 'HASH' || $KEY === 'FIRST_LIMIT' || $KEY === 'LOCK_DAY')
        {
            require 'requires.php';
            $KEY = $cerber->sqlsafe($KEY);
            $stmt = $this->STMT($conn,'SELECT * FROM ACTIVATOR WHERE HASH = ?');
            $stmt->bind_param('s',$this->GAME_HASH); $stmt->execute();
            $result = $stmt->get_result();
            foreach($result as $selector)
            {
                return $selector[$KEY];
            }
        }
    }
    public function isStack()
    {
        $hashid = $this->STACK_SELECTOR('HASH');
        if(empty($hashid))
        {
            return FALSE;
        }
        else
        {
            return TRUE;
        }

    }
    public function INIT()
    {
        #if($this->isStack())
        #{
        #    $this->Update();
        #}
        #else
        #{            
        #    $this->Add();
        #}
        $this->Add();
    }
    public function Add()
    {
        require 'requires.php';
        $firstLimit = '2'; $lockTime = date('d/m/Y', strtotime('+7 days'));
        
        $stmt = $this->STMT($conn,'INSERT INTO ACTIVATOR(CONTACT, GAME_MAIL,HASH,FIRST_LIMIT, LOCK_DAY) VALUES (?, ?, ?, ?, ?)');
        $stmt->bind_param("sssss",$this->GAME_CONTACT, $this->GAME_EMAIL, $this->GAME_HASH, $firstLimit, $lockTime);
       
        if($this->Store($stmt) >= 1)
        {
            #if($this->hash_verify())
            #{
                #echo "executed";
                #$this->Run($this->SelectAccount());
            #}
                $this->Run($this->SelectAccount());
        }
    }
    public function Update()
    {
        $first_limit = (int)$this->STACK_SELECTOR('FIRST_LIMIT') - 1;
        if($first_limit >= 0)
        {
            require 'requires.php';
            $stmt = $this->STMT($conn,'UPDATE ACTIVATOR SET FIRST_LIMIT = ? WHERE HASH = ?');
            $stmt->bind_param('is',$first_limit,$this->GAME_HASH);
            if($this->Store($stmt) > 0)
            {
                #echo "executed";
                $this->Run($this->SelectAccount());
            }
        }
        else
        {
            $day_lock = $this->STACK_SELECTOR('LOCK_DAY');
            $today = date("d/m/Y");
            if($day_lock === $today)
            {
                $this->RemoveLock();
            }
            else
            {
                #echo "Blocked";
            }
        }
    }
    public function Block($hash)
    {
        require "requires.php";
        $first_limit = (int)0;
        //$stmt = $this->STMT($conn,"UPDATE ACTIVATOR SET FIRST_LIMIT = ? WHERE HASH = ?");
        //$stmt->bind_param("is",$first_limit, $hash);
        //$this->Store($stmt);
    }
    public function RemoveLock()
    {
        require 'requires.php';
        $stmt = $this->STMT($conn,'DELETE FROM ACTIVATOR  WHERE HASH = ?');
        $stmt->bind_param('s',$this->GAME_HASH);
        if($this->Store($stmt) > 0)
        {
            $this->INIT();
        }
    }
    public function GAME_EMAIL()
    {
        require "requires.php";
        if(!empty($this->GAME_TITLE))
        {
            $stmt = $this->STMT($conn,"SELECT EMAIL FROM PRODUCTS WHERE PRODUCT_HASH = ? LIMIT 1");
            $stmt->bind_param("s", $this->GAME_HASH);
            $stmt->execute(); $result = $stmt->get_result();
            foreach($result as $game)
            {
               return $game['EMAIL'];
            }      
        }
    }
    public function hash_verify()
    {
        if(md5($this->GAME_EMAIL) === $this->GAME_HASH)
        {
            return TRUE;
        }
        else
        {
            return FALSE;
        }
    }
    public function SelectAccount()
    {
        require "requires.php";
        if(!empty($this->GAME_TITLE))
        {
            $exists = False;
            $stmt = $this->STMT($conn,"SELECT PASSWORD FROM PRODUCTS WHERE PRODUCT_HASH =  ?  LIMIT 1");
            $stmt->bind_param("s", $this->GAME_HASH);
            $stmt->execute(); $result = $stmt->get_result();
            foreach($result as $game)
            {
                $exists = TRUE;
                if($exists === TRUE)
                {
                    return $game['PASSWORD'];
                }
            }
            if($exists === FALSE)
            {
                echo "Account NOTFOUND";
            }
           
        }
    }
    public function ActivationHash($PARAM,$INPUT)
    {
        require "requires.php";
        if($PARAM === "TITLE" || $PARAM === "EMAIL")
        {
            $query = "SELECT PRODUCT_HASH FROM PRODUCTS WHERE EMAIL = ? LIMIT 1";
            if($PARAM === "TITLE")
            {
                $query = "SELECT PRODUCT_HASH FROM PRODUCTS WHERE TITLE = ? LIMIT 1";
            }
            $stmt = $this->STMT($conn,$query);
            $stmt->bind_param("s",$INPUT);
            $stmt->execute(); $result = $stmt->get_result();
            foreach($result as $game)
            {
                return $game['PRODUCT_HASH'];
            }
        }
    }
    private function Run($PWD)
    {

        //echo $this->GAME_TITLE .PHP_EOL.$this->GAME_CONTACT.PHP_EOL.
        //"GAME EMAIL IS ".$this->GAME_EMAIL.PHP_EOL.$PWD.PHP_EOL.$this->GAME_HASH.PHP_EOL.$this->GAME_CODE;
        
        $cmd = escapeshellcmd("sudo -u root python3 Microsoft/main.py ".$this->GAME_CONTACT .' '.$this->GAME_EMAIL .' '.$PWD .' ' .$this->GAME_HASH .' '. $this->GAME_CODE .' '.$this->GAME_TITLE);
        echo shell_exec($cmd);
    }

}
class Games extends DB
{
    public function MyGames()
    {
        require 'requires.php';
        $stmt = $this->STMT($conn,"SELECT TITLE FROM CUSTOMERS WHERE USERNAME = ?");
    }
    public function Load($LIMIT, $START)
    {
        require 'requires.php';
        $LIMIT = $cerber->sqlsafe((int)$LIMIT);
        $START = $cerber->sqlsafe((int)$START);
        $query = "SELECT PRODUCT_HASH, TITLE, IMAGE FROM CUSTOMERS WHERE USERNAME = ?  ORDER BY(ID) DESC ";//LIMIT ".$START.", ".$LIMIT."";
        $stmt = $this->STMT($conn,$query); $stmt->bind_param("s",$_SESSION['USERNAME']);
        $stmt->execute();  $items = $stmt->get_result();
        foreach($items as $item){
             echo $desktop->Game_gen($item);
        }
    }
}
?>