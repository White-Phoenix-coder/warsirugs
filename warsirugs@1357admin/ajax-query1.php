<?php  
include 'connection.php';
if(isset($_POST["Id"]))  
{  
     $output = '';  

     $query = "SELECT * FROM career WHERE id = '".$_POST["Id"]."'";  
     $result = $conn->prepare($query); 
     $result->execute();
     $output .= '  
     <div class="table-responsive">  
          <table class="table table-bordered">';              //include
     while($row = $result->fetch(PDO::FETCH_ASSOC))  
     {  
          $output .= '  
               <tr>  
                    <td width="30%"><label>Name</label></td>  
                    <td width="70%">'.$row["Name"].'</td>  
               </tr>  
               <tr>  
                    <td width="30%"><label>Email</label></td>  
                    <td width="70%">'.$row["email"].'</td>  
               </tr>
               <tr>  
                    <td width="30%"><label>Phone</label></td>  
                    <td width="70%">'.$row["number"].'</td>  
               </tr>

               <tr>  
               <td width="30%"><label>Subject</label></td>  
               <td width="70%">'.$row["subject"].'</td>  
          </tr>

               <tr>  
               <td width="30%"><label>Message</label></td>  
               <td width="70%">'.$row["message"].'</td>  
              </tr>

           <tr>  
               <td width="30%"><label>Created_at</label></td>  
               <td width="70%">'.$row["date"].'</td>  
          </tr>';   
     }  
     $output .= "</table></div>";  
     echo $output;  
}