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
  $newfname = $_POST['newfname'] ?? '';
  $newlname = $_POST['newlname'] ?? '';
  $newusername = $_POST['newusername'] ?? '';


  if(isset($_POST['upload'])){
  if ((($_FILES["userPic"]["type"] == "image/gif")
      || ($_FILES["userPic"]["type"] == "image/jpeg")
      || ($_FILES["userPic"]["type"] == "image/pjpeg"))
      && ($_FILES["userPic"]["size"] < 5000000)) {
            if ($_FILES["userPic"]["error"] > 0) {
                  echo "Return Code: " . $_FILES["userPic"]["error"] . "<br />";
            }
            else {
              $fdetails=explode(".",$_FILES["userPic"]["name"]);
              $fext=end($fdetails);

              $filename="User=".$_SESSION['loggedInUser']."&".time().".$fext";

              if (move_uploaded_file($_FILES["userPic"]["tmp_name"], "profile pictures/$filename"))
              {
                        echo "<p style=color:green;> Photo updated sucessfully</p>";
              }
              else {
                        die();
              }
            }
    }
  }

  try{
    require("conecction.php");
    $db->beginTransaction();
    $select1 = $db->prepare("SELECT * FROM users WHERE uid=?");
    $select1->execute(array($_SESSION['loggedInUser']));
    $count = $select1->rowCount();
    foreach($select1 as $row)
    {
      $currentFirstName=$row[1];
      $currentLastName=$row[2];
      $currentUsername=$row[3];
      $currentProfilePic=$row[5];
    }
    if(isset($_POST['b1']) &&  $_POST['b1']=="Save"  ){
    if ($JSEnabled=="FALSE"){
      $fnameValidtion=preg_match('/^[a-z]{3,15}$/i',$newfname);
      if(!$fnameValidtion)
      {
      echo "<p style=color:red;> *You Should Enter a Vaild Name</p>";
      die();
      }
    }
    $update1 = $db->prepare("UPDATE users SET first_name= ? WHERE uid=?");
    if($newfname == $currentFirstName)
    {
      echo"<p style=color:green;> No changes are done.</p>";
    }
    else{
      $update1->execute(array($newfname,$_SESSION['loggedInUser']));
      $count1=$update1->rowCount();
      if($count1>0)
      echo "<p style=color:green;> Changes Saved Sucessfully</p>";
    }
  }

  if(isset($_POST['b2']) && $_POST['b2']=="save"){
  if ($JSEnabled=="FALSE"){
    $lnameValidtion=preg_match('/^[a-z]{3,15}$/i',$newlname);
    if(!$lnameValidtion)
    {
    echo "<p style=color:red;> *You Should Enter a Vaild Name</p>";
    die();
    }
  }
  $update2 = $db->prepare("UPDATE users SET last_name = ? WHERE uid=?");
  if($newlname == $currentLastName)
  {
    echo"<p style=color:green;> No changes are done.</p>";
  }
  else{
    $update2->execute(array($newlname,$_SESSION['loggedInUser']));
    $count2=$update2->rowCount();
    if($count2>0)
    echo "<p style=color:green;> Changes Saved Sucessfully</p>";
  }
}

if(isset($_POST['b3']) && $_POST['b3']=="save"){
if ($JSEnabled=="FALSE"){
  $usernameValidtion=preg_match('/^[a-z]{3,15}$/i',$newusername);
  if(!$usernameValidtion)
  {
  echo "<p style=color:red;> *You Should Enter a Vaild Name</p>";
  die();
  }
}
else echo"";
$update3 = $db->prepare("UPDATE users SET username = ? WHERE uid=?");
if($newusername == $currentUsername)
{
  echo"<p style=color:green;> No changes are done.</p>";
}
else{
  $update3->execute(array($newusername,$_SESSION['loggedInUser']));
  $count3=$update3->rowCount();
  if($count3>0)
  echo "<p style=color:green;> Changes Saved Sucessfully</p>";
}
}

if(isset($filename)){
  $update4=$db->prepare("UPDATE users SET profile_picture = ? WHERE uid=?");
	$update4->execute(array($filename,$_SESSION['loggedInUser']));
  $count4=$update4->rowCount();
  if($count4>=0)
{
  header("Refresh:0");
}
}

    $db->commit();
  }//end of try bracket
  catch(PDOException $ex)
  {
    $db->rollBack();
    echo "<p style=color:red;> There was an connection error.<p>";
    die($ex->getMessage());
  }//end of catch bracket

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
<div class="edit1">
  <p> Acppetable photo formats are: gif/jepg/pjepg<br> and it should be maximum of 5 MB</p>
</div>
<div class="edit">
  <form enctype="multipart/form-data" onSubmit="return checkUserInputs(edit);" name="edit" method="post" class="signup" >
    <p>First Name:</p>
    <input type="text" id="i1" value=<?php echo $currentFirstName;  ?> onkeyup="checkName(this.value)" name="newfname" placeholder="First Name" readonly>
    <button type='button' name="b1" id="b1" value="Edit" onclick="changeText('b1','i1')">Edit</button>
     <span id="nameMsg"></span>
    <p>Last Name:</p>
    <input type="text" id="i2" value=<?php echo $currentLastName; ?> onkeyup="checkName2(this.value)" name="newlname" placeholder="Last Name" readonly >
    <button type='button' value="Edit" name="b2" id="b2" onclick="changeText('b2','i2')">Edit</button>
    <br> <span id="nameMsg2"></span>
    <p>Username:</p>
    <input type="text" id="i3" value="<?php echo $currentUsername;  ?>" onkeyup="checkUN(this.value)" name="newusername" placeholder="Username" readonly >
    <button type='button' name="b3" id="b3" onclick="changeText('b3','i3')">Edit</button>
    <br> <span id="usernameMsg"></span>
    <p>Password:</p>
    <input type="password" value="****************"  id="psw" placeholder="Password" readonly >
    <button type='button' id="b4" onclick="location.href = 'ChangePassword.php';">Edit</button>
    <?php
    if(file_exists('profile pictures/'.$currentProfilePic))
    echo '<img id="userImg" src="profile pictures/'.$currentProfilePic. '"width="250" height="250">';
    else{
     ?>
    <img id="userImg" src="profile pictures/defualtProfilePic.png" width="250" height="250"><?php } ?>
    <label for="upload-photo">Browse</label>
    <input type="file" name="userPic" id="upload-photo" />
    <input type="submit" name="upload" value="Upload">
    <input type='hidden' id='isset1' name='isset1' value="FALSE" />
    <input type='hidden' name='JSEnabled' value="FALSE" />
  </form>

</div>

</body>
</html>
<?php

}


?>
