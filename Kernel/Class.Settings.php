<?php

class Settings extends DB
{
    public function isInstalled()
    {
        require 'requires.php';
        $stmt = $this->STMT($conn, 'SELECT INSTALLED FROM SETTINGS LIMIT 1');
        $stmt->execute(); $result = $stmt->get_result();
        foreach($result as $flag)
        {
            $installed = (int)$flag['INSTALLED'];
            if($installed === 1)
            {
                return TRUE;
            }
            else
            {
                return FALSE;
            }
        }
        return FALSE;
    }

    public function Install($USERNAME, $EMAIL, $PASSWORD, $REPEAT)
    {
        require 'requires.php';
        $stmt = $this->STMT($conn,'INSERT INTO SETTINGS (TITLE, INSTALLED) VALUES (?, ?)');
        $title = 'ALLEPO'; $installed = '1';
        $stmt->bind_param('ss',$title,$installed);
        if($this->Store($stmt) >= 1)
        {
            if($REPEAT === $PASSWORD)
            {
                $LEVEL = 'Administrator';
                $user->Register_INIT($USERNAME,$EMAIL,$PASSWORD, $REPEAT, $LEVEL);

            }
        }
        else
        {
            header('Location:index.php?install=False');
        }
    }
    public function Tables()
    {
        require 'requires.php';
        $stmt = $this->STMT($conn, 'SHOW TABLES FROM '.DB_NAME);
        $stmt->execute(); $result = $stmt->get_result();
        $stack = array();
        foreach($result as $dbstack)
        {
            foreach($dbstack as $table)
            {
                array_push($stack, $table);
            }
        }
        if(count($stack) > 0)
        {
            return $stack;
        }
        return array();
    }
    public function Restore()
    {
        require 'requires.php';
        $dbstack = $this->Tables();
        foreach($dbstack as $tables)
        {
            $stmt = $this->STMT($conn, 'TRUNCATE '.$tables);
            if($this->store($stmt) >= 1)
            {
                header('Location:index.php?Restored=True');
            }
            else
            {
                header('Location:index.php?Restored=False');
            }
        }
    }
    public function TableTruncate($table)
    {
        if($table !== 'SETTINGS' && $table !== 'USERS')
        {
            
            require 'requires.php';
            $table = $cerber->sqlsafe($table);
            $stmt = $this->STMT($conn,'TRUNCATE '.$table);
            if($this->Store($stmt) >= 1)
            {
                echo 'cleaned';
            }
            else
            {
                echo 'error';
            }
        }
        else
        {
            echo 'disallow';
        }
    }
    
    public function BrandName_set($name)
    {
        require 'requires.php';
        $stmt = $this->STMT($conn,"UPDATE SETTINGS SET TITLE = ? LIMIT 1");
        $stmt->bind_param("s",$name);
        if($this->Store($stmt))
        {
            header('Location:index.php?Settings');
        }
    }
    public function BrandName_get()
    {
        require 'requires.php';
        $stmt = $this->STMT($conn,"SELECT TITLE FROM SETTINGS LIMIT 1");
        $stmt->execute(); $result = $stmt->get_result();
        foreach($result as $title)
        {
            return $title['TITLE'];
        }
    }
}

?>