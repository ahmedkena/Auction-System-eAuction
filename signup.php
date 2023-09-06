<?php
header('Content-Type: text/html; charset=utf-8');
ini_set("default_charset", "UTF-8");
mb_internal_encoding("UTF-8");
iconv_set_encoding("internal_encoding", "UTF-8");
iconv_set_encoding("output_encoding", "UTF-8");
session_start();
if(isset($_SESSION['loggedInUser'])||isset($_COOKIE['loggedInUser']))
{
 header("location:index2.php");
}
else{

//these 7 statements to fix undifend varrible error
$JSEnabled=$_POST['JSEnabled'] ?? '';
$fname = $_POST['fname'] ?? '';
$lname = $_POST['lname'] ?? '';
$username = $_POST['un'] ?? '';
$password = $_POST['password'] ?? '';
$cpassword = $_POST['cpassword'] ?? '';
$un = $_POST['un']?? '';
$user='';

$hpassword;




if ($JSEnabled=="FALSE")
  {
    $fnameValidtion=preg_match('/^[a-z]{3,15}$/i',$fname);
    $lnameValidtion=preg_match('/^[a-z]{3,15}$/i',$fname);
    $usernameValidtion=preg_match('/^[a-z]{3,9}$/i',$un);
    $passwordVaildtion = preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*[a-zA-Z]).{8,}$/',$password);
    if(!$fnameValidtion || !$lnameValidtion)
    {
    echo "<p style=color:red;> *You Should Enter a Vaild Name</p>";
    }
    if(!$usernameValidtion)
    {
    echo "<p style=color:red;> *Your Username Format is Invalid</p>";
    }
    if(!$passwordVaildtion || $password!=$cpassword) {
      echo "<p style=color:red;> *Your Paswword Should:<p>";
      echo "<ul>";
      echo "<li>be a minimum of 8 characters</li>";
      echo "<li>contain at least 1 number</li>";
      echo "<li>contain at least one uppercase character</li>";
      echo "<li>contain at least one lowercase character</li>";
      echo "</ul>";
    }
    if($user == $un){
      echo "<p style=color:red;> *Username is Taken</p>";
    }
  }
else if ($JSEnabled=="TRUE") {
    echo "";
}
if(isset($_POST['signup'])){
$hpassword=md5($password);
try{

  require("conecction.php");
  $db->beginTransaction();
  $stmt = $db->prepare("INSERT INTO users (first_name,last_name,username,password,profile_picture) VALUES(?,?,?,?,?)");
  $stmt->execute(array($fname,$lname,$username,$hpassword,"defualtProfilePic.png"));
  $count = $stmt->rowCount();
  if($count>0)
  {header("location:index.php?reg=1");}
  else
  {echo "<p style=color:red;> Something went wrong please try agian later<p>";}
  $db->commit();
}//end of try bracket
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
  <title>Sign Up</title>
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
    <a class="active" href="signup.php">Sign Up</a>
  <a href="">About</a>
  </div>
</nav>
<div class="signup">
<form onSubmit="return checkUserInputs('signup');" name="signup" method="post" class="signup">
  <p>First Name:</p>
  <input type="text" onkeyup="checkName(this.value)" name="fname" placeholder="First Name" required>
  <br> <span id="nameMsg"></span>
  <p>Last Name:</p>
  <input type="text" onkeyup="checkName2(this.value)" name="lname" placeholder="Last Name" required>
  <br> <span id="nameMsg2"></span>
  <p>Username:</p>
  <input type="text" onkeyup="checkUN(this.value)" name="un" placeholder="Username" required>
  <br> <span id="usernameMsg"></span>
  <p>Password:</p>
  <input type="password" onkeyup="checkPassword(this.value)" id="psw" name="password" placeholder="Password" required>
  <br> <span id="passwordMsg"></span>
  <input type="password" onkeyup="checkCpassword(this.value)" name="cpassword" placeholder="Confirm Password" required>
  <br><span id="cpasswordMsg"></span>
  <p>By creating an account you agree to our <a href="#" style="color:dodgerblue">Terms & Privacy</a>.</p>
  <input type="submit" name="signup" value="Sign Up">
  <input type='hidden' name='JSEnabled' value="FALSE" />
</form>


</div>
<hr>
 <footer class="about" id="about">&copy; 2020. Created by UOB Studentes.
 </footer>
</body>
</html>
<?php }  ?>
