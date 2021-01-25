<?php


class User extends DB
{

    public function isUser($email)
    {
        require "requires.php";
        $stmt = $this->STMT($conn,"SELECT EMAIL FROM USERS WHERE EMAIL = ?");
        $stmt->bind_param("s",$email); 
        return $this->Exists($stmt);
    }
    public function Register_INIT($USERNAME, $EMAIL, $PASSWORD, $REPEAT)
    {
        if(filter_var($EMAIL, FILTER_VALIDATE_EMAIL) && $PASSWORD === $REPEAT)
        {
            if($this->isUser($EMAIL) === FALSE)
            {
                $this->Add($USERNAME, $EMAIL, $PASSWORD, $REPEAT);
            }
            else
            {
                header("Location:index.php?User&already_exists=TRUE");
            }
        }
    }
    public function Add($USERNAME, $EMAIL, $PASSWORD, $REPEAT)
    {
        require "requires.php";
        if(filter_var($EMAIL, FILTER_VALIDATE_EMAIL) && $PASSWORD === $REPEAT)
        {
            $userid = password_hash(microtime(), PASSWORD_DEFAULT);
            $PASSWORD = password_hash($PASSWORD, PASSWORD_DEFAULT);
            $stmt = $this->STMT($conn,"INSERT INTO USERS (USERID, USERNAME, EMAIL, PASSWORD,AVATAR) VALUES (?, ?, ?, ?, ?)");
            $stmt->bind_param("sssss", $userid, $USERNAME, $EMAIL, $PASSWORD, $DEFAULT_AVATAR);
            if($this->Store($stmt) >= 1)
            {
                $store->Store_INIT('Default','default@gmail.com','default');
                header('Location:index.php?Stores');
            }
            else
            {
                header('Location:index.php?User&Add=False');
            }
        }
    }


    public function Login($EMAIL, $PASSWORD)
    {
        if(filter_var($EMAIL, FILTER_VALIDATE_EMAIL) && $this->isUser($EMAIL))
        {
            require "requires.php";
            $stmt = $this->STMT($conn, "SELECT USERID, USERNAME, EMAIL, PASSWORD, AVATAR FROM USERS WHERE EMAIL = ?");
            $stmt->bind_param("s",$EMAIL); $stmt->execute();
            $result = $stmt->get_result();
            foreach($result as $account)
            {
                if(password_verify($PASSWORD, $account['PASSWORD']))
                {
                    $this->Session_PACK($account['USERID'], $account['USERNAME'], $account['EMAIL'], $account['AVATAR']);
                }
                else
                {
                    header("Location:index.php?wrong_password");
                }
            }
        }
    }
    public function Session_PACK($USERID, $USERNAME, $EMAIL,  $AVATAR)
    {
        require "requires.php";
        $cerber->session_add("USERID",$USERID);
        $cerber->session_add("USERNAME",$USERNAME);
        $cerber->session_add("EMAIL",$EMAIL);
        $cerber->session_add("AVATAR",$AVATAR);
        $cerber->session_add("STORE_ID","1");
        header("Location:index.php?Stores");
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
        $stmt = $db->STMT($conn, 'DELETE FROM USERS WHERE USERID = ?');
        $stmt->bind_param("s",$USERID);
        $db->Store($stmt);
        header("Location:index.php");
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
}


?>