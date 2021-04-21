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
    public function Customer_search_result()
    {
        return $this->Load("Customer_search");
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
    
    public function Activation_hash($title,$hash)
    {
        require 'requires.php';
        $output =  $this->Load("Activation_hash");
        $output = str_replace("BRAND_NAME++;", $settings->BrandName_get(), $output);
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
    public function Recomended_place()
    {
        require 'requires.php';
        $output =  $this->Load("Recomended_place");
        $output = str_replace("PAGE++;", "Polecane", $output);
        $output = str_replace("STORE_NAME++;" , $store->NameById($_SESSION['STORE_ID']), $output);
        $output = str_replace("STORE_ID++;" , $_SESSION['STORE_ID'], $output);
        return $output;
    }
    public function Recomended_item()
    {
        return $this->Load("Recomended_item");
    }
    public function Recomended_activation_item()
    {
        return $this->Load('Recomended_activation_item');
    }
    public function Settings()
    {
        return $this->Load('Settings');
    }
    public function UsersPlace()
    {
        return $this->Load('UsersPlace');
    }
    public function User()
    {
        return $this->Load('User');
    }
    public function MyGamesPlace()
    {
        return $this->Load('MyGames_board');
    }
    public function MyGame()
    {
        return $this->Load('MyGame');
    }
    public function SelectUser()
    {
        return $this->Load('Assign_select_user');
    }
    public function SelectGame()
    {
        return $this->Load('Assign_select_game');
    }
    public function User_game_delete()
    {
        return $this->Load('User_game_delete');
    }
    public function UserFound()
    {
        return $this->Load('UserFound');
    }
 }

 class Desktop extends HTML
 {
      
      public function Customers_page()
      {
        require 'requires.php';
        $output = $this->Body();
        $output = str_replace("HEADER++;", $this->Header(), $output);
        $output = str_replace("BRAND_NAME++;", $settings->BrandName_get(), $output);
        $output = str_replace("NAVBAR++;", $this->Navbar(), $output); 
        $output = str_replace("USERNAME++;", $_SESSION['USERNAME'], $output); 
        $output = str_replace("SIDEBAR++;", $this->Sidebar(), $output);
        $output = str_replace("CONTENT++;", $this->Customers_table(), $output);
        $output = str_replace("JS++;", $this->Footer(), $output);
        echo $output; 
      }
      public function Products_page()
      {
        require 'requires.php';
        $output = $this->Body();
        $output = str_replace("HEADER++;", $this->Header(), $output);
        $output = str_replace("BRAND_NAME++;", $settings->BrandName_get(), $output);
        $output = str_replace("NAVBAR++;", $this->Navbar(), $output); 
        $output = str_replace("USERNAME++;", $_SESSION['USERNAME'], $output); 
        $output = str_replace("SIDEBAR++;", $this->Sidebar(), $output);
        $output = str_replace("CONTENT++;", $this->Products_table(), $output);
        $output = str_replace("JS++;", $this->Footer(), $output);
        echo $output; 
      }
      public function Stats_page()
      {
        require 'requires.php';
        $output = $this->Body();
        $output = str_replace("HEADER++;", $this->Header(), $output);
        $output = str_replace("BRAND_NAME++;", $settings->BrandName_get(), $output);
        $output = str_replace("NAVBAR++;", $this->Navbar(), $output); 
        $output = str_replace("USERNAME++;", $_SESSION['USERNAME'], $output); 
        $output = str_replace("SIDEBAR++;", $this->Sidebar(), $output);
        $output = str_replace("CONTENT++;", $this->Stats(), $output);
        $output = str_replace("JS++;", $this->Footer(), $output);
        echo $output; 
      }
      public function Stores_page($stack)
      {
        require 'requires.php';
        $output = $this->Body();
        $output = str_replace("HEADER++;", $this->Header(), $output);
        $output = str_replace("BRAND_NAME++;", $settings->BrandName_get(), $output);
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
          #$output = str_replace("HASH++;", $stack['ID'], $output);
          $output = str_replace("TITLE++;", $stack['TITLE'], $output);
          $output = str_replace("STORE_NAME++;", $store->NameById($stack['STORE_ID']), $output);
          $output = str_replace("USERNAME++;", $stack['USERNAME'], $output);
          $output = str_replace("EMAIL++;", substr($stack['EMAIL'],0,15).'...', $output);
          $output = str_replace("PRICE++;", $stack['PRICE'] .' '. $stack['CURRENCY'], $output);
          $output = str_replace("HASH++;", $stack['PRODUCT_HASH'], $output);
          $output = str_replace("DATE++;", $stack['STAMP'], $output);
          return $output;

      }
      public function Customer_search_result_gen($stack)
      {
            require "requires.php";

            $output = $this->Customer_search_result();
            $output = str_replace("ID++;", $stack['ID'], $output);
            $output = str_replace("TITLE++;", $stack['TITLE'], $output);
            $output = str_replace("STORE_NAME++;", $store->NameById($stack['STORE_ID']), $output);
            $output = str_replace("USERNAME++;", $stack['USERNAME'], $output);
            $output = str_replace("EMAIL++;", $stack['EMAIL'], $output);
            $output = str_replace("PRICE++;", $stack['PRICE'] .' '. $stack['CURRENCY'], $output);
            $output = str_replace("HASH++;", $stack['PRODUCT_HASH'], $output);
            $output = str_replace("DATE++;", $stack['STAMP'], $output);
            return $output;
      }
      public function Products_list_gen($stack)
      {
        require "requires.php";
        $output = $this->Products_list();
        $img = $stack['IMAGE'];
        if($cerber->evenODD($stack['ID']))
        {
         $output = str_replace("COLORED++;", 'white', $output);
        }
        else
        {
            $output = str_replace("COLORED++;", 'gray', $output);
        }
        if($img !== "")
        {
            $output = str_replace("IMAGE++;",'<img src="'.$img.'">',$output);
            $output = str_replace('IMAGE_URL++;',$img,$output);
        }
        else
        {
            $output = str_replace("IMAGE++;",'',$output);
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
      public function Recomended_page()
      {
         require 'requires.php';
         $output = $this->Body();
         $output = str_replace("HEADER++;", $this->Header(), $output);
         $output = str_replace("BRAND_NAME++;", $settings->BrandName_get(), $output);
         $output = str_replace("NAVBAR++;", $this->Navbar(), $output); 
         $output = str_replace("USERNAME++;", $_SESSION['USERNAME'], $output); 
         $output = str_replace("STORE_NAME++;", 'xda', $output);
         $output = str_replace("SIDEBAR++;", $this->Sidebar(), $output);
         $output = str_replace("CONTENT++;", $this->Recomended_place(), $output);
         $output = str_replace("USERNAME++;", $_SESSION['USERNAME'], $output);
         $output = str_replace("EMAIL++;", $_SESSION['EMAIL'], $output);
         $output = str_replace("JS++;", $this->Footer(), $output);
         echo $output; 
      }
      public function Recomended_item_gen($stack)
      {
          require 'requires.php';
          $output = $this->Recomended_item();
          if($stack['SHOW_ITEM'] === 1)
          {
            $output = str_replace("SELECTED++;",'true',$output);
            $output = str_replace("none;",'block;',$output);
          }
          else
          {
            $output = str_replace('SELECTED++;','false',$output);
            #$output = str_replace("NONE++;",'none;',$output);
          }
          $output = str_replace("ID++;",$stack['ID'],$output);
          $output = str_replace("IMG++;",$stack['IMG'],$output);
          $output = str_replace("LINK++;",$stack['ALLEGRO'],$output);
          $output = str_replace("TITLE++;",substr($stack['TITLE'],0,18),$output);
          $output = str_replace("PRICE++;",$stack['PRICE'],$output);
          $output = str_replace("CURRENCY++;",$stack['CURRENCY'],$output);
          return $output;
      }
      public function Recomended_activation_item_gen($stack)
      {
          require 'requires.php';
          $output =  $this->Recomended_activation_item();
          $output = str_replace("TITLE++;",substr($stack['TITLE'],0,15).'...',$output);
          $output = str_replace("IMG++;",$stack['IMG'],$output);
          $output = str_replace("LINK++;",$stack['ALLEGRO'],$output);
          $output = str_replace("PRICE++;",$stack['PRICE'],$output);
          $output = str_replace("CURRENCY++;",$stack['CURRENCY'],$output);
          return $output;
      }
      
      public function Settings_page()
     {
        require 'requires.php';
        $output = $this->Body();
        $output = str_replace("HEADER++;", $this->Header(), $output);
        $output = str_replace("BRAND_NAME++;", $settings->BrandName_get(), $output);
        $output = str_replace("NAVBAR++;", $this->Navbar(), $output); 
        $output = str_replace("USERNAME++;", $_SESSION['USERNAME'], $output); 
        $output = str_replace("SIDEBAR++;", $this->Sidebar(), $output);
        $output = str_replace("CONTENT++;", $this->Settings(), $output);
        $output = str_replace("USERNAME++;", $_SESSION['USERNAME'], $output);
        $output = str_replace("EMAIL++;", $_SESSION['EMAIL'], $output);
        $output = str_replace("JS++;", $this->Footer(), $output);
        echo $output; 
     }

     public function Users_page()
     {
        require 'requires.php';
        $output = $this->Body();
        $output = str_replace("HEADER++;", $this->Header(), $output);
        $output = str_replace("BRAND_NAME++;", $settings->BrandName_get(), $output);
        $output = str_replace("NAVBAR++;", $this->Navbar(), $output); 
        $output = str_replace("USERNAME++;", $_SESSION['USERNAME'], $output); 
        $output = str_replace("SIDEBAR++;", $this->Sidebar(), $output);
        $output = str_replace("CONTENT++;", $this->UsersPlace(), $output);
        $output = str_replace("STORE_NAME++;" , $store->NameById($_SESSION['STORE_ID']), $output);
        $output = str_replace("USERS;" , "Użytkownicy", $output);
        $output = str_replace("JS++;", $this->Footer(), $output);
        echo $output;
     }
     public function User_gen($stack)
     {
         $output = $this->User();
         $output = str_replace("IMG++;", $stack['AVATAR'], $output);
         $output = str_replace("USERNAME++;", $stack['USERNAME'], $output);
         $output = str_replace("EMAIL++;", $stack['EMAIL'], $output);
         $output = str_replace("PASSWORD++;", $stack['PASSWORD'], $output);
         $output = str_replace("USERID++;", $stack['USERID'], $output);
         return $output;
     }
     public function My_gamesBoard()
     {
         require 'requires.php';
        $output = $this->MyGamesPlace();
        $output = str_replace("BRAND_NAME++;", $settings->BrandName_get(), $output);
        $output = str_replace("USERNAME++;", $_SESSION['USERNAME'], $output);

        echo $output;
     }
     public function Game_gen($stack)
     {
         $output = $this->MyGame();
         $output = str_replace("TITLE++;", $stack['TITLE'], $output);
         $output = str_replace("IMG++;", $stack['IMAGE'], $output);
         $output = str_replace("HASH++;", $stack['PRODUCT_HASH'], $output);
         return $output;
     }
     public function Assign_user_gen($stack)
     {
        $output = $this->SelectUser();
        $output = str_replace("USERNAME++;", $stack['USERNAME'], $output);
        return $output;
     }
     public function Assign_game_gen($stack)
     {
        $output = $this->SelectGame();
        $output = str_replace("GAME++;", $stack["TITLE"], $output);
        return $output;
     }
     public function User_game_delete_gen($stack)
     {
        $output = $this->User_game_delete();
        $output = str_replace("GAME++;", $stack["TITLE"], $output);
        $output = str_replace("GAME_HASH++;", $stack["PRODUCT_HASH"], $output);

        return $output;
     }
     public function UserFound_gen($stack)
     {
        $output = $this->UserFound();
        $output = str_replace("USERNAME++;", $stack['USERNAME'], $output);
        $output = str_replace("EMAIL++;", $stack['EMAIL'], $output);
        $output = str_replace("PASSWORD++;", $stack['PASSWORD'], $output);
        $output = str_replace("USERID++;", $stack['USERID'], $output);
        return $output;
     }
 }

?>