<?php
$Login = false;
$showError = false;

if($_SERVER["REQUEST_METHOD"] == "POST" ){
include('./partials/_dbconnect.php');

$uname = $_POST["uname"];
$password = $_POST["password"];

$sql= "SELECT * FROM `user` WHERE `username` ='$uname' AND `pass`='$password'";
 $result = mysqli_query($conn, $sql);
$num= mysqli_num_rows($result);
if($num==1){ 
    $Login= true;
    session_start();
    $_SESSION["loggedin"]= true;
     $_SESSION["username"]=$uname;
    header("location: welcome.php");
}   
else{
    $showError= true;
}
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
</head>
<body>

<?php
require './partials/_nav.php';

if($Login){
    echo '<div class="alert alert-success alert-dismissible fade show" role="alert">
   <strong>Success!</strong> You have been LoggedIn.
   <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
 </div>';
 }
 if($showError){
     echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">
   <strong>Fail!</strong> Invalid Credentials!
   <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
 </div>';
 }
?>

<div class="container">
 <form action="/loginmodule/login.php" method="POST">
 <h1 class="text-center">Login to our Website</h1>
  <div class="mb-3 my-4">
  <label for="uname" class="form-label">Username</label>
  <input type="text" class="form-control" id="uname" name="uname" aria-describedby="emailHelp">
    <!-- <div id="emailHelp" class="form-text">We'll never share your email with anyone else.</div> -->
  </div>
  <div class="mb-3">
    <label for="password" class="form-label">Password</label>
    <input type="password" class="form-control" id="password" name="password">
  </div>
  <div class="mb-3 form-check">
    <input type="checkbox" class="form-check-input" id="exampleCheck1">
    <label class="form-check-label" for="exampleCheck1">Remember me</label>
  </div>
  <button type="submit" class="btn btn-primary">Login</button>
 </form>
</div>

</body>
</html>