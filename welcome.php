<?php
session_start();
if($_SESSION["loggedin"] != true){
    header("location: login.php");
    exit;
}
  $alertsuccess =false;
  $alertfail =false;
  $delete = false;  
  $editsuccess= false;
  $editfail=false;

if($_SERVER["REQUEST_METHOD"] == "POST"){
  include('./partials/_dbconnect.php');  

  //var_dump($_POST['snoedit']);
  if (isset($_POST['snoedit'])) {
    // Edit note
    $sno1 = $_POST["snoedit"];
    $titleEdit = $_POST["titleEdit"];
    $descEdit = $_POST["descriptionEdit"];

    $sql1 = "UPDATE `notes` SET `title` = '$titleEdit', `descripition` = '$descEdit' WHERE `notes`.`s.no` = '$sno1'";
    $result1 = mysqli_query($conn, $sql1);
    if ($result1) {
      $editsuccess =true;
    } else {
      $editfail =true;
    }
} else {
    // Add new note
        $title = $_POST["title"];
        $desc = $_POST["note"];

        if (!empty($title) || !empty($desc)) {
            $sql = "INSERT INTO `notes` (`username`, `title`, `descripition`) VALUES ('$_SESSION[username]', '$title', '$desc')";
            $result = mysqli_query($conn, $sql);
            if ($result) {
                $alertsuccess = true;
            } else {
                $alertfail = true;
            }
        } else {
            $alertfail = true;
        }
}

}

include('./partials/_dbconnect.php');

if(isset($_GET['delete'])){    
  $sno = $_GET['delete'];
  $delete = true;
  $sql = "DELETE FROM `notes` WHERE `s.no` = $sno ";
  $result= mysqli_query($conn, $sql);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="//cdn.datatables.net/2.0.8/css/dataTables.dataTables.min.css">     
    <title>Welcome - <?php echo $_SESSION["username"]; ?></title>
</head>
<body>

  <!-- Edit modal -->
<!-- <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#editModal">
  Edit
</button> -->

<!--Edit Modal -->

<div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModal" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="editModalLabel">Edit Modal</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
      <form action="/loginmodule/welcome.php" method="POST">
      <input type="hidden" name="snoedit" id="snoedit">
      <div class="mb-3 my-4">
        <label for="title" class="form-label">Title</label>
        <input type="text" class="form-control" id="titleEdit" name="titleEdit" aria-describedby="emailHelp">
       </div>
        <div class="mb-2">
      <label for="note" class="form-label">Description</label>
    <textarea  class="form-control" id="descriptionEdit" name="descriptionEdit"></textarea> </div>
    <button type="submit" class="btn btn-primary mb-4">Update Note</button>
    </form>
  </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <!-- <button type="button" class="btn btn-primary">Update note</button> -->
      </div>
    </div>
  </div>
</div>
<?php
require './partials/_nav.php';
if( $alertsuccess){
  echo('<div class="alert alert-success alert-dismissible fade show" role="alert">
    <strong>Success! </strong> Note has been added Successfully.
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
  </div>');
}

if($alertfail){
  echo('<div class="alert alert-danger alert-dismissible fade show" role="alert">
  <strong>Fail! </strong>  Note is not added.
  <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>');
} 

if( $editsuccess){
  echo('<div class="alert alert-success alert-dismissible fade show" role="alert">
    <strong>Success! </strong> Note has been Updated Successfully.
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
  </div>');
}

if($editfail){
  echo('<div class="alert alert-danger alert-dismissible fade show" role="alert">
  <strong>Fail! </strong>  Note is not Updated.
  <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>');
} 

if( $delete){
  echo('<div class="alert alert-success alert-dismissible fade show" role="alert">
    <strong>Success! </strong> Note has been deleted Successfully.
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
  </div>');
}
?>

<div class="container my-4">
<div class="alert alert-success" role="alert">
  <h4 class="alert-heading"><?php echo ("Welcome - ".$_SESSION["username"]); ?></h4>  
</div>
</div>

<div class="container">
 <form action="/loginmodule/welcome.php" method="POST">
 <!-- <h1 class="text-center">Login to our Website</h1> -->
  <div class="mb-3 my-4">
  <label for="title" class="form-label">Title</label>
  <input type="text" class="form-control" id="title" name="title" aria-describedby="emailHelp">
  </div>
  <div class="mb-3">
    <label for="note" class="form-label">Description</label>
    <textarea  class="form-control" id="note" name="note"></textarea>
  </div>
 
  <button type="submit" class="btn btn-primary mb-4">Add Note</button>
 </form>

 <div class="container">
 <table class="table" id="myTable">
  <thead>
    <tr>
      <th scope="col">S.no</th>
      <th scope="col">Title</th>
      <th scope="col">Description</th>
      <th scope="col">Actions</th>
    </tr>
  </thead>
  <tbody>
    <?php
      include('./partials/_dbconnect.php');
      $sql= "SELECT * FROM `notes` ";
      $result= mysqli_query($conn, $sql);
      $num= mysqli_num_rows($result);     
      $i=0;
      $counter=0;
      if($num>0){
          while($rows=mysqli_fetch_assoc($result)){
           // $counter++;
            if($rows['username']== $_SESSION["username"]){
              $i++;
              echo '<tr>
                     <th scope="row">'.$i.'</th>
                     <td>'.$rows['title'].'</td>
                     <td>'.$rows['descripition'].'</td>
                     <td><button type="button" class="edit btn btn-sm btn-primary" id='.$rows['s.no'].'>Edit</button>
                     <button type="button" class="delete btn btn-sm btn-primary" id=d'.$rows['s.no'].'>Delete</button>
                     </td>
                    </tr>';
             //echo $i .". ". $rows['title']. "  ". $rows['descripition']. "  ". $rows['dt'];
              // echo ("<br>");
            }
          }
      }
      else{
          echo("No data in the Database");
      }
    ?> 
    
  </tbody>
</table>
 </div>
<hr>

 <script
      src="https://code.jquery.com/jquery-3.7.1.min.js"
      integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo="
      crossorigin="anonymous"></script>
    <script src= "//cdn.datatables.net/2.0.8/js/dataTables.min.js"></script>
    <script>
      let table = new DataTable('#myTable');
    </script>

    <script>
      edits= document.getElementsByClassName('edit');
      Array.from(edits).forEach((element)=>{
      element.addEventListener("click", (e)=>{
      console.log("edit", );
      tr= e.target.parentNode.parentNode;  
      console.log(e.target.parentNode.parentNode);    
      title= tr.getElementsByTagName("td")[0].innerText;
      note= tr.getElementsByTagName("td")[1].innerText;
      console.log(title,note);
      titleEdit.value = title;
      descriptionEdit.value = note;
      snoedit.value = e.target.id;
      console.log(snoedit);
      $('#editModal').modal('toggle');
      })
     })      

     deletes= document.getElementsByClassName('delete');
      Array.from(deletes).forEach((element)=>{
      element.addEventListener("click", (e)=>{      
      sno = e.target.id.substr(1,);      
      if(confirm("are you sure you want to delete!")){
        console.log("yes");
        window.location = `/loginmodule/welcome.php?delete=${sno}`;
      }
      else{
        console.log("no");
      }
      })
     })      
    </script>
      
</body>
</html>