
<html>
<head>
<link rel="stylesheet" type="" href="./resources/css/bootstrap.min.css">
<style>
  pre{
    font-size: large;
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
  }
</style>
</head>
<body>
    
  
<div class="container d-flex justify-content-center mt-5 ">
            <h2 class="mt-5">Log In</h2>
    </div>
<div class="container d-flex justify-content-center mt-5 ">

<div class="card w-50 mt-5 ">
  
  <div class="card-body">
<form method='post' action='' >
    
    <div class="form-group">
        <label for="exampleDropdownFormEmail2">USER NAME</label>
        <input type='text' name="unam" required class="form-control" id="uname" placeholder="Enter User Name">
    </div>
    <div class="form-group">
        <label for="exampleDropdownFormPassword2">PASSWORD</label>
        <input type='password' name='pass' required  class="form-control" id="pass" placeholder="Enter Password">
        <input type="hidden" name="useid" id="useid" value="">
    </div>
    <div class="d-flex justify-content-center">
    <button  class="btn btn-primary w-25 " name="login" id="submit" type='button'>LOG IN</button>
    </div>

       
</form>
  </div>
</div>
</div>
  
    
</body>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>

<script>
  $(document).on('click', '#submit', function(e) {
    console.log(1);
    var name = $('#uname').val();
    var password = $('#pass').val();
    form = {
      'name': name,
      'password': password
    };
    console.log(form);
    $.ajax({
      type: 'POST',
      dataType: 'json',
      url: 'http://localhost/attendence/gateway/action?application=crud&action=loginsubmit',
      data: form,
      success: function(data) {
        console.log(data);
        if (data) {
     
          window.location.replace('http://localhost/attendence/gateway/upload.php');
        } else {
          Swal.fire("Invalid Credentials", "Your credentials are invalid.", "error");
          console.log("error input vro");
        }
      }
    });
  });
</script>

</html>

