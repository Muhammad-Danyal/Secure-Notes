<?php

$showAlert = false;
$showError = false;

if($_SERVER["REQUEST_METHOD"] == "POST" ){
include('./partials/_dbconnect.php');

$uname = $_POST["uname"];
$password = $_POST["password"];
$cpassword = $_POST["cpassword"];

$sqlExist = "SELECT * from `user` WHERE `username`= '$uname'";
$result = mysqli_query($conn, $sqlExist);
$numrows= mysqli_num_rows($result);
if($numrows>0){
    $showError= " Username already Exists.";
}

else{
if(($password === $cpassword) && ($uname !="")){
    $sql= "INSERT INTO `user` (`username`, `pass`, `date`) VALUES ('$uname', '$password', current_timestamp())";
    $result = mysqli_query($conn, $sql);
    if($result){ 
        $showAlert= true;
    }   
}
else{
    $showError= " Invalid Username or Passwords did not matched.";
}
}
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Signup</title>
</head>
<body>

<?php
require './partials/_nav.php';

if($showAlert){
   echo '<div class="alert alert-success alert-dismissible fade show" role="alert">
  <strong>Success!</strong> Your account has been created Successfully.
  <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>';
}
if($showError){
    echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">
  <strong>Fail!</strong> '.$showError.'
  <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>';
}

?>
<div class="container">
    <h1 class="text-center">Signup to our Website</h1>
 <form action="/loginmodule/signup.php" method="POST">
  
  <!-- <div class="mb-3">
  <label for="name" class="form-label">Full name</label>
  <input type="text" class="form-control" id="name" name="name" aria-describedby="emailHelp">
  </div> -->
  <div class="mb-3 my-4">
    <label for="uname" class="form-label">Username</label>
    <input type="text" class="form-control" id="uname" name="uname" aria-describedby="emailHelp">
    <!-- <div id="emailHelp" class="form-text">We'll never share your email with anyone else.</div> -->
  </div>
  <div class="mb-3">
    <label for="password" class="form-label">Password</label>
    <input type="password" class="form-control" id="password" name="password">
  </div>
  <div class="mb-3">
    <label for="cpassword" class="form-label">Confirm Password</label>
    <input type="password" class="form-control" id="cpassword" name="cpassword">
  </div>
  <!-- <div class="mb-3 form-check">
    <input type="checkbox" class="form-check-input" id="exampleCheck1">
    <label class="form-check-label" for="exampleCheck1">Remember me</label>
  </div> -->
  <button type="submit" class="btn btn-primary">Signup</button>
 </form>
</div>


</body>
</html>