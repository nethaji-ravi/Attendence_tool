<?php
 include("./navbar.php");
?>
<!DOCTYPE html>
<html>
<head>
    <title>Worker Attendance</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-iYQeCzEYFbKjA/T2uDLTpkwGzCiq6soy8tYaI1GyVh/UjpbCx/TYkiZhlZB6+fzT" crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.24/css/jquery.dataTables.min.css">
    <script src="https://cdn.datatables.net/1.10.24/js/jquery.dataTables.min.js"></script>

    <style>
        /* @import url('https://fonts.googleapis.com/css2?family=Montserrat:wght@200&display=swap');
        body{ font-family: 'Montserrat', sans-serif } */
    </style>
    <link rel="stylesheet" href="style.css" type="text/css">
</head>
<body>
    <br><br><br>
    <div class="form-container w-25">
        <form name="date" action="" method="POST">
            <input type="date" name="min_date" value="" id="filterdate" pattern="[0-9]{4}-[0-9]{2}-[0-9]{2}" title="Enter date in yyyy-mm-dd format" required/>
            <button type="submit" class="pl-5" id="datesubmit" name="button">Submit</button>
        </form>
    </div>
    <br>
    <table id="attendanceTable" class="display">
        <thead>
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
                <th>Comment box</th>
            </tr>
        </thead>
        <tbody>
            <!-- Table rows will be dynamically added here -->
        </tbody>
    </table>

    <script>
        $(document).ready(function() {
            var table = $('#attendanceTable').DataTable({
                "pageLength": 20,
                columnDefs: [
                    { width: '10%', targets: 0 },
                    { width: '20%', targets: 1 },
                    ]
        });
            function updateTableRows(response) {
                // $('tbody').empty();
                table.clear().draw();

                response.forEach(function(row) {
                    var employeeId = row.emp_id;
                    var name = row.emp_name;
                    var punchingDate = row.punching_date;
                    var expectedIn = row.expected_in;
                    var actualIn = row.actual_in;
                    var expectedOut = row.expected_out;
                    var actualOut = row.actual_out;
                    var consider = row.consider;
                    var reasons = row.reasons;
                    var comment= row.commentbox;


                    var newRow = $('<tr>');

                    newRow.append('<td>' + employeeId + '</td>');
                    newRow.append('<td>' + name + '</td>');
                    newRow.append('<td>' + punchingDate + '</td>');
                    newRow.append('<td>' + expectedIn + '</td>');
                    newRow.append('<td>' + actualIn + '</td>');
                    newRow.append('<td>' + expectedOut + '</td>');
                    newRow.append('<td>' + actualOut + '</td>');

                    var considerSelect = $('<select>');
                    considerSelect.attr('name', 'consider');
                    considerSelect.attr('data-id', row.id); 

                    if (consider == 'NO' || consider =='no') {
                        considerSelect.append('<option value="No"' + (consider === 'No' ? ' selected' : '') + '>No</option>');
                        considerSelect.append('<option value="Yes"' + (consider === 'Yes' ? ' selected' : '') + '>Yes</option>');
                    } else {
                        considerSelect.append('<option value="Yes"' + (consider === 'Yes' ? ' selected' : '') + '>Yes</option>');
                        considerSelect.append('<option value="No"' + (consider === 'no' ? ' selected' : '') + '>No</option>');
                    }
                    
                    // considerSelect.append('<option value="Yes"' + (consider === '' ? ' selected' : '') + '>'+consider+'</option>');


                    var reasonsSelect = $('<select>');
                    reasonsSelect.attr('name', 'reasons');
                    reasonsSelect.on('change', function() {
                        updateReasons(this.value, row.id);
                    });
                    reasonsSelect.append('<option value=""' + (reasons === '' ? ' selected' : '') + '>'+reasons+'</option>');
                    reasonsSelect.append('<option value=""' + (reasons === '' ? ' selected' : '') + '>Select</option>');

                    if (reasons !== 'leave') {
                        reasonsSelect.append('<option value="Leave"' + (reasons === 'Leave' ? ' selected' : '') + '>Leave</option>');
                    }

                    if (reasons !== 'not_punched_in') {
                        reasonsSelect.append('<option value="not_punched_in"' + (reasons === 'not_punched_in' ? ' selected' : '') + '>Not Punch In</option>');
                    }

                    if (reasons !== 'permission') {
                        reasonsSelect.append('<option value="Permission"' + (reasons === 'Permission' ? ' selected' : '') + '>Permission</option>');
                    }
                    var commentBoxInput = $('<input>');
                    commentBoxInput.attr('type', 'text');
                    commentBoxInput.attr('name', 'commentbox');
                    commentBoxInput.attr('data-id', row.id);
                    commentBoxInput.val(row.commentbox);

    // if (consider == 'no') {
    //   considerSelect.val('yes'); // Set the selected value to 'Yes'
    // } else {
    //   considerSelect.val('no'); // Set the selected value to 'No'
    // }

                    newRow.append($('<td>').append(considerSelect));
                    newRow.append($('<td>').append(reasonsSelect));
                    newRow.append($('<td>').append(commentBoxInput));

                    // $('tbody').append(newRow);
                    table.row.add(newRow).draw();
                });
            }

            function updateConsider(value, id) {
                console.log(value, id);
                $.ajax({
                    type: 'POST',
                    dataType: 'json',
                    url: 'http://localhost/attendence/gateway/action?application=crud&action=consider',
                    data: {
                        id: id,
                        value: value
                    },
                    success: function(response) {
                        
                    }
                });
            }

            function updateReasons(value, id) {
                console.log(value, id);
                $.ajax({
                    type: 'POST',
                    dataType: 'json',
                    url: 'http://localhost/attendence/gateway/action?application=crud&action=reason',
                    data: {
                        id: id,
                        value: value
                    },
                    success: function(response) {
                        
                    }
                });
            }

            function updateCommentBox(value, id) {
                console.log(value, id);
                $.ajax({
                    type: 'POST',
                    dataType: 'json',
                    url: 'http://localhost/attendence/gateway/action?application=crud&action=commentbox',
                    data: {
                    id: id,
                    value: value
                    },
                    success: function(response) {
                    // Handle success response if needed
                    }
                });
                }

            
            $.ajax({
                type: 'GET',
                dataType: 'json',
                url: 'http://localhost/attendence/gateway/action?application=crud&action=viewreport',
                success: function(response) {
                    console.log(response);
                    updateTableRows(response);
                }
            });

            
            $(document).on('change', 'select[name="consider"]', function() {
                var value = $(this).val();
                var id = $(this).data('id');
                updateConsider(value, id);
            });

            
            $(document).on('change', 'input[name="commentbox"]', function() {
            var value = $(this).val();
            var id = $(this).data('id');
            updateCommentBox(value, id);
            });

            $(document).on('click', '#datesubmit', function(e) {
                e.preventDefault();
                var previewfilter = $('#filterdate').val();

                var form = {
                    filterdate: previewfilter
                };

                $.ajax({
                    type: 'POST',
                    dataType: 'json',
                    url: 'http://localhost/attendence/gateway/action?application=crud&action=viewfilter',
                    data: form,
                    success: function(data) {
                        console.log(data);
                        updateTableRows(data);
                    }
                });
            });
        });

            
            // $(document).ready(function() {
            //     var table;
            //     table = $('#attendanceTable').DataTable({
                    
            //         "pageLength": 20,
                    // columnDefs: [
                    // { width: '10%', targets: 0 },
                    // { width: '20%', targets: 1 },
                    // ]
            //     });
            //     });
                      

    </script>
</body>
</html>

