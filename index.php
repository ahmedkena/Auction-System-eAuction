<?php
session_start();
header('Content-Type: text/html; charset=utf-8');
ini_set("default_charset", "UTF-8");
mb_internal_encoding("UTF-8");
iconv_set_encoding("internal_encoding", "UTF-8");
iconv_set_encoding("output_encoding", "UTF-8");
if(isset($_SESSION['loggedInUser'])||isset($_COOKIE['loggedInUser']))
{
  header("location:index2.php");
}
else{

if ( isset($_GET['reg']) && $_GET['reg'] == 1 )
{
     echo '<script>window.alert("You\'ve Registered Suseesfully!\n Please Login ")</script>';
}
$username = $_POST['username'] ?? '';
$password = $_POST['password'] ?? '';
$remember = $_POST['remember'] ?? '';
$hpassword=md5($password);
$uid;
$firstName;

if(isset($_POST['Login'])){
  if(empty($username)&& empty($password))
  echo '<script>alert("You should enter usernaame and password !")</script>';
  else{
  try{
    require("conecction.php");
    $db->beginTransaction();
    $rs=$db->prepare("SELECT * FROM users  WHERE username=? AND password=?");
    $rs->execute(array($username,$hpassword));
    $count=$rs->rowCount();
    foreach($rs as $row)
    $uid=$row[0];
    if($count>=1)
    {
    $_SESSION['loggedInUser']=$uid;
    if(isset($remember))
    setcookie('loggedInUser',$uid,time()+55555555);
    header("location: index2.php");

    }
    else
      echo "<script>window.alert(\"Invaild Username or Password\")</script>";
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
  <title>eAuction</title>
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
   echo '<a class="active" href="index.php">Home</a>';
  else
  echo '<a class="active" href="index2.php">Home</a>';
    ?>
  <a href="#exploreCategories">Categories</a>
  <a href="signup.php">Sign Up</a>

  <a href="">About</a>


<div id="login" class="login-container">
    <form method="post">
     <input type="text" placeholder="Username" name="username" required>
     <input type="password" placeholder="Password" name="password" required>
     <input type="submit" name="Login" value="Login">
     <input type="checkbox" name="remember" value="true">
     <label for="remember"> Remember Me</label>
</form>
</div>

</nav>
<div class="img_container">
  <img src="imgs/bg1.jpg" >
  <div class="welcome_text">
  <h1>Welcome to eAuction</h1>
  <p>C2C Auction Website !</p>
</div>
</div>




<a class="scroll1" href="#exploreActiveAuctions"><img src="imgs/scroll-icon.png"></a>
<div class="exploreActiveAuctions" id="exploreActiveAuctions">
  <h1> Explore Active Auctions: </h1><br><br><br>
  <img src="imgs/ddd.png">
  <!-- rest of the code here...-->
  </div>
  <a class="scroll2" href="#exploreCategories"><img src="imgs/scroll-icon.png"></a>

  <div class="exploreCategories" id="exploreCategories">
    <h1> Explore Categories: </h1><br><br><br><br><br><br>
    <div class="row">
      <div class="column" id="c1">
        <a href="anitques.php" ><h1>Antiques</h1> <P> Old coins, and collectables!</p><img src="imgs/anitques.jpg" style="width:100%;height:70%;"></a>
      </div>
      <div class="column" id="c2">
        <a href="cars.php" ><h1>Cars</h1> <P> Used Cars, and spareparts !</p><img src="imgs/Cars.jpg" style="width:100%;height:70%;"></a>
      </div>
      <div class="column" id="c3">
      <a href="elec.php"><h1>Electronics</h1> <P> Phones, Machines. and etc.!</p>  <img src="imgs/elec.jpg" style="width:100%;height:70%;"></a>
      </div>
      <div class="column" id="c4">
        <a href="real-estate.php" ><h1>Real-Estate</h1> <P> Own your proprety now!</p><img src="imgs/real-estate.jpg" style="width:100%;height:70%;"></a>
      </div>
      <div class="column" id="c5">
        <!--empty div-->
      </div>
    </div>

    <!-- rest of the code here...-->
    </div>


<hr>
    <footer class="about" id="about">&copy; 2020. Created by UOB Studente.
    </footer>
</body>
</html>
<?php
} ?>
