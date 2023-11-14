<?php

include '../database/connection.php';


    $resultrg =mysqli_query($con, "SELECT * FROM city where regDesc ='".$_POST['city_code']."' order by citymunDesc asc");
          while($rowrg=mysqli_fetch_array($resultrg))
         {

         $array[] = array("city_name" => $rowrg['2']);
         //echo '<option  value="'.$rowrg[2].'">'.$rowrg[2].' </option> ';
         
         }
         
         echo json_encode($array);

?>