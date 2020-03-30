<?php
    session_start();

    $generalError = "";
    $imeError = "";
    $emailError = "";
    $pwError = "";
    $telefonError = "";

    if(isset($_POST['register'])){
        try{
            
            if(isset($_POST['ime']) && $_POST['ime'] != ""){
                if(preg_match("/[A-Z][a-z]+/", $_POST['ime'])){
                    $ime = $_POST['ime'];
                }else{
                    $imeError = "<p style='font-size:12px;color: #c02739;margin-bottom:0;'>Name must begin with a capital letter and consist only of letters.</p>";
                }
            }else{
                $imeError = "<p style='font-size:12px;color: #c02739;margin-bottom: 0;'>Please enter your name.</p>";
            }

            if(isset($_POST['email']) && $_POST['email'] != ""){
                if(preg_match("/[a-z0-9]+@(yahoo.com|gmail.com)/", $_POST['email'])){
                    $email = $_POST['email'];
                }else{
                    $emailError = "<p style='font-size:12px;color: #c02739;margin-bottom:0;'>Incorrect e-mail pattern, use yahoo.com or gmail.com.</p>";
                    
                }
            }else{
                $emailError = "<p style='font-size:12px;color: #c02739;margin-bottom:0;'>Please enter your e-mail.</p>";
                
            }

            if(isset($_POST['pw']) && $_POST['pw'] != ""){
                $velikoSlovo = preg_match("/[A-Z]/", $_POST['pw']);
                $maloSlovo = preg_match("/[a-z]/", $_POST['pw']);
                $broj = preg_match("/[0-9]/", $_POST['pw']);
                if(!$velikoSlovo || !$maloSlovo || !$broj || strlen($_POST['pw']) < 8){
                    $pwError = "<p style='font-size:12px;color: #c02739;margin-bottom:0;'>Password must contain " . 
                                "at least 8 characters" . ", " . 
                                "at least one upper case letter" . ", " . 
                                "at least one lower case letter" . " and " . 
                                "at least one number.</p>";
                    
                }else{
                    $pw = $_POST['pw'];
                }
            }else{
                $pwError = "<p style='font-size:12px;color: #c02739;margin-bottom:0;'>Please enter your password.</p>";
                
            }
            
            if(isset($_POST['telefon']) && $_POST['telefon'] != ""){
                $provera = preg_match("/\+38164[0-9]{7}|\+3816[0,1,2,3,5,6][0-9]{6,7}/", $_POST['telefon']);
                if(!$provera){
                    $telefonError = "<p style='font-size:12px;color: #c02739;margin-bottom:0;'>Phone pattern must be +381x...</p>";
                }else{
                    $telefon = $_POST['telefon'];
                }
            }else{
                $telefonError = "<p style='font-size:12px;color: #c02739;margin-bottom:0;'>Please enter your phone number.</p>";
            }
            
            if($emailError != "" || $pwError != "" || $imeError != "" || $telefonError != ""){
                throw new PDOException();
            }else{
            
            
                require "config.php";
                

                $connection = new PDO($dsn, $username, $password, $options);
            
            
                $sql_provera = "SELECT *
                                FROM korisnici
                                WHERE email = :email";
            
            
                $statement_provera = $connection->prepare($sql_provera);
                $statement_provera->bindParam(":email", $email, PDO::PARAM_STR);
                $statement_provera->execute();
            
                if($statement_provera->rowCount() > 0){
                    $generalError = "User with e-mail is already registered.";
                    throw new PDOException();
                }else{
                    $sql = "INSERT INTO korisnici (ime, email, password, telefon) VALUES (:ime,:email, :pw, :telefon)";
                
                    $statement = $connection->prepare($sql);
                    $statement->bindParam(":ime", $ime, PDO::PARAM_STR);
                    $statement->bindParam(":email", $email, PDO::PARAM_STR);
                    $statement->bindParam(":pw", $pw, PDO::PARAM_STR);
                    $statement->bindParam(":telefon", $telefon, PDO::PARAM_STR);
                    $statement->execute();
            
                }
            
            }
            
            

        }catch(PDOException $error){
            echo $error->getMessage();
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
                <a href="index.php">Restate</a>
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
                  <?php if(isset($_SESSION['ime'])){?>
                  <div style="border-radius:10px;" class="btn-group">
                  <button style="background-color:#191919;border:black;" type="button" class="btn btn-secondary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <?php echo $_SESSION['ime'];?>
                  </button>
                  <div style="background-color: black;" class="dropdown-menu dropdown-menu-right">
                    <button class="dropdown-item" type="button"><a href="profile.php?id="<?php echo $_SESSION['id'];?>>View your profile</a></button>
                    <button class="dropdown-item" type="button"><a href="submit-property.php">Submit property</a></button>
                    <button class="dropdown-item" type="button"><a href="logout.php">Logout</a></button>
                  </div>
                    </div>
                    <?php }else{?>
                    <li><a style="color:black!important" href="login.php" class="nav-link login">Login</a></li>
                    <?php }?>
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
              
                <h1 class="mb-4" data-aos="fade-up" data-aos-delay="100">Sign Up</h1>
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
                   <div class="col-md-12 form-group" style="margin-bottom: 0;">
                       <label style="margin-bottom: 0;" for="ime">Name:</label>
                        <br>
                        <input type="text" name="ime" id="ime">
                        <br>
                        <?php if($imeError != ""){ ?><span class="email-error"><?php echo $imeError;?></span> <?php }?>
                   </div>
               </div>              
              
              <div class="row">
               <div class="col-md-12 form-group" style="margin-bottom: 0;">
                    <label style="margin-bottom: 0;" for="email">E-mail: </label>
                    <br>
                    <input type="text" name="email" id="email">
                    <span id="mejlpopup"></span>
                    <br>
                    <?php if($emailError != ""){ ?><span class="email-error"><?php echo $emailError;?></span> <?php }?>
                </div>
               </div>
               
               
               <div class="row">
                <div class="col-md-12 form-group" style="margin-bottom: 0;">
                   <label style="margin-bottom: 0;" for="pw">Password:</label>
                   <br>
                    <input type="password" name="pw" id="pw">
                    <span id="pwpopup"></span>
                    <?php if($pwError != ""){ ?><span class="email-error"><?php echo $pwError;?></span> <?php }else{echo "<br>";}?>
                    <a style="color: black!important;" href="#" id="show-pw" onclick="showPassword()">Show password</a>
                    
               </div>
             </div>
              
              <div class="row">
                   <div class="col-md-12 form-group">
                       <label style="margin-bottom: 0;" for="phone">Phone number: </label>
                        <br>
                        <input type="text" name="telefon" id="telefon">
                        <br>
                        <?php if($telefonError != ""){ ?><span class="email-error"><?php echo $telefonError;?></span> <?php }?>
                   </div>
               </div>
               
               
                <div class="row">
               <div class="col-md-12">
                 <input type="submit" name="register" class="btn btn-black py-3 btn-block login-btn" value="Sign Up">
               </div>
               <div class="col-md-6 mt-2">
                   <b style="font-size: 12px;">Already have an account?</b><a style="color:black!important;font-size: 12px;" href="login.php"> Sign in</a>
                </div>
             </div>
             
             <?php 
                    if(isset($_POST['register'])){
                            if($generalError == "" && $imeError == "" && $pwError == "" && $emailError == "" && $telefonError == "")
                                echo "<p style='color:#178550;font-size:20px;'>Registered sucessfully! <a style='color: black!important;' href='login.php'>Sign in</a>.</p>";
                            else
                                echo "<p style='color:#c02739;font-size:20px;'>" . $generalError . "</p>";
                    }
             ?>
             
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
        
        document.addEventListener("click", function(evt){
            var poljeSifra = document.getElementById("pw"),
                poljeMejl = document.getElementById("email"),
                poljeTelefon = document.getElementById("telefon"),
                targetEl = evt.target;
            
            do{
                if(targetEl == poljeSifra){
                    
                    document.getElementById("pwpopup").innerHTML = "<br>" + "* vise od 8 karaktera <br>" + 
                                                                    "* bar jedno veliko slovo <br>" +
                                                                    "* bar jedno malo slovo <br>" + 
                                                                    "* bar jedan broj";
                    
                    document.getElementById("mejlpopup").innerHTML = "";
                    
                    return;
                }else if(targetEl == poljeMejl){
                    document.getElementById("mejlpopup").innerHTML = "<br>* ili yahoo.com ili gmail.com";
                    document.getElementById("pwpopup").innerHTML = "";
                    return;
                }
                
                targetEl = targetEl.parentNode;
                
            }while(targetEl);
            
            document.getElementById("pwpopup").innerHTML = "";
            document.getElementById("mejlpopup").innerHTML = "";
            
            
                
            
        });
    
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

