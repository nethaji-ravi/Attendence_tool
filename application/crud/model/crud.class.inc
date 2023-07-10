<?php

class CrudmModel
{
   

    public function login_submit($name,$password)
    {
        global $db;
        $result=$db['master']->getOneRow("SELECT user_id FROM `user_detail` WHERE user_name ='$name' AND password='$password'");
        return  $result;
    }
    public function report()
    {
         global $db;
         $result = $db['master']->getResults("SELECT * FROM employee_punching_detail WHERE punching_date >= DATE_SUB(NOW(), INTERVAL 1 WEEK)");
         return $result;
    }
    public function report_filter($mindate,$maxdate)
    {
        global $db;
        $result = $db['master']->getResults("SELECT * FROM employee_punching_detail WHERE punching_date >= '$mindate' AND punching_date <= '$maxdate' ORDER BY punching_date DESC");
        return $result;
   }
   public function view_report()
   {
        global $db;
        $results = $db['master']->getResults("SELECT * FROM employee_punching_detail WHERE punching_date >= DATE_SUB(NOW(), INTERVAL 1 WEEK)" );
        return $results;
   }
   public function consider($id,$change)
   {
        global $db;
        $results = $db['master']->query("UPDATE employee_punching_detail set consider = '$change' where id= '$id'" );
        return $results;
   }
   public function reason($id,$change)
   {
        global $db;
        $results = $db['master']->query("UPDATE employee_punching_detail set reasons = '$change' where id= '$id'" );
        return $results;
   }
   public function commentbox($id,$change)
   {
        global $db;
        $results = $db['master']->query("UPDATE employee_punching_detail set commentbox = '$change' where id= '$id'" );
        return $results;
   }
   public function view_filter($date)
   {
     global $db;
     $results = $db['master']->getResults("SELECT * FROM employee_punching_detail WHERE punching_date = '$date' ");
     return $results;
   }
   public function dateche($punchingDate,$employeeId)
   {
        global $db;
        $result = $db['master']->getResults("SELECT * FROM `employee_punching_detail` WHERE  punching_date = '$punchingDate' AND emp_id='$employeeId'");
        return $result;
   }
   public function datainsert($emp_id,$emp_name,$punching_date,$expected_in,$actual_in,$expected_out,$actual_out)
    {
         global $db;
         $result = $db['master']->query("INSERT INTO employee_punching_detail (emp_id, emp_name,punching_date,expected_in,actual_in,expected_out,actual_out,consider) VALUES ('$emp_id', '$emp_name','$punching_date', '$expected_in','$actual_in', '$expected_out','$actual_out','yes')");
         return $result;
    }
    public function updat($emp_id1,$emp_name1,$punching_date1,$expected_in1,$actual_in1,$expected_out1,$actual_out1)
    {
         global $db;
         $result = $db['master']->query("UPDATE `employee_punching_detail` SET `emp_id`='$emp_id1',`emp_name`='$emp_name1',`punching_date`='$punching_date1',`expected_in`='$expected_in1',`actual_in`='$actual_in1',`expected_out`='$expected_out1',`actual_out`='$actual_out1',`consider`='yes' WHERE emp_id='$emp_id1' AND punching_date= '$punching_date1'");
         return $result;
    }


    
}
