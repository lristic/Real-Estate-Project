<?php
    session_start();

    $cenaError = "";
    $kvadraturaError = "";
    $generalError = "";

    if(isset($_POST['submit'])){
        require "config.php";
        
        try{            
            $connection = new PDO($dsn, $username, $password, $options);
            
            $sql = "SELECT *
                    FROM stanovi
                    WHERE";

            if(isset($_POST['lokacija']) && $_POST['lokacija'] != ""){
                $lokacija = $_POST['lokacija'];
            }else{
                $lokacija = "";
            }
            if(isset($_POST['cena']) && $_POST['cena'] != ""){
                $cena = $_POST['cena'];
            }else{
                $cena = "";
            }
            if(isset($_POST['kvadratura']) && $_POST['kvadratura'] != ""){
                $kvadratura = $_POST['kvadratura'];
            }else{
                $kvadratura = "";
            }
            if(isset($_POST['status']) && $_POST['status'] != ""){
                if($_POST['status'] === "prodaja"){
                    $status = "P";
                }else{
                    $status = "I";
                }
            }else{
                $status = "";
            }
            if(isset($_POST['brsoba']) && $_POST['brsoba'] != ""){
                $brsoba = $_POST['brsoba'];
            }else{
                $brsoba = "";
            }

            if($lokacija == "" && $cena == "" && $kvadratura == "" && $status == "" && $brsoba == ""){
                $generalError = "Choose at least one search parameter.";
                $sql = "";
                throw new PDOException();
            }

            
            if($lokacija != ""){
                $sql = $sql . " lokacija = :lokacija";
                
            }
            
            if($status != ""){
                if($lokacija != ""){
                    $sql = $sql . " AND status = :status";
                }else{
                    $sql = $sql ." status = :status";
                }
            }
                    
            if($brsoba != ""){
                if($lokacija != "" || $status != ""){
                    $sql = $sql . " AND broj_soba < :brsoba";
                }else{
                    $sql = $sql . " broj_soba >= :brsoba";
                }
            }
            
            
            if($kvadratura != ""){
                if($kvadratura < 0){
                    $kvadraturaError = "Kvadratura mora biti veca od 0m2.";
                }else{
                    $kvadratura = $_POST['kvadratura'];
                }
                if($lokacija != "" || $status != "" || $brsoba != ""){
                    $sql = $sql . " AND kvadratura < :kvadratura";
                }else{
                    $sql = $sql . " kvadratura < :kvadratura";
                }
            }
            
            
            if($cena != ""){
                if($cena < 0){
                    $cenaError = "Cena mora biti veca od 0e.";
                }else{
                    $cena = $_POST['cena'];
                }
                if($lokacija != "" || $kvadratura != "" || $status != "" || $brsoba != ""){
                    $sql = $sql . " AND cena <= :cena";
                }else{
                    $sql = $sql . " cena <= :cena";
                }
            }
            


            $sql = $sql . " ORDER BY cena ASC";
            
            $statement = $connection->prepare($sql);
        
                

            if($lokacija != ""){
                $statement->bindParam(':lokacija', $lokacija, PDO::PARAM_STR);
            }
            if($cena != ""){
                $statement->bindParam(':cena', $cena, PDO::PARAM_STR);
            }
            if($kvadratura != ""){
                $statement->bindParam(':kvadratura', $kvadratura, PDO::PARAM_STR);
            }
            if($status != ""){
                $statement->bindParam(':status', $status, PDO::PARAM_STR);
            }
            if($brsoba != ""){
                $statement->bindParam(':brsoba', $brsoba, PDO::PARAM_STR);
            }

            $statement->execute();
            $result = $statement->fetchAll();
            
            
            
            
        }catch(PDOException $error){
            echo $sql . " " . $error->getMessage();
        }
    }


?>


<!doctype html>
<html lang="en">

  <head>
    <title>Search Results | Restate</title>
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
                  <li class="active"><a href="index.php" class="nav-link">Home</a></li>
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
      <div class="site-section-cover overlay" data-stellar-background-ratio="0.5" style="background-image: url('images/hero_2.jpg')">
        <div class="container">
          <div class="row align-items-center justify-content-center text-center">
            <div class="col-md-7">
              <h1>Results:</h1>
              <br>
              <?php if($generalError == ""){
                        if($statement->rowCount() > 0){
                            echo "<p style='color:white;'>Your search returned " . $statement->rowCount() . " results." . "<br>" . "See the properties below.</p>";
                        }else{
                            echo "<p style='color:#c02739'>Your search returned 0 results." . "<br>" . "Please choose different filters.</p>";
                }}else{echo "<p style='color:#c02739;font-size: 25px;'>" . $generalError . "</p>";}?>
            </div>
          </div>
        </div>
      </div>
    </div>
    <?php if($generalError == ""){?>
    <section style="background-color: #90bede;">
    
    <br>
    <hr style="background-color: black;width:80%;height:.5px;margin-bottom:0;">
    <div class="container">
      <br>
       <h1 style="color:black;">Properties:</h1>
        <div class="row">
           <?php if($statement->rowCount() > 0){foreach($result as $row){ ?>
            <div class="col-md-4 property">
               <center><img style="height: 150px;" src="<?php echo "images/" . $row['lokacija'] . "/" . $row['id_stana'] . "/" . $row['slika']; ?>"></center>
                <h3 style="text-align:center;"><?php echo $row['lokacija']; ?></h3>
                <h4>Price: <?php echo $row['cena'] . "$" ?></h4>
                <h4 >Rooms: <?php echo $row['broj_soba'];?></h4>
                <h4>Size: <?php echo $row['kvadratura'] . "m²";?></h4>
                <center><a href=<?php echo "property-single.php?id_stana=" . $row['id_stana']?>>View property</a></center>
                <br>
            </div>
                <?php }}else{
                    echo " <h4 style='color: #c02739;margin-left: 30px;'> > No properties found.</h4>";
                } ?>
        </div>
                
    </div>
    
    <br>
    
<hr style="background-color: black;width:80%;height:.5px;margin-bottom:0;">
  </section>
   <?php } ?>
   
    <form action="index-res.php" method="post">
      <div class="realestate-filter" style="background-color: black;border-top: 2px solid #90bede">
        <div class="container">
          <br>
        </div>
      </div>
      
      <div class="realestate-tabpane pb-5" style="background-color: black;color:white;">
       <br>
        <div class="container tab-content">
           <div class="tab-pane active" id="for-rent" role="tabpanel" aria-labelledby="rent-tab">

             <div class="row">
               <div class="col-md-6 form-group">
               
                <label for="lokacija">Location: </label>
                 <?php 
                    require "config.php";
        
                    $connection = new PDO($dsn, $username, $password, $options);
                    $sql = "SELECT DISTINCT lokacija FROM stanovi";
                    $res = $connection->query($sql);

                    echo "<select class='form-control' style='width:100%' name='lokacija' id='lokacija'>";
                    echo "<option selected disabled>-- Choose a location -- </option>";
        
                    foreach ($res as $row){
                        echo "<option value= '" . $row['lokacija'] . "'>" . $row['lokacija'] . "</option>";
                    }

                    echo "</select>";        
                ?>
               </div>
               <div class="col-md-6 form-group">
                <label for="status">Status:</label>
                 <select name="status" id="status" class="form-control w-100">
                   <option value="">-- Choose property status --</option>
                   <option value="prodaja">For Sale</option>
                   <option value="izdavanje">For Rent</option>
                   
                 </select>
               </div>
               
             </div>

             <div class="row">
              
               <div class="col-md-4 form-group">
                <label for="rooms">Minimum Rooms:</label>
                 <select name="brsoba" id="brsoba" class="form-control w-100">
                   <option selected disabled>-- Choose Min No. of Rooms --</option>
                   <option  value="0.5">0.5</option>
                   <option value="1">1</option>
                   <option value="2">2</option>
                   <option value="3">3</option>
                 </select>
               </div>
               <div class="col-md-4 form-group">
                    <label for="size">Maximum Size: </label>
                     <select name="kvadratura" id="kvadratura" class="form-control w-100">
                       <option selected disabled>-- Choose Max Size --</option>
                       <option value="100">100m²</option>
                       <option value="200">200m²</option>
                       <option value="300">300m²</option>
                     </select>
                 </div>
                   <div class="col-md-4 form-group">
                    <label for="price">Maximum Price:</label>
                     <select name="cena" id="cena" class="form-control w-100">
                       <option selected disabled>-- Choose Max Price --</option>
                       <option value="100">100$</option>
                       <option value="200">200$</option>
                       <option value="300">300$</option>
                       <option value="400">400$</option>
                       <option value="500">500$+</option>
                     </select>
                   </div>
                 </div>
               </div>
               <div class="row">
               <div class="col-md-4">
                 <input style="background-color: #90bede;color:black;" type="submit" name="submit" class="btn btn-black py-3 btn-block" value="Search">
               </div>
             </div>
             </div>
             

           </div>
           
        </div>
      </div>
    </form>

    <div class="site-section">
      <div class="container">
        <div class="row align-items-stretch">
          <div class="col-lg-6">
            <div class="h-100 p-5 bg-black">
              <div class="row">
                <div class="col-md-6 text-center mb-4">
                  <div class="service-38201">
                    <span class="flaticon-house-2"></span>
                    <h3>Estate Insurance</h3>
                    <p>Estate Management</p>
                  </div>
                </div>
                <div class="col-md-6 text-center mb-4">
                  <div class="service-38201">
                    <span class="flaticon-bathtub"></span>
                    <h3>Elegant Bathtub</h3>
                    <p>Estate Management</p>
                  </div>
                </div>
                <div class="col-md-6 text-center mb-4">
                  <div class="service-38201">
                    <span class="flaticon-house-1"></span>
                    <h3>Fresh Air</h3>
                    <p>Estate Management</p>
                  </div>
                </div>
                <div class="col-md-6 text-center mb-4">
                  <div class="service-38201">
                    <span class="flaticon-calculator"></span>
                    <h3>Estate Calculator</h3>
                    <p>Estate Management</p>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="col-lg-5 ml-auto">
            <h3 class="heading-29201">About Us</h3>
            
            <p class="mb-5">Perspiciatis quidem harum provident repellat sint.</p>

            <div class="service-39290 d-flex align-items-start mb-5">
              <div class="media-icon mr-4">
                <span class="flaticon-house-1"></span>
              </div>
              <div class="text">
                <h3>Mission</h3>
                <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Illo rem sit dolorem saepe ex voluptatum nam nulla et!</p>
              </div>
            </div>

            <div class="service-39290 d-flex align-items-start mb-5">
              <div class="media-icon  mr-4">
                <span class="flaticon-calculator"></span>
              </div>
              <div class="text">
                <h3>Vission</h3>
                <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Illo rem sit dolorem saepe ex voluptatum nam nulla et!</p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="site-section bg-black block-14">
      <div class="container">
        <div class="row justify-content-center">
          <div class="col-md-6 text-center">
            <h3 class="heading-29201 text-center text-white">Latest Properties</h3>
            
            <p class="mb-5 text-white">Perspiciatis quidem, harum provident, repellat sint officia quos fugit tempora id deleniti.</p>
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

  </body>

</html>

