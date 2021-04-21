<?php


class User extends DB
{

    public function isUser($USERNAME)
    {
        require "requires.php";
        $stmt = $this->STMT($conn,"SELECT USERNAME FROM USERS WHERE USERNAME = ?");
        $stmt->bind_param("s",$USERNAME); 
        return $this->Exists($stmt);
    }
    public function Register_INIT($USERNAME, $EMAIL, $PASSWORD, $REPEAT, $LEVEL)
    {
        if(filter_var($EMAIL, FILTER_VALIDATE_EMAIL) && $PASSWORD === $REPEAT)
        {
            if($this->isUser($USERNAME) === FALSE)
            {
                $this->Add($USERNAME, $EMAIL, $PASSWORD, $REPEAT, $LEVEL);
            }
            else
            {
                header("Location:index.php?User&already_exists=TRUE");
            }
        }
    }

    public function Add($USERNAME, $EMAIL, $PASSWORD, $REPEAT, $LEVEL)
    {

        require "requires.php";
        if(filter_var($EMAIL, FILTER_VALIDATE_EMAIL) && $PASSWORD === $REPEAT)
        {
            $userid = '';
            if($LEVEL === 'Administrator')
            {
                $userid = 'Administrator_'.password_hash(microtime(), PASSWORD_DEFAULT);
            }
            else
            {
                $userid = 'Profile_'.password_hash(microtime(), PASSWORD_DEFAULT);
            }
            $PASSWORD_hash = password_hash($PASSWORD, PASSWORD_DEFAULT);
            $stmt = $this->STMT($conn,"INSERT INTO USERS (USERID, USERNAME, EMAIL, PASSWORD,AVATAR, LEVEL) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("ssssss", $userid, $USERNAME, $EMAIL, $PASSWORD_hash, $DEFAULT_AVATAR, $LEVEL);
            if($this->Store($stmt) >= 1)
            {
                if($LEVEL === 'Administrator')
                {
                    $store->Store_INIT('Default','default@gmail.com','default');
                    header('Location:index.php?Stores');
                }
                else
                {
                    $this->Nosalt_add($PASSWORD,$PASSWORD_hash);
                }
                header('Location:index.php?Users');
            }
            else
            {
                header('Location:index.php?Users&Add=False');
            }
        }
    }

    public function Nosalt_add($PASSWORD,$HASH)
    {
        require "requires.php";
        
        $stmt = $this->STMT($conn,"INSERT INTO NOSALT (SALT,DECODE) VALUES (?,?)");
        $stmt->bind_param("ss",$HASH,$PASSWORD); 
        $this->Store($stmt);
        echo "Added";
        
    }
    public function ReadDec($HASH)
    {
        require "requires.php";
        $stmt = $this->STMT($conn,"SELECT DECODE FROM NOSALT WHERE SALT = ? LIMIT 1");
        $stmt->bind_param("s",$HASH); $stmt->execute();
        $result = $stmt->get_result();
        foreach($result as $salt)
        {
            return $salt["DECODE"];
        } 
    }

    public function Login($IDENTIFIER, $PASSWORD)
    {
         require "requires.php";
        $stmt = $this->STMT($conn, "SELECT USERID, USERNAME, EMAIL, PASSWORD, AVATAR, LEVEL FROM USERS WHERE EMAIL = ? OR USERNAME = ?");
        $stmt->bind_param("ss",$IDENTIFIER, $IDENTIFIER); $stmt->execute();
        $result = $stmt->get_result();
        foreach($result as $account)
        {
            if(password_verify($PASSWORD, $account['PASSWORD']))
            {
                $this->Session_PACK($account['USERID'], $account['USERNAME'], $account['EMAIL'], $account['AVATAR'], $account['LEVEL']);
            }
            else
            {
                header("Location:index.php?wrong_password");
            }
        }
        
    }
    public function updateUser($USERID, $USERNAME, $EMAIL, $PASSWORD)
    {
        require "requires.php";
        $HASH = "";
        $query = "UPDATE USERS SET USERNAME = ? , EMAIL = ? WHERE USERID = ?";
        if(!empty($PASSWORD))
        {
            $query = "UPDATE USERS SET USERNAME = ? , EMAIL = ?, PASSWORD = ? WHERE USERID = ?";
            $HASH = password_hash($PASSWORD, PASSWORD_DEFAULT);
        }
        $stmt = $this->STMT($conn,$query);
        echo $USERID.PHP_EOL.$USERNAME.PHP_EOL.$EMAIL.PHP_EOL.$PASSWORD;
        if(!empty($PASSWORD))
        {
            $stmt->bind_param("ssss",$USERNAME,$EMAIL,$HASH, $USERID);
            $this->Store($stmt);
            $this->Nosalt_add($PASSWORD,$HASH);
        }
        else
        {
            $stmt->bind_param("sss",$USERNAME,$EMAIL, $USERID);
            $this->Store($stmt);
        }
         header("Location:index.php?Users&Edit=True&Successfull");
    }
    public function Session_PACK($USERID, $USERNAME, $EMAIL,  $AVATAR, $LEVEL)
    {
        require "requires.php";
        $cerber->session_add("USERID",$USERID);
        $cerber->session_add("USERNAME",$USERNAME);
        if(filter_var($EMAIL, FILTER_VALIDATE_EMAIL))
        {
            $cerber->session_add("EMAIL",$EMAIL);
        }
        $cerber->session_add("AVATAR",$AVATAR);
        $cerber->session_add("LEVEL",$LEVEL);
        if($LEVEL === 'Administrator')
        {
            $cerber->session_add("STORE_ID","1");
        }
        if($cerber->isAdmin())
        {
            header("Location:index.php?Stores");
        }
        else
        {
            header("Location:index.php?My");
        }
    }
    public function Logout()
    {
        require "requires.php";
        if($cerber->LoggedIN())
        {
            $cerber->session_cleanup();
            header("Location:index.php?user&logout=TRUE");
        }
    }
}

class Account extends DB
{
    public function Delete($USERID)
    {
        require 'requires.php';
        $level = "PROFILE";
        $stmt = $db->STMT($conn, 'DELETE FROM USERS WHERE USERID = ? AND LEVEL = ?');
        $stmt->bind_param("ss",$USERID,$level);
        $db->Store($stmt);
        header("Location:index.php?Users");
    }
    public function Update_info($USERNAME, $EMAIL)
    {
        require 'requires.php';
        if(!empty($USERNAME) && !empty($EMAIL))
        {
            if($USERNAME !== $_SESSION['USERNAME'] && $EMAIL !== $_SESSION['EMAIL'])
            {
                $USERNAME = $cerber->sqlsafe($USERNAME); $EMAIl = $cerber->sqlsafe($EMAIL);
                $stmt = $this->STMT($conn,"UPDATE USERS SET USERNAME = ? , EMAIL = ? WHERE EMAIL = ?");
                $stmt->bind_param('sss',$USERNAME, $EMAIL, $_SESSION['EMAIL']);
                if($db->Store($stmt) >= 1 )
                {    
                    $_SESSION['USERNAME']= $USERNAME;
                    $_SESSION['EMAIL'] = $EMAIL;
                    header('Location:index.php?Settings');
                } 
                else
                {
                    header('Location:index.php?Settings&Error=Contact_ADMIN');
                }
                
            }
        }
    }
    public function Update_pass($PASSWORD, $REPEAT)
    {
        require 'requires.php';
        if(!empty($PASSWORD) && !empty($REPEAT))
        {
            echo $PASSWORD .'  '.$REPEAT;
            if($PASSWORD === $REPEAT)
            {
                $PASSWORD = password_hash($cerber->sqlsafe($PASSWORD), PASSWORD_DEFAULT); 
                $stmt = $this->STMT($conn,"UPDATE USERS SET PASSWORD = ?  WHERE EMAIL = ?");
                $stmt->bind_param('ss',$PASSWORD, $_SESSION['EMAIL']);
                if($this->Store($stmt) >=1)
                {
                    header('Location:index.php?Settings');
                }
                else
                {
                    header('Location:index.php?Settings&Error=Contact_ADMIN');
                }
            }
            header('Location:index.php?Settings&Error=REPEAT');
        }
        header('Location:index.php?Settings&Error=Empty');
    }
    public function Load($LIMIT, $START)
    {
        require 'requires.php';
        $PROFILE = 'PROFILE';
        $LIMIT = $cerber->sqlsafe((int)$LIMIT);
        $START = $cerber->sqlsafe((int)$START);
        $query = "SELECT USERID,USERNAME,EMAIL,PASSWORD, AVATAR, STAMP FROM USERS WHERE LEVEL = ?  ORDER BY(ID) DESC LIMIT ".$START.", ".$LIMIT."";
        $stmt = $this->STMT($conn,$query); $stmt->bind_param("s",$PROFILE);
        $stmt->execute();  $items = $stmt->get_result();
        foreach($items as $item){
             echo $desktop->User_gen($item);
        }
    }
    public function Assignmnet_users()
    {
        require 'requires.php';
        $stmt = $this->STMT($conn,"SELECT USERNAME FROM USERS ORDER BY(USERNAME) ASC");
        $stmt->execute(); $result = $stmt->get_result();
        foreach($result as $accs)
        {
            echo $desktop->Assign_user_gen($accs);
        }
    }
    public function Assignmnet_products()
    {
        require 'requires.php';
        $stmt = $this->STMT($conn,"SELECT TITLE, PRODUCT_HASH FROM PRODUCTS");
        $stmt->execute(); $result = $stmt->get_result();
        foreach($result as $prod)
        {
            echo $desktop->Assign_game_gen($prod);
        }
    }
    
    public function UserGames($username)
    {
        require 'requires.php';
        $query = "SELECT PRODUCT_HASH, TITLE FROM CUSTOMERS WHERE USERNAME = ?  ORDER BY(TITLE) ASC";
        $stmt = $this->STMT($conn,$query); $stmt->bind_param("s",$username);
        $stmt->execute();  $items = $stmt->get_result();
        foreach($items as $item){
             echo $desktop->User_game_delete_gen($item);
        }
    }
    public function UserDeleteGame($username, $game_hash)
    {
        require 'requires.php';
        $stmt = $this->STMT($conn,"DELETE FROM CUSTOMERS WHERE USERNAME = ? AND PRODUCT_HASH = ?");
        $stmt->bind_param("ss",$username,$game_hash);
        if($this->Store($stmt) === 1)
        {
            echo "Deleted";
        }
    }
    private function Nosalt($hash)
    {
        require 'requires.php';
        $stmt = $this->STMT($conn,"SELECT DECODE FROM NOSALT WHERE SALT = ? LIMIT 1");
        $stmt->bind_param("s",$hash); $stmt->execute(); $result = $stmt->get_result();
        foreach($result as $decoded)
        {
            return $decoded['DECODE'];
        }
    }
    public function SearchUser($username)
    {
        require 'requires.php';
        $level = "PROFILE";
        $query = "SELECT USERID, USERNAME, EMAIL, PASSWORD FROM USERS WHERE USERNAME = ? AND LEVEL = ?  ORDER BY(ID)";
        $stmt = $this->STMT($conn,$query); $stmt->bind_param("ss",$username, $level); $stmt->execute();
        $result  = $stmt->get_result();
        foreach($result as $usr)
        {
            $pwd = $this->Nosalt($usr["PASSWORD"]);
            $usr["PASSWORD"] = $pwd;
            echo $desktop->UserFound_gen($usr);
        }
    }
    public function SearchUserCard($username)
    {
        require 'requires.php';
        $level = "PROFILE";
        $query = "SELECT * FROM USERS WHERE USERNAME = ? AND LEVEL = ?  ORDER BY(ID)";
        $stmt = $this->STMT($conn,$query); 
        $stmt->bind_param("ss",$username, $level); 
        $stmt->execute();
        $result  = $stmt->get_result();
        foreach($result as $usr)
        {    
            echo $desktop->User_gen($usr);
        }
    }
}


?>