<?php 


class DB
{
    public function STMT($conn,$query)
    {
        if($conn&&!empty($query))
        {
            $stmt = mysqli_stmt_init($conn);
            if(!$stmt->prepare($query))
            {
                 echo mysqli_stmt_error($stmt);
                 exit();
             }
             $stmt->prepare($query);
             return $stmt;
        }
    }
    function Count($stmt)
 	{
 		$stmt->execute();
        $stmt->store_result();
        return $stmt->num_rows();
 	}
 	function Store($stmt)
 	{
 		$stmt->execute();
        $stmt->store_result();
         if(!$stmt)
         {
             die('prepare () failed)' . htmlspecialchars($stmt->error()));
         }
         return $stmt->store_result();
    }
    function Exists($stmt)
    {
        $result = $this->Count($stmt);
        if($result > 0)
        {
            return TRUE;
        }
        else
        {
            return FALSE;
        }
        return FALSE;
    } 
 }

 


?>