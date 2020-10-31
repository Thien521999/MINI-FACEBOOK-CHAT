
//update_last_activity.php
<?php
   include('database_connection.php');
   session_start();
   $query = "
               UPDATE user_details 
               SET last_activity = now() 
               WHERE login_details_id = '".$_SESSION["login_details_id"]."'
            ";
   $statement = $connect->prepare($query);
   $statement->execute();
?>

