 for($x=0;$x<$totalStus;$x++) {
     $cboxE='';
     $cboxV='';
     $cboxC='';
     
     if($all[$x]['flag_edit']==1) $cboxE='checked';
     if($all[$x]['flag_view']==1) $cboxV='checked';
     if($all[$x]['flag_create']==1) $cboxC='checked';
     
      ?><tr><th><?php    
     if($all[$x]['exists']=='no'){
      echo ("<tr><td>".$all[$x]['id_student'].' ');
      echo ("</td><td>".$all[$x]['name_first'].' '.$all[$x]['name_last']); ?></th>
      <th>  <input  type ="hidden" name="<?php echo($trackTotal."_id_student")?>" value="<?php echo$all[$x]['id_student']?>"></th>
        <th>  <input type="checkbox" name="<?php echo("E_".$all[$x]['id_student'])?>" value="1"<?php echo($cboxE)?>>edit</th>
        <th>  <input type="checkbox" name="<?php echo("V_".$all[$x]['id_student'])?>" value="1"<?php echo($cboxV)?>>view</th>
        <th>  <input type="checkbox" name="<?php echo("C_".$all[$x]['id_student'])?>" value="1"<?php echo($cboxC)?>>create</th>
        <th>  <input type ="hidden" name="<?php echo("X_".$all[$x]['id_student'])?>" value="<?php echo$all[$x]['exists']?>"></th></tr>
<?php 
     $trackTotal=$trackTotal+1; //writevar($trackTotal,'this is the no loop and the total of tracktotal');
    
     }
     

 ?>