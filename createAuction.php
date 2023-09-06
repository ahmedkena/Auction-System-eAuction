<?php
header('Content-Type: text/html; charset=utf-8');
ini_set("default_charset", "UTF-8");
mb_internal_encoding("UTF-8");
iconv_set_encoding("internal_encoding", "UTF-8");
iconv_set_encoding("output_encoding", "UTF-8");
session_start();
date_default_timezone_set('Asia/Bahrain');
if(!isset($_SESSION['loggedInUser'])||!isset($_COOKIE['loggedInUser']))
{
  header("location:index.php");
}
else {
  if(isset($_POST['createAuction'])){
    $tittle = $_POST['Tittle'] ?? '';
    $desc = $_POST['desc'] ?? '';
    $category = $_POST['category'] ?? '';
    $duration = $_POST['Duration'] ?? '';
    $startingBid = $_POST['startingBid'] ?? '';

  if ((($_FILES["itemPics"]["type"] == "image/gif")
      || ($_FILES["itemPics"]["type"] == "image/jpeg")
      || ($_FILES["itemPics"]["type"] == "image/pjpeg")
      || ($_FILES["itemPics"]["type"] == "image/jpg")
      || ($_FILES["itemPics"]["type"] == "image/png"))
      && ($_FILES["itemPics"]["size"] < 10000000)) {
            if ($_FILES["itemPics"]["error"] > 0) {
                  echo "Return Code: " . $_FILES["itemPics"]["error"] . "<br />";
            }
            else {
              $fdetails=explode(".",$_FILES["itemPics"]["name"]);
              $fext=end($fdetails);

              $filename="User=".$_SESSION['loggedInUser']."&".time().".$fext";

              if (move_uploaded_file($_FILES["itemPics"]["tmp_name"], "items pictures/$filename"))
              {
                        echo "";
              }
              else {
                        die();
              }
            }
    }
    if(empty($tittle) || empty($desc) || !isset($category) || !isset($duration) || !isset($startingBid) ){
      echo "<p style=color:red;> You should fill out all requierd fields.</p>";
    }
    else{
      try{
        require("conecction.php");
        $db->beginTransaction();
        $today = date("Y-m-d H:i:s");
        $endDate = date("Y-m-d H:i:s" , strtotime($today. ' +' .$duration));
        $ins = $db->prepare("INSERT INTO items (UID,Tittle,Description,Category,Duartion,startDate,endDate,startingBid,image,status) VALUES (?,?,?,?,?,?,?,?,?,'active')");
        $ins->bindParam(1,$_SESSION['loggedInUser']);
        $ins->bindParam(2,$tittle);
        $ins->bindParam(3,$desc);
        $ins->bindParam(4,$category);
        $ins->bindParam(5,$duration);
        $ins->bindParam(6,$today);
        $ins->bindParam(7,$endDate);
        $ins->bindParam(8,$startingBid);
        $ins->bindParam(9,$filename);
        $ins->execute();
        $count=$ins->rowCount();
        $id=$db->lastInsertId();
        if($count>0){
          header("location:single_item.php?id=".$id);
        }
        else{
          echo "<p style=color:red;> Adding item failed, please try again.</p>";
        }
    $db->commit();
}
catch(PDOException $ex)
{
  $db->rollBack();
  echo "<p style=color:red;> There was an connection error.<p>";
  die($ex->getMessage());
}//end of catch bracket

  }



  }







?>
<html>
<head>
  <title>Create Auction</title>
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
    <a class="active" href="createAuction.php">Create Auction</a>
    <a  href="manageProfile.php">Manage Profile</a>
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
<div class="create">
  <form enctype="multipart/form-data" name="edit" method="post" class="signup" >
    <p>Tittle:</p>
    <input type="text" name="Tittle" placeholder="Tittle" required >
    <p>Item Decription:</p>
    <textarea rows="10" cols="50"  name="desc" placeholder="Breif summary of your item ..." required ></textarea>
    <p>Category:</p>
    <select name="category" required>
      <option selected disabled>Choose Option..</option>
      <option value="Real-Estate">Real-Estate</option>
      <option value="Cars">Cars</option>
      <option value="Antiques">Antiques</option>
      <option value="Electronics">Electronics</option>
    </select>
    <p>Duration:</p>
    <select name="Duration" required>
      <option selected disabled>Choose Option..</option>
      <option value="3 hours">3 Hours</option>
      <option value="6 hours">6 Hours</option>
      <option value="1 days">1 Day</option>
      <option value="3 days">3 Days</option>
      <option value="7 days">7 Days</option>
    </select>
    <p>Starting Price:</p>
    <input type='number' id="startingBid" name='startingBid' min='1' step=".1" required>  <label id="bd" for="startingBid"> BD </label> <br>
    <input id="itemPics" type='file' name='itemPics' required>
    <label for='itemPics'>Click here to upload item pictures</label>
    <input type='submit' name='createAuction' value='Create Auction'>
  </form>

</div>
</body>
</html>
<?php } ?>
