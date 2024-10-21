<?php 

include_once 'connect.php'; 
session_start(); 

if($_SESSION['useremail']=="" OR $_SESSION['role']=='User'){ 

    header('location:../index.php'); 
    
    } 
  
    
  if($_SESSION['role']=="Admin"){ 
  
   include_once ("header.php"); 
  
  }else{
  
    include_once ("headeruser.php"); 
  
  }

error_reporting(0);

$id=$_GET['id'];

if(isset($id)){

    $delete= $pdo->prepare("delete from tbl_user where userid=".$id);

    if($delete->execute()){

    $_SESSION['status']="Account deleted successfully";
    $_SESSION['status_code']="success";

    }else{

    $_SESSION['status']="Account Is Not Deleted";
    $_SESSION['status_code']="warning";
}

}

if (isset($_POST['btnsave'])) {
    $username = $_POST['txtname'];
    $usercontact = $_POST['txtcontact'];
    $useraddress = $_POST['txtaddress'];
    $usergender = $_POST['txtgender'];
    $useremail = $_POST['txtemail'];   
    $userpassword = $_POST['txtpassword']; // No hashing here
    $userrole = $_POST['txtselect_option'];
   

    $select = $pdo->prepare("INSERT INTO tbl_user (username, useremail, userpassword, role, usercontact, useraddress, usergender) VALUES (:name, :email, :password, :role, :contact, :address, :gender)"); 

    $select->bindParam(':name', $username);
    $select->bindParam(':contact', $usercontact);
    $select->bindParam(':address', $useraddress);
    $select->bindParam(':gender', $usergender);
    $select->bindParam(':email', $useremail);  
    $select->bindParam(':password', $userpassword); // Bind the raw password
    $select->bindParam(':role', $userrole);
  

  $row = $select->fetch(PDO::FETCH_ASSOC);
    if($select->execute()){
        //echo 'Insert successful into the database';
        $_SESSION['status']="Insert successful into the database";
        $_SESSION['status_code']="success";
    } else {
       // echo 'Error inserting the user into the database';
       $_SESSION['status']="Error inserting the user into the database";
       $_SESSION['status_code']="warning";
    }
  }

?>




  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">Registration</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <!--<li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active">Starter Page</li>-->
            </ol>
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <div class="content">
      <div class="container-fluid">
        
            <div class="card card-primary card-outline">
              <div class="card-header">
                <h5 class="m-0">Registration</h5>
              </div>
              <div class="card-body">
              
                <div class="row">
                  <div class="col-md-4">
                    <form action="" method="post">
                      <div class="form-group">
                        <label for="exampleInputEmail1">Name</label>
                        <input type="text" class="form-control" placeholder="Enter Name" name="txtname"required>
                      </div>
                        
                      <div class="form-group">
                        <label for="exampleInputEmail1">Contact Number</label>
                        <input type="text" class="form-control" placeholder="Contact Number" name="txtcontact"required>
                      </div> 
                      
                      <div class="form-group">
                        <label for="exampleInputEmail1">Home Address</label>
                        <input type="text" class="form-control" placeholder="Enter Address" name="txtaddress"required>
                      </div> 

                      <div class="form-group">
                        <label>Role</label>
                        <select class="form-control" name="txtgender"required>
                          <option value=""disabled selected>Gender</option>
                          <option>Male</option>
                          <option>Female</option>
                        </select>
                      </div>

                      <div class="form-group">
                        <label for="exampleInputEmail1">Email address</label>
                        <input type="email" class="form-control" placeholder="Enter email"name="txtemail"required>
                      </div>

                      <div class="form-group">
                        <label for="exampleInputPassword1">Password</label>
                        <input type="password" class="form-control" placeholder="Password"name="txtpassword"required>
                      </div>

                      <div class="form-group">
                        <label>Role</label>
                        <select class="form-control" name="txtselect_option"required>
                          <option value=""disabled selected>Select Role</option>
                          <option>Admin</option>
                          <option>User</option>
                        </select>
                      </div>

                      <div class="card-footer">
                      <button type="submit" class="btn btn-primary" name="btnsave">Save</button>
                      </div>
                    </form>
                  </div>

                  <!-- This div contains the table and aligns it to the right -->
                  <div class="col-md-8 text-end">
                    <table class="table table-striped  table-hover">
                      <thead>
                        <tr>
                          <td>#</td>
                          <td>Name</td>
                          <td>Contact</td>
                          <td>Address</td>
                          <td>Email</td>
                          <td>Password</td>
                          <td>Role</td>
                          <td>Delete</td>
                        </tr>
                      </thead>

                      <tbody>
                        <?php
                          $select = $pdo->prepare("select * from tbl_user order by userid desc"); 
                          $select->execute();
                          while($row=$select->fetch (PDO::FETCH_OBJ)) {
                            echo '
                              <tr>
                            <td>'.$row->userid.'</td>
                                <td>'.$row->username.'</td>
                                  <td>'.$row->usercontact.'</td>
                                    <td>'.$row->useraddress.'</td>
                                      <td>'.$row->usergender.'</td>
                                         <td>'.$row->useremail.'</td>
                                            <td>'.$row->userpassword.'</td>
                                             <td>'.$row->role.'</td>
                                                <td>
                                <a href="registration.php?id='.$row->userid.'" class="btn btn-danger"><i class="fa fa-trash-alt"></i></a>

                                </td>
                              </tr>';
                          }
                        ?>
                      </tbody>
                    </table>
                  </div>

                </div> 
              </div> 
            </div> 
       
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->

<?php 
include_once "footer.php"; 
?> 

<?php 

if(isset($_SESSION['status']) && $_SESSION['status']!=''){ 

?> 

<script> 
      Swal.fire({
        icon: '<?php echo $_SESSION['status_code']?>',
        title: '<?php echo $_SESSION['status']?>'
      })  
</script> 

<?php 
unset($_SESSION['status']); 
} 

?> 
