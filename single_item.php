<?php
header('Content-Type: text/html; charset=utf-8');
ini_set("default_charset", "UTF-8");
mb_internal_encoding("UTF-8");
iconv_set_encoding("internal_encoding", "UTF-8");
iconv_set_encoding("output_encoding", "UTF-8");
session_start();
date_default_timezone_set('Asia/Bahrain');
if(!isset($_GET['id'])){
  echo"<h1>Somethimng went wrong, please tryagain</h1>";
}
try{
  require("conecction.php");
  $db->beginTransaction();
  $rs=$db->prepare("SELECT * FROM items WHERE ID=? ");
  $rs->execute(array($_GET['id']));

  foreach ($rs as $row) {
    $id=$row[0];
    $sellerID=$row[1];
    $itemName=$row[2];
    $desc=$row[3];
    $category=$row[4];
    $duartion=$row[5];
    $startDate=$row[6];
    $endDate=$row[7];
    $sBid=$row[8];
    $img=$row[9];
    $status=$row[10];
  }
  $currentBid=$sBid;
  $rs2=$db->prepare("SELECT * FROM bids WHERE itemID=? ");
  $rs2->execute(array($id));
  foreach ($rs2 as $row) {
    $currentBid=$row[3];
  }
  $user=$db->query("SELECT * FROM users WHERE uid='$sellerID'");
  foreach($user as $row){
    $SellerUsername=$row[3];
  }

  echo '<div class="msg">';
if (isset($_SESSION['loggedInUser'])){
if(isset($_POST['placeBid'])){
  if(isset($_POST['bid'])){
    $ins2 = $db->prepare("INSERT INTO bids (itemID,buyerID,bid) VALUES (?,?,?)");
    if($_POST['bid']>$currentBid){
      $currentBid=$_POST['bid'];
      $ins2 -> execute(array($id,$_SESSION['loggedInUser'],$currentBid));
      echo "<p style=color:red;>You are now the highest bidder!</p>";
    }

    else
      echo"<p style=color:red;>You should insert a higher bid.</p>";
  }
  else {
    echo"<p style=color:red;>You should put your bid first!!</p>";
  }
}
if(isset($_POST['AddToWishList'])){
$uid=$_SESSION['loggedInUser'];
  $selwish=$db->query("SELECT itemID FROM userwishlist WHERE uid='$uid'");
  foreach($selwish as $row){
    $itemID=$row[0];
  }
  if($itemID==$_GET['id']){
    echo"<p style=color:red;>This item already exists in your wishlist</p>";
  }
$insWish=$db->prepare("INSERT INTO userwishlist(uid,itemID) VALUES (?,?)");
$insWish->execute(array($_SESSION['loggedInUser'],$_GET['id']));

if($insWish->rowCount()==0)
echo"<p style=color:red;>Something went wrong please tryagain</p>";
else header("location:userWishList.php");
}
if(isset($_POST['delete'])){
  $del=$db->prepare("DELETE FROM items WHERE ID=?");
  $del->execute(array($_GET['id']));
  if($del->rowCount()==0)
  echo"<p style=color:red;>Something went wrong please try again!!</p>";
  else
    echo"<p style=color:red;>Item Deleted Sucessfully</p>";
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
echo '</div>';
?>
<html>
<head>
  <title><?php echo $itemName; ?></title>
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

        <?php
        if(isset($_SESSION['loggedInUser']) || isset($_COOKIE['loggedInUser']))
        {
          echo '<a href="createAuction.php">Create Auction</a>';
          echo '<a href="manageProfile.php">Manage Profile</a>';
          echo '<a href="userWishList.php">My Wishlist</a>';
        }
        else{
            echo '<a href="signup.php">Sign Up</a>';
        }
         ?>
        <a href="">About</a>
  </div>
</nav>
<div class="abcd">
<?php
echo "<h1>".$itemName."</h1>";
echo '<img src="items pictures/'.$img. '"width="350" height="450">';
echo "<div class='besideImg'>";
echo "<p>Category: ".$category."</p>";
echo "<p><b> Item Description: </b>".nl2br($desc)."</p>";
echo "<p> Ends at: ".$endDate."</p>";
echo "<p>Offerd by: ".$SellerUsername."</p>";
try{
  require("conecction.php");
  $db->beginTransaction();
  $rs=$db->prepare("SELECT * FROM users WHERE uid=? ");
  $rs->execute(array($sellerID));
  $id=$_GET['id'];
  $rs2=$db->query("SELECT bids.*,users.* FROM bids,users WHERE itemID='$id' AND bids.buyerID=users.uid ");
  $rs3=$db->query("SELECT bids.*,users.* FROM bids,users WHERE bids.bid=(SELECT MAX(bid) FROM bids WHERE itemID='$id' ) AND bids.buyerID=users.uid");
foreach($rs3 as $row){
  $highestBid=$row[3];
  $buyerUser=$row[7];
}
if(isset($_POST['setStatus'])){
  if(isset($_POST['status']) && $_POST['status']=="Failed" && $sellerID==$_SESSION['loggedInUser']){
  $update1=$db->prepare("UPDATE items SET status=? WHERE ID=?");
  $update1->execute(array('Failed',"$id"));
  $uoCoun1=$update1->rowCount();
  if($uoCoun1==0)
  echo"<p>Somethimng went wrong, please tryagain</p>";
}
}
else if (isset($_POST['setStatus'])){
  if(isset($_POST['status']) && $_POST['status']=="Completed" && $sellerID==$_SESSION['loggedInUser']){
  $update=$db->prepare("UPDATE items SET status=? WHERE ID=?");
  $update->execute(array('Completed',$id));
  $uoCoun=$update->rowCount();
  if($uoCoun==0)
    echo"<p>Somethimng went wrong, please tryagain</p>";
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
echo '<div class="myDIV"><p>Bids History:</p></div>';
echo '<div class="hide">';
echo '<table style="border:1px solid black";>';
echo '<tr>';
echo '<th>User</th>';
echo '<th>Bid</th>';
echo '</tr>';
foreach ($rs2 as $row) {
  echo '<tr>';
  echo '<td>'.$row[7].'</td>';
  echo '<td>'.$row[3].'</td>';
  echo '</tr>';
}
echo '</table>';
echo '</div>';
echo "<form method='post'>";
$today=date("Y-m-d H:i:s");
if(isset($_SESSION['loggedInUser'])&&$sellerID==$_SESSION['loggedInUser']  && $today<$endDate)
{
  echo "<input type='submit' name='edit' value='Edit'>";
  echo "<input type='submit' name='delete' value='Delete'>";
    echo "<input readonly type='number' name='bid' min=".$currentBid." step='.1' value=".$currentBid. "> <nobr>  <p id='bd'> BD </p> ";
}
else if( isset($_SESSION['loggedInUser'])&& $sellerID==$_SESSION['loggedInUser'] && $today>=$endDate && $status=="active")
{

  if($rs3->rowCount()==0)
{  echo "<p>No one auctioned on this item, you can republish it: </p><br> ";
  echo "<input type='submit' name='republish' value='Re-publish'>";}
  else{
    echo "<p style=color:red;>This Auction has ended, please mark the transcation status</p><br>";
    echo "<p>The highest bid was ".$highestBid." BD by ".$buyerUser." </p><br>" ;
    echo "<input type='radio' id='st1' name='status' value='Completed'>";
    echo "<label for='st1'>Completed</label><br>";
    echo "<input type='radio' id='st2' name='status' value='Failed'>";
    echo "<label for='st2'>Failed</label><br>";
    echo "<input type='submit' name='setStatus' value='Set item status'>";
  }

}
else if (isset($_SESSION['loggedInUser'])&& $sellerID==$_SESSION['loggedInUser'] && $today>=$endDate && $status=="Failed"){
  echo "<p>You marked this auction status as failed</p><br>";
}
else if (isset($_SESSION['loggedInUser'])&& $sellerID==$_SESSION['loggedInUser'] && $today>=$endDate && $status=="Completed"){
  echo "<p>You marked this auction status as completed</p><br>";
}
else if(isset($_SESSION['loggedInUser']) && $today<$endDate && $status=="active" ){
  echo "<input type='number' name='bid' min=".$currentBid." step='.1' value=".$currentBid."> <nobr>  <p id='bd'> BD </p> <br>";
  echo "<input type='submit' name='placeBid' value='Place Bid'>";
  echo "<input type='submit' name='AddToWishList' value='Add To Wish List'>";

}
else if(!isset($_SESSION['loggedInUser']))
{
  echo "<p>You Should Login, In order to particapite in auctions</p>";
}
else{
echo "<p>This item has expierd</p>";
}

echo "</div>";


 ?>
</form>


</div>
</body>
</html>
