<?php 

 class HTML
 {
    public function Load($template)
    {
        $path = "ASSETS/HTML/".$template.".html";
        if(file_exists($path))
        {  
            return file_get_contents($path);
        }
    }
    public function Install()
    {
        echo $this->Load('Install');
    }
    public function Login()
    {
        return $this->Load("Login");
    }
    
    public function Header()
    {
        return $this->Load("Header");
    }
    public function Body()
    {
        return $this->Load("Body");
    }
    
    public function Navbar()
    {
        return $this->Load("Navbar");
    }
    public function Sidebar()
    {
        return $this->Load("Sidebar");
    }

    public function Footer()
    {
        return $this->Load("Footer");
    }
    public function StoreBox()
    {
        return $this->Load("Stores");
    }
    public function TopUserOptions()
    {
        return $this->Load('TopUserOptions');
    }
    public function Logout()
    {
        return $this->Load('Logout');
    }
    public function Customers_table()
    {
        require "requires.php";
        $output =  $this->Load("Customers_table");
        $output = str_replace("PAGE++;", "Klienci", $output);
        $output = str_replace("STORE_NAME++;" , $store->NameById($_SESSION['STORE_ID']), $output);
        $output = str_replace("STORE_ID++;" , $_SESSION['STORE_ID'], $output);

        return $output;
    }
    public function Customers_list()
    {
        return $this->Load("Customers_list");
    }
    public function Products_table()
    {
        require "requires.php";
        $output =  $this->Load("Products_table");
        $output = str_replace("PAGE++;", "Produkty", $output);
        $output = str_replace("STORE_NAME++;" , $store->NameById($_SESSION['STORE_ID']), $output);
        $output = str_replace("STORE_ID++;" , $_SESSION['STORE_ID'], $output);
        return $output;
    }
    public function Products_list()
    {
        return $this->Load("Products_list");
    }
    public function Activation_page($title)
    {
        $output =  $this->Load("Activation_page");
        $output = str_replace("TITLE++;", $title, $output);
        return $output;
    }
    public function Activation_hash($title,$hash)
    {
        $output =  $this->Load("Activation_hash");
        $output = str_replace("TITLE++;", $title, $output);
        $output = str_replace("HASH++;", $hash, $output);
        return $output;
    }
    public function Stats()
    {
        return $this->Load("Stats");
    }
    public function Income()
    {
        return $this->Load("Income");
    }
    public function Stores_place()
    {
        return $this->Load('Stores_place');
    }
    public function Settings()
    {
        return $this->Load('Settings');
    }
 }

 class Desktop extends HTML
 {
      
      public function Customers_page()
      {
        $output = $this->Body();
        $output = str_replace("HEADER++;", $this->Header(), $output);
        $output = str_replace("NAVBAR++;", $this->Navbar(), $output); 
        $output = str_replace("USERNAME++;", $_SESSION['USERNAME'], $output); 
        $output = str_replace("SIDEBAR++;", $this->Sidebar(), $output);
        $output = str_replace("CONTENT++;", $this->Customers_table(), $output);
        $output = str_replace("JS++;", $this->Footer(), $output);
        echo $output; 
      }
      public function Products_page()
      {
        $output = $this->Body();
        $output = str_replace("HEADER++;", $this->Header(), $output);
        $output = str_replace("NAVBAR++;", $this->Navbar(), $output); 
        $output = str_replace("USERNAME++;", $_SESSION['USERNAME'], $output); 
        $output = str_replace("SIDEBAR++;", $this->Sidebar(), $output);
        $output = str_replace("CONTENT++;", $this->Products_table(), $output);
        $output = str_replace("JS++;", $this->Footer(), $output);
        echo $output; 
      }
      public function Stats_page()
      {
        $output = $this->Body();
        $output = str_replace("HEADER++;", $this->Header(), $output);
        $output = str_replace("NAVBAR++;", $this->Navbar(), $output); 
        $output = str_replace("USERNAME++;", $_SESSION['USERNAME'], $output); 
        $output = str_replace("SIDEBAR++;", $this->Sidebar(), $output);
        $output = str_replace("CONTENT++;", $this->Stats(), $output);
        $output = str_replace("JS++;", $this->Footer(), $output);
        echo $output; 
      }
      public function Stores_page($stack)
      {
        $output = $this->Body();
        $output = str_replace("HEADER++;", $this->Header(), $output);
        $output = str_replace("NAVBAR++;", $this->Navbar(), $output); 
        $output = str_replace("USERNAME++;", $_SESSION['USERNAME'], $output); 
        $output = str_replace("SIDEBAR++;", $this->Sidebar(), $output);
        $output = str_replace('CONTENT++;', $this->Stores($stack), $output);
        $output = str_replace("JS++;", $this->Footer(), $output);
        echo $output; 
      }
      public function Stores($stack)
      {
        $output = $this->Stores_place();
        foreach($stack as $market)
        {
            $output .= $this->StoreBox();
            $output = str_replace("NAME++;", $market['NAME'], $output);
            $output = str_replace('ID++;', $market['ID'], $output);
        }
        return $output.'</div>'; //last div to close <div class='stores_place'>
      }
      public function UserOptions($stack)
      {
          $output = $this->TopUserOptions();
          $output = str_replace("NAME++;", $stack['NAME'], $output);
        $output = str_replace('ID++;', $stack['ID'], $output);
        return $output;
      }
      public function Customers_list_gen($stack)
      {
          require "requires.php";
          $output = $this->Customers_list();
          if($cerber->evenODD($stack['ID']))
          {
            $output = str_replace("COLORED++;", 'white', $output);
          }
          else
          {
            $output = str_replace("COLORED++;", 'gray', $output);
          }
          $output = str_replace("ID++;", $stack['ID'], $output);
          $output = str_replace("TITLE++;", $stack['TITLE'], $output);
          $output = str_replace("STORE_NAME++;", $store->NameById($stack['STORE_ID']), $output);
          $output = str_replace("USERNAME++;", $stack['USERNAME'], $output);
          $output = str_replace("EMAIL++;", substr($stack['EMAIL'],0,15).'...', $output);
          $output = str_replace("PRICE++;", $stack['PRICE'] .' '. $stack['CURRENCY'], $output);
          $output = str_replace("STATUS++;", $stack['ACTIVE'], $output);
          $output = str_replace("DATE++;", $stack['STAMP'], $output);
          return $output;

      }
      public function Products_list_gen($stack)
      {
        require "requires.php";
        $output = $this->Products_list();
        if($cerber->evenODD($stack['ID']))
        {
         $output = str_replace("COLORED++;", 'white', $output);
        }
        else
        {
            $output = str_replace("COLORED++;", 'gray', $output);
        }
        $output = str_replace("ID++;", $stack['ID'], $output);
        $output = str_replace("TITLE++;", $stack['TITLE'], $output);
        $output = str_replace("STORE_NAME++;", $store->NameById($stack['STORE_ID']), $output);
        $output = str_replace("EMAIL++;", $stack['EMAIL'], $output);
        $output = str_replace("PASSWORD++;", $stack['PASSWORD'], $output);
        $output = str_replace("STATUS++;", $stack['STATUS'], $output);
        $output = str_replace("DATE++;", $stack['STAMP'], $output);
        return $output;
      }
     public function Settings_page()
     {
        $output = $this->Body();
        $output = str_replace("HEADER++;", $this->Header(), $output);
        $output = str_replace("NAVBAR++;", $this->Navbar(), $output); 
        $output = str_replace("USERNAME++;", $_SESSION['USERNAME'], $output); 
        $output = str_replace("SIDEBAR++;", $this->Sidebar(), $output);
        $output = str_replace("CONTENT++;", $this->Settings(), $output);
        $output = str_replace("USERNAME++;", $_SESSION['USERNAME'], $output);
        $output = str_replace("EMAIL++;", $_SESSION['EMAIL'], $output);
        $output = str_replace("JS++;", $this->Footer(), $output);
        echo $output; 
     }
 }

?>