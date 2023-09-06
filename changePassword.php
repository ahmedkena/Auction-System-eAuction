<?php
session_start();
header('Content-Type: text/html; charset=utf-8');
ini_set("default_charset", "UTF-8");
mb_internal_encoding("UTF-8");
iconv_set_encoding("internal_encoding", "UTF-8");
iconv_set_encoding("output_encoding", "UTF-8");
if(!isset($_SESSION['loggedInUser'])||!isset($_COOKIE['loggedInUser']))
{
  header("location:index.php");
}
else {
  $JSEnabled=$_POST['JSEnabled'] ?? '';
  $password = $_POST['password'] ?? '';
  $cpassword = $_POST['cpassword'] ?? '';
  $oldPassword = $_POST['oldPassword'] ?? '';
  if(isset($_POST['change'])){
    if ($JSEnabled=="FALSE"){
      $passwordVaildtion = preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*[a-zA-Z]).{8,}$/',$password);
      if(!$passwordVaildtion || $password!=$cpassword) {
        echo "<p style=color:red;> *Your Paswword Should:<p>";
        echo "<ul>";
        echo "<li>be a minimum of 8 characters</li>";
        echo "<li>contain at least 1 number</li>";
        echo "<li>contain at least one uppercase character</li>";
        echo "<li>contain at least one lowercase character</li>";
        echo "</ul>";
        die();
      }
  }
  else{
    echo"";
  }
  try{
    require("conecction.php");
    $db->beginTransaction();
    $stmt = $db->prepare("SELECT * FROM users WHERE uid=?");
    $stmt->execute(array($_SESSION['loggedInUser']));
    $count = $stmt->rowCount();
    foreach($stmt as $row)
    {
      $currentPassword=$row[4];
    }
    if($oldPassword==$currentPassword && $oldPassword!=$password){
      $stmt = $db->prepare("UPDATE users SET password=MD5(?) WHERE uid=?");
      $stmt->execute(array($password,$_SESSION['loggedInUser']));
      $count=$stmt->rowCount();
      if($count>0)
      {
        echo "Password changed sucessfully";
        header( "refresh:5;url=manageProfile.php" );
      }
    }
    else{
      echo "You have entered Invalid Passsword";
    }
  }
    catch(PDOException $ex)
    {
      $db->rollBack();
      echo "<p style=color:red;> There was an connection error.<p>";
      die($ex->getMessage());
    }//end of catch bracket
}
  ?>
  <html>
  <head>
    <title>Manage Profile</title>
    <link rel="shortcut icon" type="image/x-icon" href="imgs/favicon.ico">
    <meta charset="utf-8">
    <link rel="stylesheet" href="index.css">
    <script src="myJSFunctions.js"></script>
  </head>
  <body>
        <div class="search">
          <form method="get" action="search.php">
            <input type="search" name="searchbar" placeholder="Search Something ...">
            <input type="submit" name="search" value="Search!">
          </form>
          </div>
    <nav>
      <?php if(!isset($_SESSION['loggedInUser'])||!isset($_COOKIE['loggedInUser']))
       echo '<div class="logo"> <a href="index.php"><strong>e</strong>Auction</a></div>';
      else
      echo '<div class="logo"> <a href="index2.php"><strong>e</strong>Auction</a></div>';
        ?>
  <div class="topnav">
    <?php if(!isset($_SESSION['loggedInUser'])||!isset($_COOKIE['loggedInUser']))
  {   echo '<a  href="index.php">Home</a>';
     echo '<a href="index.php#exploreCategories">Categories</a>';}
    else
  {  echo '<a href="index2.php">Home</a>';
    echo '<a href="index2.php#exploreCategories">Categories</a>';}
      ?>
      <a href="createAuction.php">Create Auction</a>
      <a class="active" href="manageProfile.php">Manage Profile</a>
      <a href="userWishList.php">My Wishlist</a>
      <a href="">About</a>
      <?php
      if(isset($_SESSION['loggedInUser']) || isset($_COOKIE['loggedInUser']))
      {
        echo '<form class="logout" name="logout">';
        echo '<input type="submit" name="logout" value="Log Out">';
        echo '</form>';
      }
       ?>

      <?php
      if(isset($_GET['logout'])){
      if(isset($_SESSION['loggedInUser']) || isset($_COOKIE['loggedInUser']))
      {
      unset($_SESSION['loggedInUser']);
      setcookie('loggedInUser','',time()-55555555);
         header("index.php");
      }
      }
      ?>
    </div>
  </nav>
  <div class="edit">
    <form onSubmit="return checkUserInputs(changepsw);" name="changepsw" method="post" class="signup" >
      <p>Change Password:</p>
      <input type="password" name="oldPassword" placeholder="Old Password" required>
      <input type="password" onkeyup="checkPassword(this.value)" id="psw" name="password" placeholder="Password" required>
      <br> <span id="passwordMsg"></span>
      <input type="password" onkeyup="checkCpassword(this.value)" name="cpassword" placeholder="Confirm Password" required>
      <br><span id="cpasswordMsg"></span>
      <div class="changepsw">
      <input type="submit" name="change" value="Change">
      <input type="submit" name="cancel" value="Cancel">
      <input type='hidden' name='JSEnabled' value="FALSE" />
    </div>
    </form>

  </div>

  </body>
  </html>
  <?php

  }


  ?>
