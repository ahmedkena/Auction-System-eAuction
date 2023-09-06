<?php session_start();


header('Content-Type: text/html; charset=utf-8');
ini_set("default_charset", "UTF-8");
mb_internal_encoding("UTF-8");
iconv_set_encoding("internal_encoding", "UTF-8");
iconv_set_encoding("output_encoding", "UTF-8");


 ?>
<html>
<head>
  <title>My Wish List</title>
  <link rel="shortcut icon" type="image/x-icon" href="imgs/favicon.ico">
  <meta charset="utf-8">
  <link rel="stylesheet" href="index.css">
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
   echo '<a href="./index.php">Home</a>';
  else
  echo '<a  href="./index2.php">Home</a>';
    ?>

    <?php
    if(isset($_SESSION['loggedInUser']) || isset($_COOKIE['loggedInUser']))
    {
      echo '<a href="createAuction.php">Create Auction</a>';
      echo '<a href="manageProfile.php">Manage Profile</a>';
      echo '<a class="active" href="userWishList.php">My Wishlist</a>';
    }
    else{
        echo '<a href="signup.php">Sign Up</a>';
    }
     ?>
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
<?php
try{
  require("conecction.php");
  $db->beginTransaction();
  $uid=$_SESSION['loggedInUser'];
  $rs=$db->query("SELECT userwishlist.*,users.*,items.* FROM userwishlist,users,items WHERE users.uid='$uid' AND users.uid=userwishlist.uid AND userwishlist.itemID=items.ID ");
  $today = date("Y-m-d H:i:s");
  $count=$rs->rowCount();
  if($count==0){
    echo "<h1>No Results Found!</h1>";
  }
  foreach ($rs as $row) {
    $id=$row[9];
    $sellerID=$row[10];
    $itemName=$row[11];
    $desc=$row[12];
    $duartion=$row[14];
    $startDate=$row[15];
    $endDate=$row[16];
    $sBid=$row[17];
    $img=$row[18];
    if($endDate!=$today){
    echo "<table width=100% style='border:1px solid black;' align=center >";
    echo "<tr>";
    echo '<td style="height:150px;width:700px"><img src="items pictures/'.$img. '"width="150" height="150"></td>';
    echo "<td style='height:150px;width:300px'><h3 style=text-align:center;>".$itemName."</h3>";
    echo "<h6 style=text-align:center;>".nl2br($desc)."</h6><td>";
    echo "<td style='height:150px;width:200px;text-align:center;'><a  href=single_item.php?id=".$id." style=text-align:center;>View</a>";
    echo "<p style=text-align:center;>Ends at: ".$endDate."</p></td>";
    echo "</tr>";
    echo "</table>";
  }
  }
  $db->commit();
}
catch(PDOException $ex)
{
$db->rollBack();
echo "<p style=color:red;> There was an connection error.<p>";
die($ex->getMessage());
}//end of catch bracket

 ?>
</body>
</html>
