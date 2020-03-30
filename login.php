<?php 
    session_start();

    $emailError2 = "";
    $pwError2 = "";

    if(isset($_POST['login'])){
        if(!isset($_SESSION['ime'])){
            try{

                if(isset($_POST['email']) && $_POST['email'] != ""){
                    $email = $_POST['email'];
                }else{
                    $emailError2 = "E-mail is required";
                }

                if(isset($_POST['pw']) && $_POST['pw'] != ""){
                    $pw = $_POST['pw'];
                }else{
                    $pwError2 = "Password is required";
                }
                
               
            
                if($emailError2 != "" || $pwError2 != ""){
                    throw new PDOException();
                }else{                

                    require "config.php";

                    $connection = new PDO($dsn, $username, $password, $options);

                    $sql = "SELECT *
                        FROM korisnici
                        WHERE email = :email
                        AND password = :pw"; 
                
                
                    $statement = $connection->prepare($sql);
                    $statement->bindParam(':email', $email, PDO::PARAM_STR);
                    $statement->bindParam(':pw', $pw, PDO::PARAM_STR);
                    $statement->execute();

                    $result = $statement->fetchAll();
                    
                    
                    
                    foreach($result as $row){
                        $_SESSION['ime'] = $row['ime'];
                        $_SESSION['id'] = $row['id'];
                    }
                    
                    if($statement->rowCount() == 0){
                        $emailError2 = "Incorrect e-mail";
                        $pwError2 = "Incorrect password";
                    } else{
                        header("Location: index.php");
                    }
                    
                    
                      
                }
            

            }catch(PDOException $error){
                echo $error->getMessage();
            }
        }else{
            $ulogovanError = "Jedan korisnik je vec ulogovan.";
        }
    }

?>
<!doctype html>
<html lang="en">

  <head>
    <title>Realtors &mdash; Website Template by Colorlib</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <link href="https://fonts.googleapis.com/css?family=DM+Sans:300,400,700&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="fonts/icomoon/style.css">

    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/bootstrap-datepicker.css">
    <link rel="stylesheet" href="css/jquery.fancybox.min.css">
    <link rel="stylesheet" href="css/owl.carousel.min.css">
    <link rel="stylesheet" href="css/owl.theme.default.min.css">
    <link rel="stylesheet" href="fonts/flaticon/font/flaticon.css">
    <link rel="stylesheet" href="css/aos.css">

    <!-- MAIN CSS -->
    <link rel="stylesheet" href="css/style.css">

  </head>

  <body data-spy="scroll" data-target=".site-navbar-target" data-offset="300">

    
    <div class="site-wrap" id="home-section">

      <div class="site-mobile-menu site-navbar-target">
        <div class="site-mobile-menu-header">
          <div class="site-mobile-menu-close mt-3">
            <span class="icon-close2 js-menu-toggle"></span>
          </div>
        </div>
        <div class="site-mobile-menu-body"></div>
      </div>



      <header class="site-navbar site-navbar-target" role="banner">

        <div class="container">
          <div class="row align-items-center position-relative">

            <div class="col-3 ">
              <div class="site-logo">
                <a href="index.html">Realtors</a>
              </div>
            </div>

            <div class="col-9  text-right">
              

              <span class="d-inline-block d-lg-none"><a href="#" class="text-white site-menu-toggle js-menu-toggle py-5 text-white"><span class="icon-menu h3 text-white"></span></a></span>

              

              <nav class="site-navigation text-right ml-auto d-none d-lg-block" role="navigation">
                <ul class="site-menu main-menu js-clone-nav ml-auto ">
                  <li><a href="index.php" class="nav-link">Home</a></li>
                  <li><a href="#" class="nav-link">Agents</a></li>
                  <li><a href="#" class="nav-link">Property</a></li>
                  <li><a href="#" class="nav-link">About</a></li>
                  <?php if(isset($_SESSION['ime'])){ ?>
                      <li><a style="color:#90bede!important" class="submit-property" href="submit-property.php">Submit Property</a></li>
                      <?php } ?>
                  <?php if(isset($_SESSION['ime'])){ ?>
                      <li><a style="color:black!important" href="logout.php" class="nav-link login">Logout</a></li>
                      <?php }else{?>
                      <li><a style="color:black!important" href="login.php" class="nav-link login">Login</a></li>
                      <?php  }?>
                </ul>
              </nav>
            </div>

            
          </div>
        </div>

      </header>

    <div class="ftco-blocks-cover-1">
      <div class="ftco-cover-1 innerpage overlay" style="background-image: url('images/hero_1.jpg')">
        <div class="container">
          <div class="row align-items-center justify-content-center">
            <div class="col-lg-6 text-center">
              
                <h1 class="mb-4" data-aos="fade-up" data-aos-delay="100">Login</h1>
        <form method="post" style="height: 400px;">
                  <div class="realestate-filter">
                    <div class="container">
                      <br>
                    </div>
                  </div>
      
      <div class="realestate-tabpane pb-5">
        <div class="container tab-content">
           <div class="tab-pane active" id="for-rent" role="tabpanel" aria-labelledby="rent-tab">

             <div class="row">
               <div class="col-md-12 form-group">
                <label for="email">E-mail: </label>
                <br>
                <input type="text" name="email" id="email">
                <br>
                <?php if($emailError2 != ""){ ?><span class="email-error"><?php echo $emailError2;?></span> <?php }?>
               </div>
               </div>
               <div class="row">
               <div class="col-md-12 form-group">
               <label for="pw">Password:</label>
               <br>
                <input type="password" name="pw" id="pw"><br><a style="color: black!important;" href="#" id="show-pw" onclick="showPassword()">Show password</a>
                <br>
                <?php if($pwError2 != ""){ ?><span class="email-error"><?php echo $pwError2;?></span> <?php }?>
               </div>
               
             </div>
                <div class="row">
               <div class="col-md-12">
                 <input type="submit" style="height: 55px;" name="login" class="btn btn-black py-3 btn-block login-btn" value="Login">
               </div>
               <div class="col-md-6 mt-2">
                   <b style="font-size: 12px;">Don't have an account?</b><a style="color:black!important;font-size: 12px;" href="register.php"> Sign up</a>
                </div>
             </div>
             
               </div>
               
             </div>
             

           </div>
           
        </div>
      </div>
    </form>
            </div>
          </div>
        </div>
      </div>
    </div>

    

    

    <footer class="site-footer">
      <div class="container">
        <div class="row">
          <div class="col-md-4">
            <h3 class="text-white h5 mb-3">Subscribe</h3>
            <form action="" class="d-flex">
              <input type="text" class="form-control mr-3" placeholder="Enter your email">
              <input type="submit" class="btn btn-primary text-white" value="Send Now">
            </form>
          </div>
          <div class="col-md-3 ml-auto">
            <h3 class="text-white h5 mb-3">Subscribe</h3>
            <ul class="list-unstyled menu-arrow">
              <li><a href="#">About Us</a></li>
              <li><a href="#">Testimonials</a></li>
              <li><a href="#">Terms of Service</a></li>
              <li><a href="#">Privacy</a></li>
              <li><a href="#">Contact Us</a></li>
            </ul>
          </div>
          <div class="col-md-4">
            <h3 class="text-white h5 mb-3">About</h3>
            <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Ut dolores deserunt, obcaecati fuga quo. Autem explicabo sapiente, maiores.</p>
          </div>
        </div>
        <div class="row pt-5 mt-5 text-center">
          <div class="col-md-12">
            <div class="border-top pt-5">
              <p>
            <!-- Link back to Colorlib can't be removed. Template is licensed under CC BY 3.0. -->
            Copyright &copy;<script>document.write(new Date().getFullYear());</script> All rights reserved | This template is made with <i class="icon-heart text-danger" aria-hidden="true"></i> by <a href="https://colorlib.com" target="_blank" >Colorlib</a>
            <!-- Link back to Colorlib can't be removed. Template is licensed under CC BY 3.0. -->
            </p>
            </div>
          </div>

        </div>
      </div>
    </footer>

    </div>

    <script src="js/jquery-3.3.1.min.js"></script>
    <script src="js/jquery-migrate-3.0.0.js"></script>
    <script src="js/popper.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/owl.carousel.min.js"></script>
    <script src="js/jquery.sticky.js"></script>
    <script src="js/jquery.waypoints.min.js"></script>
    <script src="js/jquery.animateNumber.min.js"></script>
    <script src="js/jquery.fancybox.min.js"></script>
    <script src="js/jquery.stellar.min.js"></script>
    <script src="js/jquery.easing.1.3.js"></script>
    <script src="js/bootstrap-datepicker.min.js"></script>
    <script src="js/aos.js"></script>

    <script src="js/main.js"></script>
    
    <script>
        function showPassword(){
            if(document.getElementById('pw').type === 'password'){
                document.getElementById('pw').type = 'text';
                document.getElementById('show-pw').innerText = 'Hide password';
            }else{
                document.getElementById('pw').type = 'password';
                document.getElementById('show-pw').innerText = 'Show password';
            }
        }
    </script>

  </body>

</html>

