<?php 

class Cerber
{
    function no_php($value)
    {
        $value = str_replace("<php","",$value);
        $value = str_replace("?>","",$value);
        $value = str_replace("<","",$value);
        $value = str_replace("system(","",$value);
        $value = str_replace("(","",$value);
        $value = str_replace("cmd","",$value);
        $value = str_replace("eval","",$value);
        $value = str_replace("fopen","",$value);
        $value = str_replace("fgets","",$value);
        return $value;
    }
    public function sqlsafe($value)
    {
        $value = $this->no_php($value);
        $value = str_replace("'",'',$value);
        $value = str_replace('"', '',$value);
        $value = str_replace('`','',$value);
        $value = str_replace('\\','',$value);
        $value = str_replace('/','',$value);
        $value = str_replace('<','',$value);
        $value = str_replace('<script>','',$value);
        $value = strip_tags($value);
        return $value;
    }
    public function evenODD($number)
    { 
        if($number % 2 == 0){ 
            return TRUE;  
        } 
        else{ 
            return FALSE; 
        } 
    }
    function Session_startup()
    {
        if(session_status() === PHP_SESSION_NONE)
        {
            session_start();
        }
    }
    function session_add($KEY,$VALUE)
    {
        $KEY = $this->sqlsafe($KEY); $VALUE = $this->sqlsafe($VALUE);
        if(session_status() === PHP_SESSION_ACTIVE)
        {
            $_SESSION[$KEY] = $VALUE;
        }
    }
    function LoggedIN()
    {
        if(isset($_SESSION['USERID']))
        {
            return TRUE;
        }
        else
        {
            return FALSE;
        }
    }
    function isAdmin()
    {
        if($this->LoggedIN())
        {
            $flag = explode('_',$_SESSION['USERID']);
            if($flag[0]==='Administrator')
            {
                return TRUE;
            }
            return FALSE;
        }
        return FALSE;
    }
    function session_cleanup()
    {
        session_destroy();
        session_unset();
        //redirect
    }
}

?>