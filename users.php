<?php
@ob_start();
session_start();
    include 'inc/header.php';
    include 'inc/footer.php';
    include 'app/connect.php';

if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == true) {
  if(isset($_POST['search'])){
    $firstname = mysqli_real_escape_string($conn, $_POST['fnm']);
    $lastname = mysqli_real_escape_string($conn, $_POST['lnm']);
    if(empty($firstname) && empty($lastname)){
      $error = "Please enter a name";
    }
    else{
    	$sql = "SELECT * FROM User WHERE Fname LIKE '%$firstname%' AND Lname LIKE '%$lastname%'";  //Queries on both
      if(!empty($firstname) && empty($lastname)){ //only last name
        $sql = "SELECT * FROM User WHERE Fname LIKE '%$firstname%'";
        }
      if(empty($firstname) && !empty($lastname)){ //only first name
      	$sql = "SELECT * FROM User WHERE Lname LIKE '%$lastname%'";
      }
      $users = [];
      $user_result = mysqli_query($conn, $sql);
      if(mysqli_num_rows($user_result) > 0){
          while($row = mysqli_fetch_assoc($user_result)){
              $username = $row['user_name']; 
              $firstname = $row['Fname'];
              $lastname = $row['Lname'];
              array_push($users, $username); //user object prob needed 
          }
      } else{
        $error = "No Users Found.";
      }
    }
  }
} else {
    header('Location: login.php');
}
?>
      <div class="container">
    <div class="starter-template">
      <h1>Users</h1>
      <div class="text-center">
          <h2>Who would you like to look up?</h2>
          <br/>
          <form class="signup-form" action ="" method="POST">
            <div class="form-group">
                <input type="text" class="form-input-txtbox" placeholder="First Name" name="fnm">
                <input type="text" class="form-input-txtbox" placeholder="Last Name" name="lnm">
                <button type="search" name="search" class="btn btn-primary">Search</button>
            </div>
        </form>
      </div>
      <div style = "font-size:11px; color:#cc0000; margin-top:10px"><?php echo $error; ?></div>
      <div class="row"><hr></div>
      <?php
          if(count($users)>0){
            foreach($users as & $u){?>
        <div class="row">
        	<?php echo $u; //add link to user's profile page
            ?>
        </div>
        <div class="row"><hr></div>
      <?php }} ?>
    </div>
  </div>