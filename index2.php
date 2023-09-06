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
   <a href="createAuction.php">Create Auction</a>
   <a href="manageProfile.php">Manage Profile</a>
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
         <a href="anitques.php"><h1>Antiques</h1> <P> Old coins, and collectables!</p><img src="imgs/anitques.jpg" style="width:100%;height:70%;"></a>
       </div>
       <div class="column" id="c2">
         <a href="cars.php" ><h1>Cars</h1> <P> Used Cars, and spareparts !</p><img src="imgs/Cars.jpg" style="width:100%;height:70%;"></a>
       </div>
       <div class="column" id="c3">
       <a href="elec.php" ><h1>Electronics</h1> <P> Phones, Machines. and etc.!</p>  <img src="imgs/elec.jpg" style="width:100%;height:70%;"></a>
       </div>
       <div class="column" id="c4">
         <a href="real-estate.php"><h1>Real-Estate</h1> <P> Own your proprety now!</p><img src="imgs/real-estate.jpg" style="width:100%;height:70%;"></a>
       </div>
       <div class="column" id="c5">
         <!--empty div-->
       </div>
     </div>

     <!-- rest of the code here...-->
     </div>


 <hr>
     <footer class="about" id="about">&copy; 2020. Created by UOB Studentes.
     </footer>
 </body>
 </html>
<?php } ?>
