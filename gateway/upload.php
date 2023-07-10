<?php
include("./navbar.php");
?>
<br>
<br>
<html>

<head>
<style>
        #warn{
            font-size: 15px;
            color: red;
        }
    </style>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-iYQeCzEYFbKjA/T2uDLTpkwGzCiq6soy8tYaI1GyVh/UjpbCx/TYkiZhlZB6+fzT" crossorigin="anonymous" />
</head>
<body>

    <div class="container my-4">
      <div class="row">
        <div class="col-md-12 text-center mx-auto rounded">
          <h1 class="text-center bg-dark text-light text-uppercase p-2" class="sticky">Attendance Update</h1>
        </div>

        <div class="col-md-12 col-lg-8 offset-lg-2 text-center mx-auto">
          <div class="card">
            <div class="card-body">
                <form action="http://localhost/attendence/gateway/action?application=crud&action=csv" method="post" enctype="multipart/form-data">
                  <p class="mb-3 mt-5 form-label h6">Please select a file to upload:</p>
                  <p id="warn">Only CSV files are accepted!</p>
                  <input name="fileSelect" type="file" accept=".csv, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel"  class="mb-5 form-control w-100 mx-auto" />
                <button  class="btn btn-danger" name='uploadcsv' type="submit">Submit</button>
                </form>
            </div>
          </div>
        </div>
      </div>
    </div>
 
</body>
</html>







