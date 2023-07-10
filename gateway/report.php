<?php
include("./navbar.php");
?>
<br><br><br>
<!DOCTYPE html>
<html>
<head>
  <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.25/css/jquery.dataTables.min.css">
  <link rel="stylesheet" href="https://cdn.datatables.net/1.10.24/css/jquery.dataTables.min.css">
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://cdn.datatables.net/1.10.24/js/jquery.dataTables.min.js"></script>
  <link rel="stylesheet" href="style.css" type="text/css">
  
<!-- SweetAlert2 CSS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.1.3/dist/sweetalert2.min.css">

<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- SweetAlert2 JS -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.1.3/dist/sweetalert2.all.min.js"></script>

</head>
<body>

  <button class="export-button btn bg-primary text-light mb-2 ms-3 ml-2">Export as CSV</button>
  <button class="button btn bg-danger text-light mb-2 ms-3 ml-2" id="pdf">Export as PDF</button>
  <button class="button btn bg-danger text-light mb-2 ms-3 ml-2" id="email">Send Email</button>
    <div class="form-container">
        <form name="date" action="" method="POST">
            <input type="date" name="min_date" value="" id="mindate" pattern="[0-9]{4}-[0-9]{2}-[0-9]{2}" title="Enter date in yyyy-mm-dd format" required/>
            <input type="date" name="max_date" value="" id="maxdate" pattern="[0-9]{4}-[0-9]{2}-[0-9]{2}" title="Enter date in yyyy-mm-dd format" required/>
            <button type="submit" id="datesubmit" name="button">Submit</button>
        </form>
    </div>

  <div class="table-container"> 
    <div class="table-responsive">
      <table class="table" id="employeeTable">
        <thead class="thead-dark">
          <tr>
            <th>Employee ID</th>
            <th>Name</th>
            <th>Punching Date</th>
            <th>Expected In</th>
            <th>Actual In</th>
            <th>Expected Out</th>
            <th>Actual Out</th>
            <th>Consider</th>
            <th>Reasons</th>
          </tr>
        </thead>
        <tbody>
        </tbody>
      </table>
    </div>
  </div>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.9.2/html2pdf.bundle.js"></script>
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://cdn.datatables.net/1.10.25/js/jquery.dataTables.min.js"></script>
  <script>
  $(document).ready(function() {
  var table;

  function initializeDataTable() {
    table = $('#employeeTable').DataTable({
    //   "lengthMenu": [20, 50, 100],
      "pageLength": 20,
      columnDefs: [
        { width: '10%', targets: 0 }, // Reduce width of Employee ID column
        { width: '20%', targets: 1 }, // Increase width of Name column
      ]
    });
  }

  function loadData() {
    $.ajax({
      type: 'GET',
      dataType: 'json',
      url: 'http://localhost/attendence/gateway/action?application=crud&action=reportlist',
      success: function(response) {
        if ($.fn.DataTable.isDataTable('#employeeTable')) {
          table.clear().destroy();
        }

        initializeDataTable();

        table.clear().draw();

        $.each(response, function(index, row) {
          table.row.add([
            row.emp_id,
            row.emp_name,
            row.punching_date,
            row.expected_in,
            row.actual_in,
            row.expected_out,
            row.actual_out,
            row.consider,
            row.reasons
          ]).draw();
        });
      },
      error: function(error) {
        console.log(error);
      }
    });
  }

  $(document).on('click', '#datesubmit', function(e) {
    e.preventDefault(); // Prevent form submission

    var mindate = $('#mindate').val();
    var maxdate = $('#maxdate').val();
    var form = {
      minidate: mindate,
      maxidate: maxdate
    };

    $.ajax({
      type: 'POST',
      dataType: 'json',
      url: 'http://localhost/attendence/gateway/action?application=crud&action=reportdate',
      data: form,
      success: function(data) {
        if ($.fn.DataTable.isDataTable('#employeeTable')) {
          table.clear().destroy();
        }

        initializeDataTable();

        table.clear().draw();

        $.each(data, function(index, row) {
          table.row.add([
            row.emp_id,
            row.emp_name,
            row.punching_date,
            row.expected_in,
            row.actual_in,
            row.expected_out,
            row.actual_out,
            row.consider,
            row.reasons
          ]).draw();
        });
      },
      error: function(error) {
        console.log(error);
      }
    });
  });


  loadData();

  $('.export-button').on('click', function() {
    var data = table.rows().data();
    var csvContent = "data:text/csv;charset=utf-8,";

    var headers = [];
    $('.table thead th').each(function() {
      headers.push($(this).text());
    });
    csvContent += headers.join(",") + "\r\n";

    data.each(function(row) {
      var rowData = Object.values(row).join(",");
      csvContent += rowData + "\r\n";
    });

    var currentDate = new Date();
    var dateString =
      "report_" +
      currentDate.getFullYear() +
      "-" +
      (currentDate.getMonth() + 1) +
      "-" +
      currentDate.getDate() +
      ".csv";

    var link = document.createElement("a");
    link.setAttribute("href", encodeURI(csvContent));
    link.setAttribute("download", dateString);
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
  });


  $('#pdf').on('click', function() {
    var element = $('.table')[0];

    var currentDate = new Date();
    var dateString =
      "report_" +
      currentDate.getFullYear() +
      "-" +
      (currentDate.getMonth() + 1) +
      "-" +
      currentDate.getDate() +
      ".pdf";

    html2pdf()
      .set({
        margin: 0,
        filename: dateString,
        image: { type: 'jpeg', quality: 0.98 },
        html2canvas: { scale: 2,width:1500, logging: true },
        jsPDF: { unit: 'mm', format: 'a4', orientation: 'portrait' }
      })
      .from(element)
      .save();
  });
});


$(document).ready(function() {
    $('#email').on('click', function() {
      var tableContent = $('.table').html();
      $.post('report.php', { tableContent: tableContent }, function(response) {
        console.log(response);
        if (response) {
          Swal.fire('Success!', 'Email sent successfully.', 'success');
        } else {
          Swal.fire('Error!', 'Error sending Email.', 'error');
        }
      });
    });
  });

</script>
<?php

require 'C:/xampp/htdocs/attendence/phpmailer/src/PHPMailer.php';
require 'C:/xampp/htdocs/attendence/phpmailer/src/SMTP.php';
require 'C:/xampp/htdocs/attendence/phpmailer/src/Exception.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['tableContent'])) {
    $tableContent = $_POST['tableContent'];
    
    $senderEmail = 'alerts@symbioticinfo.com';
    $recipientEmail = 'mahesh1832001@gmail.com';
    $subject = 'Table Content Report';
    
    
    $message = '<html><body>';
    $message .= '<h2>Table Content:</h2>';
    $message .= '<table border=2>' . $tableContent . '</table>';
    $message .= '</body></html>';
    
    
    $mailer = new PHPMailer(true); 
    $mailer->isSMTP();
    $mailer->Host = 'mail.symbioticinfo.com'; 
    $mailer->Port = 465; 
    $mailer->SMTPSecure = 'ssl';
    $mailer->SMTPAuth = true;
    $mailer->Username = 'alerts@symbioticinfo.com'; 
    $mailer->Password = 'G$d26B@e$4fXz@M9$A';
    
    $mailer->setFrom($senderEmail, 'Alert', 0);
    $mailer->addAddress($recipientEmail);
    
    $mailer->Subject = $subject;
    $mailer->Body = $message;
    $mailer->isHTML(true); 
    
    try {
      $mailer->send();
      $response = 'Email sent successfully!';
      echo "<script>alert('$response');</script>";
    } catch (Exception $e) {
      $response = 'Failed to send email. Error: ' . $mailer->ErrorInfo;
      echo "<script>alert('$response');</script>";
    }
   
    
    
}
?>



</body>
</html>

</body>
</html>