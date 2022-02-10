<?php  
// require_once("connection.php");
include("./database/conn.php");
 $output = '';  
 if(isset($_POST["ListPrice"]) && isset($_POST["SetType"]))  
 {  
        $ListPrice = substr($_POST["ListPrice"], 0,7);
        $SetType = substr($_POST["SetType"], 7,8);
        // $sql = "SELECT * FROM listmenu WHERE ResName = 'Loft' AND ListPrice = '".$ListPrice."' AND SetType = '".$SetType."' ";
        // $result = mysqli_query($conn, $sql);  
        // while($row = mysqli_fetch_array($result))
        $sql = $conn->prepare("SELECT * FROM listmenu WHERE ResName = 'Loft' AND ListPrice = '".$ListPrice."' AND SetType = '".$SetType."' ");
        $sql->execute();
        $rssql = $sql->fetchAll();
        foreach ($rssql as $keyrssql => $valuerssql) 
        {  
            $output .= '<input type="text" id="MenuName'.$valuerssql["ListTopic"].'" name="MenuName'.$valuerssql["ListTopic"].'" value="'.$valuerssql["MenuName"].'" class="form-control border-input" ReadOnly style="background-color: #fffcf5;">'; 
            $output .= '<input type="hidden" name="BKStatus" id="BKStatus" value="4">'; 
        }  
     
      echo $output. '</br>'; 
      
 }  
 ?>  