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
    public function Add($STORE_ID, $TITLE, $EMAIL, $PASSWORD)
    {
        require 'requires.php';
        $status = "Nie sprzedane";
        $stmt = $this->STMT($conn,"INSERT INTO PRODUCTS(STORE_ID, TITLE, EMAIL, PASSWORD, STATUS) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sssss", $STORE_ID, $TITLE, $EMAIL, $PASSWORD, $status);
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
    public function Update($TITLE)
    {
        require 'requires.php';
        $status = "Sprzedane";
        $stmt = $this->STMT($conn, 'UPDATE  PRODUCTS SET STATUS = ? WHERE TITLE  = ?');
        $stmt->bind_param("ss",$status, $TITLE);
        $db->Store($stmt);
        header("Location:index.php");
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
        $query = "SELECT * FROM PRODUCTS WHERE STORE_ID = ?  ORDER BY(ID) DESC LIMIT ".$START.", ".$LIMIT."";
        $stmt = $this->STMT($conn,$query); $stmt->bind_param("s",$_SESSION['STORE_ID']);
        $stmt->execute();  $items = $stmt->get_result();
        foreach($items as $item){
             echo $desktop->Products_list_gen($item);
        }
    }
}

class Customers extends DB
{
    public function Add($STORE_ID, $TITLE, $USERNAME, $EMAIL, $PRICE, $CURRENCY)
    {
        require "requires.php";
        $stmt = $this->STMT($conn,"INSERT INTO CUSTOMERS(STORE_ID, TITLE, USERNAME, EMAIL, PRICE, CURRENCY,ACTIVE,
                            MONTH, YEAR) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $status = "Sprzedane"; $month = date("M"); $year = date("Y");
        $stmt->bind_param("sssssssss", $STORE_ID, $TITLE, $USERNAME, $EMAIL, $PRICE, $CURRENCY, $status, $month, $year);
        $this->Store($stmt);
        $product->Update($TITLE);
        #echo "1";

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
    public function Long_ACTIVATION_URL($MAIL)
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
    public function GET_ACTIVATION_HASH($TITLE)
    {
        require "requires.php";
        if(!empty($TITLE))
        {
            $exists = False;
            $status = "Sprzedane";
            $stmt = $this->STMT($conn,"SELECT EMAIL FROM PRODUCTS WHERE STATUS = ? AND TITLE LIKE ?  LIMIT 1");
            $stmt->bind_param("ss", $status,$TITLE);
            $stmt->execute(); $result = $stmt->get_result();
            foreach($result as $game)
            {
                $exists = TRUE;
                if($exists === TRUE)
                {
                    echo md5($game['EMAIL']);
                }
            }
            if($exists === FALSE)
            {
                echo "NOTFOUND";
            }
           
        }
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
 
class Microsoft extends DB
{
    public $GAME_TITLE;
    public $GAME_CONTACT;
    public $GAME_CODE;
    public $GAME_HASH;
    public $GAME_MAIL;

    function __construct($TITLE, $EMAIL, $CODE)
    {
        $this->GAME_TITLE = $TITLE;
        $this->GAME_CONTACT = $EMAIL;
        $this->GAME_CODE = $CODE; 
        $this->GAME_MAIL = $this->GAME_EMAIL();
        $this->GAME_HASH = md5($this->GAME_MAIL);
        $this->INIT();
        
    }
    public function GAME_EMAIL()
    {
        require "requires.php";
        if(!empty($this->GAME_TITLE))
        {
            $status = "Sprzedane";
            $stmt = $this->STMT($conn,"SELECT EMAIL FROM PRODUCTS WHERE STATUS = ? AND TITLE LIKE ?  LIMIT 1");
            $stmt->bind_param("ss", $status,$this->GAME_TITLE);
            $stmt->execute(); $result = $stmt->get_result();
            foreach($result as $game)
            {
               return $game['EMAIL'];
            }
           
        }
    }
    public function STACK_SELECTOR($KEY)
    {
        if($KEY === 'HASH' || $KEY === 'FIRST_DAY' || $KEY === 'FIRST_LIMIT' || $KEY === 'LOCK_TIME')
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
        if($this->isStack())
        {
            $this->Update();
        }
        else
        {
            $this->Add();
        }
    }
    public function Add()
    {
        require 'requires.php';
        $firstDay = '1'; $firstLimit = '2'; $lockTime = '0';
        $stmt = $this->STMT($conn,'INSERT INTO ACTIVATOR(CONTACT, GAME_MAIL,HASH,FIRST_DAY,FIRST_LIMIT, LOCK_TIME)
                                    VALUES (?, ?, ?, ?, ?, ?)');
        $stmt->bind_param('ssssss',$this->GAME_CONTACT, $this->GAME_MAIL, $this->GAME_HASH, $firstDay,$firstLimit,$lockTime);
        if($this->Store($stmt) > 0)
        {
            $this->SelectAccount();
        }
        
    }
    public function Update()
    {
        require 'requires.php';
        $FIRST_DAY = (int)$this->STACK_SELECTOR('FIRST_DAY');
        $FIRST_LIMIT = (int)$this->STACK_SELECTOR('FIRST_LIMIT');
        $LOCK_WEEK = (int)$this->STACK_SELECTOR('LOCK_TIME');
        if($FIRST_DAY === 1 && $FIRST_LIMIT === 2 && $LOCK_WEEK === 0)
        {
            $limit = (int)1;
            $stmt = $this->STMT($conn,'UPDATE ACTIVATOR SET FIRST_LIMIT = ? WHERE HASH = ?');
            $stmt->bind_param('is',$limit,$this->GAME_HASH);
            if($this->Store($stmt) > 0)
            {
                $this->SelectAccount();
            }
             
        }
        if($FIRST_DAY === 1 && $FIRST_LIMIT === 1 && $LOCK_WEEK === 0)
        {
            $limit = (int)0;
            $lock = (int)7;
            $stmt = $this->STMT($conn,'UPDATE ACTIVATOR SET FIRST_DAY = ?, FIRST_LIMIT = ?, LOCK_TIME = ? WHERE HASH = ?');
            $stmt->bind_param('iiis',$limit,$limit,$lock,$this->GAME_HASH);
            if($this->Store($stmt) > 0)
            {
                $this->SelectAccount();
            }
        }
        
        if ($LOCK_WEEK > 0)
        {
            echo 'Blocked ';
        }
    }
    public function SelectAccount()
    {
        require "requires.php";
        if(!empty($this->GAME_TITLE))
        {
            $exists = False;
            $status = "Sprzedane";
            $stmt = $this->STMT($conn,"SELECT * FROM PRODUCTS WHERE STATUS = ? AND TITLE LIKE ?  LIMIT 1");
            $stmt->bind_param("ss", $status,$this->GAME_TITLE);
            $stmt->execute(); $result = $stmt->get_result();
            foreach($result as $game)
            {
                $exists = TRUE;
                if($exists === TRUE)
                {
                    $this->Run($game['PASSWORD']);
                }
            }
            if($exists === FALSE)
            {
                echo "NOTFOUND";
            }
           
        }
    }
     
    private function Run($PWD)
    {
        $cmd = escapeshellcmd("sudo -u venom python3 MICROSOFT/Main.py ".$this->GAME_CONTACT .' '.$this->GAME_MAIL .' '.$PWD .' '. $this->GAME_CODE .' '.$this->GAME_TITLE);
        echo shell_exec($cmd);
    }
}
class Long_activator extends DB
{
    public $GAME_CONTACT;
    public $GAME_CODE;
    public $GAME_HASH;
    public $GAME_EMAIL;
    public $GAME_TITLE;
    function __construct($hash,$contact,$code,$title)
    {
        $this->GAME_HASH = $hash;
        $this->GAME_CONTACT = $contact;
        $this->GAME_CODE = $code;
        $this->GAME_TITLE = $title;
        if($this->Find_activator())
        {
            $this->SelectProduc();
         }
        else
        {
            $micro = new Microsoft($this->GAME_TITLE,$this->GAME_CONTACT,$this->GAME_CODE);
         }
    }
    public function Find_activator()
    {
        require "requires.php";
        $stmt = $this->STMT($conn,"SELECT CONTACT, GAME_MAIL FROM ACTIVATOR WHERE HASH = ?");
        $stmt->bind_param("s",$this->GAME_HASH); $stmt->execute();
        $result = $stmt->get_result();
        foreach($result as $item)
        {
            $this->GAME_EMAIL = $item['GAME_MAIL'];
            $this->GAME_CONTACT = $item['CONTACT'];
            return TRUE;
        }
        return FALSE;
    }
    public function SelectProduc()
    {
        require "requires.php";
        if(!empty($this->GAME_EMAIL))
        {
            $exists = False;
            $status = "Sprzedane";
            $stmt = $this->STMT($conn,"SELECT * FROM PRODUCTS WHERE STATUS = ? AND EMAIL = ?  LIMIT 1");
            $stmt->bind_param("ss", $status,$this->GAME_EMAIL);
            $stmt->execute(); $result = $stmt->get_result();
            foreach($result as $game)
            {
                $exists = TRUE;
                if($exists === TRUE)
                {
                    $micro = new Microsoft($game['TITLE'],$this->GAME_CONTACT,$this->GAME_CODE);
                     
                }
            }
            if($exists === FALSE)
            {
                echo "NOTFOUND";
            }
           
        }
    }
}
?>