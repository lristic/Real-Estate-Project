<?php
    session_start();

        require "config.php";
        
        try{            
            $connection = new PDO($dsn, $username, $password, $options);
            
            if(isset($_GET['id_stana'])){
            
            
            $sql_delete = "DELETE
                           FROM stanovi
                           WHERE id_stana = :id_stana";
            
            $id_stana = $_GET['id_stana'];
            
            $stmt = $connection->prepare($sql_delete);
            $stmt->bindParam(":id_stana", $id_stana, PDO::PARAM_STR);
            
            $stmt->execute();
            
            $_SESSION['obrisano'] = 1;
                
            //header("Location: deleted.php");
            } 
            
            
            $id = $_SESSION['id'];
            
            $sql = "SELECT *
                    FROM stanovi
                    WHERE id = :id
                    ORDER BY lokacija ASC";


            $statement = $connection->prepare($sql);
            
            $statement->bindParam(":id", $id, PDO::PARAM_STR);

            $statement->execute();
            $result = $statement->fetchAll();
            
        }catch(PDOException $error){
            echo $sql . " " . $error->getMessage();
        }
    
$emailError2 = "";
$pwError2 ="";



?>



<!doctype html>
<html lang="en">

  <head>
    <title>Profile | Restate</title>
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
      <div class="site-section-cover overlay" data-stellar-background-ratio="0.5" style="background-image: url('images/hero_6.jpg')">
        <div class="container">
          <div class="row align-items-center justify-content-center text-center">
            <div class="col-md-7">
             <?php if(isset($_SESSION['ime'])){echo "<h2 style='color:white'>Hello " . $_SESSION['ime'] . "!</h2>";} ?>
              <h1>Manage Your Profile</h1>
              <br>
              <p><a href="#" onclick="scrollDown()" class="btn btn-primary text-white px-4 py-3">View Your Properties</a></p>
              <?php if(isset($_SESSION['obrisano']) && $_SESSION['obrisano'] == 1){
                            echo "<h3 style='color:#178550;text-shadow:2px 2px black;'>Property deleted!</h3>";
              } ?>
            </div>
          </div>
        </div>
      </div>
    </div>
    
    

    

    
    <div class="site-section bg-black block-14" id="your-properties">
      <div class="container">
        <div class="row justify-content-center">
          <div class="col-md-6 text-center">
            <h3 class="heading-29201 text-center text-white">Your Properties</h3>
            
            <p class="mb-5 text-white">View, Delete or Edit Your Properties</p>
          </div>
        </div>
        

        <div class="owl-carousel nonloop-block-14">
         
         <?php foreach($result as $row){ ?>
          <div class="media-38289">
            <a href="property-single.html" class="d-block"><img style="height:223px" src="<?php echo 'images/' . $row['lokacija'] . "/" . $row['id_stana'] . "/" . $row['slika'];?>" alt="Image" class="img-fluid"></a>
            <div class="text">
              <div class="d-flex justify-content-between mb-3">
                <div class="sq d-flex align-items-center"><span class="wrap-icon icon-fullscreen"></span> 
                <span><?php echo $row['kvadratura'] ?></span></div>
                <div class="bed d-flex align-items-center"><span class="wrap-icon icon-bed"></span> <span><?php echo $row['broj_soba']; ?></span></div>
                  <div class="bath d-flex align-items-center"><span><?php if($row['status'] == "P"){echo "For Sale";}else{echo "For Rent";}?></span></div>
              </div>
              <h3 class="mb-3"><a href="#"><?php echo $row['lokacija'];?></a></h3>
              <span class="d-block small address d-flex align-items-center"><span style="font-size: 25px">Price: </span> <span style="font-size: 25px;padding-left: 10px;"><?php echo  $row['cena'] . "$" ?></span></span>
               <br>
               <center>
                <a class="view-single" href="<?php echo "property-single.php?id_stana=" . $row['id_stana'];?>">View</a>
                <a class="view-single" href="<?php echo "submit-property.php?id_stana=" . $row['id_stana'];?>">Edit</a>
                <a class="view-single-delete" href="<?php echo "profile.php?id_stana=" . $row['id_stana'];?>" onclick="return confirm('Are you sure you want to delete this property?');">Delete</a>
                </center>
            </div>
        
          </div>
          
          <?php }?>

        </div>

        
      </div>
    </div>

    <div class="site-section">
      <div class="container">
        <div class="row justify-content-center mb-5">
          <div class="col-md-6 text-center">
            <h3 class="heading-29201 text-center">Our Agents</h3>
            
            <p class="mb-5">Perspiciatis quidem, harum provident, repellat sint officia quos fugit tempora id deleniti.</p>
          </div>
        </div>
        <div class="row">
          <div class="col-md-4 mb-5 mb-md-0">
            <div class="person-29381">
              <div class="media-39912">
                <img src="images/person_1.jpg" alt="Image" class="img-fluid">
              </div>
              <h3><a href="#">Josh Long</a></h3>
              <span class="meta d-block mb-4">4 Properties</span>
              <div class="social-32913">
                <a href="#"><span class="icon-facebook"></span></a>
                <a href="#"><span class="icon-twitter"></span></a>
                <a href="#"><span class="icon-instagram"></span></a>
              </div>
            </div>
          </div>
          <div class="col-md-4 mb-5 mb-md-0">
            <div class="person-29381">
              <div class="media-39912">
                <img src="images/person_3.jpg" alt="Image" class="img-fluid">
              </div>
              <h3><a href="#">Melinda David</a></h3>
              <span class="meta d-block mb-4">10 Properties</span>
              <div class="social-32913">
                <a href="#"><span class="icon-facebook"></span></a>
                <a href="#"><span class="icon-twitter"></span></a>
                <a href="#"><span class="icon-instagram"></span></a>
              </div>
            </div>
          </div>
          <div class="col-md-4 mb-5 mb-md-0">
            <div class="person-29381">
              <div class="media-39912">
                <img src="images/person_2.jpg" alt="Image" class="img-fluid">
              </div>
              <h3><a href="#">Jessica Soft</a></h3>
              <span class="meta d-block mb-4">18 Properties</span>
              <div class="social-32913">
                <a href="#"><span class="icon-facebook"></span></a>
                <a href="#"><span class="icon-twitter"></span></a>
                <a href="#"><span class="icon-instagram"></span></a>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>


    <div class="site-section bg-primary">
      <div class="container block-13">
        <div class="nonloop-block-13 owl-carousel">
          <div class="testimonial-38920 d-flex align-items-start">
            <div class="pic mr-4"><img src="images/person_1.jpg" alt=""></div>
            <div>
              <span class="meta">Business Man</span>
              <h3 class="mb-4">Josh Long</h3>
              <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Nemo sapiente unde pariatur id, hic quos nihil nulla veritatis!</p>
              <div class="mt-4">
                <span class="icon-star text-white"></span>
                <span class="icon-star text-white"></span>
                <span class="icon-star text-white"></span>
                <span class="icon-star text-white"></span>
              </div>
            </div>
          </div>

          <div class="testimonial-38920 d-flex align-items-start">
            <div class="pic mr-4"><img src="images/person_2.jpg" alt=""></div>
            <div>
              <span class="meta">Business Woman</span>
              <h3 class="mb-4">Jean Doe</h3>
              <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Nemo sapiente unde pariatur id, hic quos nihil nulla veritatis!</p>
              <div class="mt-4">
                <span class="icon-star text-white"></span>
                <span class="icon-star text-white"></span>
                <span class="icon-star text-white"></span>
                <span class="icon-star text-white"></span>
              </div>
            </div>
          </div>

          <div class="testimonial-38920 d-flex align-items-start">
            <div class="pic mr-4"><img src="images/person_3.jpg" alt=""></div>
            <div>
              <span class="meta">Business Woman</span>
              <h3 class="mb-4">Jean Doe</h3>
              <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Nemo sapiente unde pariatur id, hic quos nihil nulla veritatis!</p>
              <div class="mt-4">
                <span class="icon-star text-white"></span>
                <span class="icon-star text-white"></span>
                <span class="icon-star text-white"></span>
                <span class="icon-star text-white"></span>
              </div>
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
        var EPPZScrollTo =
{
    /**
     * Helpers.
     */
    documentVerticalScrollPosition: function()
    {
        if (self.pageYOffset) return self.pageYOffset; // Firefox, Chrome, Opera, Safari.
        if (document.documentElement && document.documentElement.scrollTop) return document.documentElement.scrollTop; // Internet Explorer 6 (standards mode).
        if (document.body.scrollTop) return document.body.scrollTop; // Internet Explorer 6, 7 and 8.
        return 0; // None of the above.
    },

    viewportHeight: function()
    { return (document.compatMode === "CSS1Compat") ? document.documentElement.clientHeight : document.body.clientHeight; },

    documentHeight: function()
    { return (document.height !== undefined) ? document.height : document.body.offsetHeight; },

    documentMaximumScrollPosition: function()
    { return this.documentHeight() - this.viewportHeight(); },

    elementVerticalClientPositionById: function(id)
    {
        var element = document.getElementById(id);
        var rectangle = element.getBoundingClientRect();
        return rectangle.top;
    },

    /**
     * Animation tick.
     */
    scrollVerticalTickToPosition: function(currentPosition, targetPosition)
    {
        var filter = 0.2;
        var fps = 60;
        var difference = parseFloat(targetPosition) - parseFloat(currentPosition);

        // Snap, then stop if arrived.
        var arrived = (Math.abs(difference) <= 0.5);
        if (arrived)
        {
            // Apply target.
            scrollTo(0.0, targetPosition);
            return;
        }

        // Filtered position.
        currentPosition = (parseFloat(currentPosition) * (1.0 - filter)) + (parseFloat(targetPosition) * filter);

        // Apply target.
        scrollTo(0.0, Math.round(currentPosition));

        // Schedule next tick.
        setTimeout("EPPZScrollTo.scrollVerticalTickToPosition("+currentPosition+", "+targetPosition+")", (1000 / fps));
    },

    /**
     * For public use.
     *
     * @param id The id of the element to scroll to.
     * @param padding Top padding to apply above element.
     */
    scrollVerticalToElementById: function(id, padding)
    {
        var element = document.getElementById(id);
        if (element == null)
        {
            console.warn('Cannot find element with id \''+id+'\'.');
            return;
        }

        var targetPosition = this.documentVerticalScrollPosition() + this.elementVerticalClientPositionById(id) - padding;
        var currentPosition = this.documentVerticalScrollPosition();

        // Clamp.
        var maximumScrollPosition = this.documentMaximumScrollPosition();
        if (targetPosition > maximumScrollPosition) targetPosition = maximumScrollPosition;

        // Start animation.
        this.scrollVerticalTickToPosition(currentPosition, targetPosition);
    }
};
        
        function scrollDown(){
            
            
                EPPZScrollTo.scrollVerticalToElementById('your-properties', 20);
        }
</script>
    

  </body>

</html>

