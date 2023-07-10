
<?php


class CrudController
{


    public function router()
    {

        global $request, $tsResponse, $library;

        $crudModel = new CrudmModel();

        $action = strtolower(trim($request['action']));

        switch ($action) {
            case "index":
                include(ROOT_DIR . 'gateway/home.php');
                break;
            case "loginsubmit":
                $user=$request['name'];
                $password=$request['password'];
                $result=$crudModel->login_submit($user,$password);
             
                echo json_encode($result);
                break;
            case "reportlist":
                $reportlist = $crudModel->report();
                echo json_encode($reportlist);
                break;
            case "reportdate":
                $mindate = $request['minidate'];
                $maxdate = $request['maxidate'];
                $filter = $crudModel->report_filter($mindate,$maxdate);
                echo json_encode($filter);
                break;
            case "viewreport":
                $viewreport = $crudModel->view_report();
                echo json_encode($viewreport);
                break;
            case "consider":
                $id = $request['id'];
                $change = $request['value'];
                $result = $crudModel->consider($id,$change);
                break;
            case "reason":
                $id = $request['id'];
                $change = $request['value'];
                $result = $crudModel->reason($id,$change);
                break;
            case "commentbox":
                $id = $request['id'];
                $change = $request['value'];
                $result = $crudModel->commentbox($id,$change);
                break;
            case "viewfilter":
                $date = $request['filterdate'];
                $result = $crudModel->view_filter($date);
                echo json_encode($result);
                break;
            case "csv":
              
                    if ($_SERVER["REQUEST_METHOD"] === "POST") {
                        if (isset($_FILES["fileSelect"]) && $_FILES["fileSelect"]["error"] === UPLOAD_ERR_OK) {
                            $file = $_FILES["fileSelect"]["tmp_name"];
                            $upload_dir = "../storage/";

                            $fileContent = file_get_contents($file);
                            $file_name = $_FILES["fileSelect"]["name"];
                            $file_tmp = $_FILES["fileSelect"]["tmp_name"];


                            $file_ext = pathinfo($file_name, PATHINFO_EXTENSION);
                            if ($file_ext === 'csv') {

                                $upload_path = $upload_dir . $file_name;
                                move_uploaded_file($file_tmp, $upload_path);
                                    $lines = explode(PHP_EOL, $fileContent);
                                    $headerSkipped = false;
                                    foreach ($lines as $line) {
                                        if (!$headerSkipped) {
                                            $headerSkipped = true;
                                            continue;
                                        }

                                        $values = str_getcsv($line);
                                        
                                        $employeeId = $values[0]; 
                                        $idsToSkip = ["MT0003","MT0011", "MT0027"];
                                        if (in_array($employeeId, $idsToSkip)) {
                                            continue;
                                        }
                                        $punchingDate=date("Y-m-d", strtotime($values[2]));


                                        $ret=$crudModel->dateche($punchingDate,$employeeId);
                                        if(empty($ret)){

                                            $emp_id  = $values[0];
                                            $emp_name = $values[1];
                                            $punching_date = date("Y-m-d", strtotime($values[2]));
                                            $expected_in = $values[3];
                                            $actual_in = $values[4];
                                            $expected_out = $values[5];
                                            $actual_out = $values[6];
                                            if(isset($emp_id)){
                                            $ins=$crudModel->datainsert($emp_id,$emp_name,$punching_date,$expected_in,$actual_in,$expected_out,$actual_out);
                                            }
                                        }elseif(!empty($ret)){

                                            $emp_id1 = $values[0];
                                            $emp_name1 = $values[1];
                                            $punching_date1 = date("Y-m-d", strtotime($values[2]));
                                            $expected_in1 = $values[3];
                                            $actual_in1 = $values[4];
                                            $expected_out1 = $values[5];
                                            $actual_out1 = $values[6];

                                            $updat=$crudModel->updat($emp_id1,$emp_name1,$punching_date1,$expected_in1,$actual_in1,$expected_out1,$actual_out1);
                                        }
                                    
                                    }
                                    echo"<script src='https://cdn.jsdelivr.net/npm/sweetalert2@10.15.5/dist/sweetalert2.all.min.js'></script>";
                                    echo"<link rel='stylesheet' href='https://cdn.jsdelivr.net/npm/sweetalert2@10.15.5/dist/sweetalert2.min.css'>";
                                    echo "<script>
                                                document.addEventListener('DOMContentLoaded', function() {
                                                    Swal.fire({
                                                        title: 'Success!',
                                                        text: 'ADDED SUCCESSFULLY',
                                                        icon: 'success'
                                                    }).then(function() {
                                                        window.location.href = 'http://localhost/php/attendence/gateway/upload.php';
                                                    });
                                                });
                                            </script>";
                            }else {
                                echo"<script src='https://cdn.jsdelivr.net/npm/sweetalert2@10.15.5/dist/sweetalert2.all.min.js'></script>";
                                echo"<link rel='stylesheet' href='https://cdn.jsdelivr.net/npm/sweetalert2@10.15.5/dist/sweetalert2.min.css'>";
                                echo "<script>
                                            document.addEventListener('DOMContentLoaded', function() {
                                                Swal.fire({
                                                    title: 'Error!',
                                                    text: 'Invalid file format. Please upload a CSV file.',
                                                    icon: 'error'
                                                }).then(function() {
                                                    window.location.href = 'http://localhost/php/attendence/gateway/upload.php';
                                                });
                                            });
                                        </script>";
                                    }           
                        }else {
                            echo"<script src='https://cdn.jsdelivr.net/npm/sweetalert2@10.15.5/dist/sweetalert2.all.min.js'></script>";
                            echo"<link rel='stylesheet' href='https://cdn.jsdelivr.net/npm/sweetalert2@10.15.5/dist/sweetalert2.min.css'>";
                            echo "<script>
                                        document.addEventListener('DOMContentLoaded', function() {
                                            Swal.fire({
                                                title: 'Error!',
                                                text: 'Please select a CSV file to upload.',
                                                icon: 'error'
                                            }).then(function() {
                                                window.location.href = 'http://localhost/php/attendence/gateway/upload.php';
                                            });
                                        });
                                    </script>";
                            }
                                        
                
                }
                break;
        }
    }
     
}
    
?>