<?php 
    session_start();

    try{
        require "config.php";
        
        $connection = new PDO($dsn, $username, $password, $options);
        
        $sql = "SELECT *
                FROM stanovi
                WHERE id_stana = :id_stana";
        
        $sql_ime = "SELECT ime
                    FROM stanovi s
                        JOIN korisnici k
                            ON k.id = s.id
                    WHERE id_stana = :id_stana";
        
        $sql_ostalo = "SELECT *
                    FROM stanovi s
                        JOIN korisnici k
                            ON k.id = s.id
                    WHERE id_stana <> :id_stana
                    AND k.id = :id";
        
        
        $sql_id = "SELECT id
                    FROM stanovi
                    WHERE id_stana = :id_stana";
        
        if(isset($_GET['id_stana'])){
            $id_stana = $_GET['id_stana'];
        }else{
            echo "Greska";
        }
        
        
        $stmt_id = $connection->prepare($sql_id);
        $stmt_id->bindParam(":id_stana", $id_stana, PDO::PARAM_STR);
        
        $stmt_id->execute();
        
        $res_id = $stmt_id->fetchAll();
        
        foreach($res_id as $row_id)
            $id = $row_id['id'];
        
        
        
        $stmt_ostalo = $connection->prepare($sql_ostalo);
        $stmt_ostalo->bindParam(":id_stana", $id_stana, PDO::PARAM_STR);
        $stmt_ostalo->bindParam(":id", $id, PDO::PARAM_STR);
        
        $stmt_ostalo->execute();
        $res_ostalo = $stmt_ostalo->fetchAll();
        
            
        
        $stmt_ime = $connection->prepare($sql_ime);
        $stmt_ime->bindParam(":id_stana", $id_stana, PDO::PARAM_STR);
        
        $stmt_ime->execute();
        
        $res_ime = $stmt_ime->fetchAll();
        
        
        $statement = $connection->prepare($sql);
        $statement->bindParam(":id_stana", $id_stana, PDO::PARAM_STR);
        
        $statement->execute();
        
        $result = $statement->fetchAll();
        
        
                
    }catch(PDOException $error){
        echo $error->getMessage();
    }
?>

<!doctype html>
<html lang="en">

  <head>
    <title><?php foreach($result as $row){echo $row['lokacija'];}?> | Restate </title>
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
                  <li class="active"><a href="property.html" class="nav-link">Property</a></li>
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
<?php foreach($result as $row){?>
    <div class="ftco-blocks-cover-1">
      <div class="site-section-cover overlay" data-stellar-background-ratio="0.5" style="background-image: url('<?php echo "images/" . $row['lokacija'] . "/" . $row['id_stana'] . "/" . $row['slika']; ?>')">
        <div class="container">
          <div class="row align-items-center justify-content-center text-center">
            <div class="col-md-7">
             
              <span class="h4 text-primary mb-4 d-block"><?php echo $row['cena'] . "$"?></span>
              <h1 class="mb-2"><?php echo $row['lokacija'];?></h1>
              <p class="text-center mb-5"><span class="small address d-flex align-items-center justify-content-center">  <span><?php echo $row['opis'];?></span></span></p>
            
                <div class="d-flex media-38289 justify-content-around mb-5">
                  <div class="sq d-flex align-items-center" style="font-size: 20px!important;"> <span><?php echo $row['kvadratura'] . "m²";?></span></div>
                  <div class="bed d-flex align-items-center" style="font-size: 20px!important;"> No. of rooms: <span><?php echo $row['broj_soba'] ?></span></div>
                  <div class="bath d-flex align-items-center" style="font-size: 20px!important;"> <span><?php if($row['status'] == "P"){echo "For Sale";}else{echo "For Rent";} ?></span></div>
                </div>
              <p><a href="#" class="btn btn-primary text-white px-4 py-3">Contact Agent</a></p>
                  <?php }?>
            </div>
          </div>
        </div>
      </div>
    </div>
    

    
    <div class="site-section">
      <div class="container">
        <div class="row">
          <div class="col-md-8">
            <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Non alias labore similique, laboriosam consequuntur tempora, quas accusantium voluptatibus eius, maiores, minima! Ipsam tempore ex qui voluptatum quo voluptas! Incidunt, pariatur.</p>
            <p>Dolorem quaerat tenetur corporis praesentium, soluta debitis culpa asperiores, minus delectus quibusdam amet recusandae aliquam voluptatibus dicta quis, facere tempora eum placeat repellendus maxime nesciunt voluptates totam sapiente commodi. Tenetur.</p>
            <p>Labore natus ullam suscipit distinctio debitis voluptas minima ipsam. Odit, reprehenderit minima distinctio, dolorum ipsam velit, minus labore eum commodi quia quae doloribus impedit blanditiis architecto fugiat delectus provident quas.</p>
            <p>Asperiores temporibus adipisci dolor quasi assumenda est, itaque corrupti, neque facilis beatae natus voluptatibus aperiam mollitia esse ipsam! Quam perferendis facere sed beatae repudiandae rerum laudantium necessitatibus. Incidunt, dolorem, officiis?</p>
            <p>Mollitia impedit omnis ullam earum est, quaerat consectetur voluptates quia, dolore asperiores ipsum eligendi quae iste, facere porro debitis nostrum obcaecati culpa eius perspiciatis alias distinctio. Perferendis, magnam mollitia fuga.</p>
            <p><a href="#" class="btn btn-primary text-white">Contact Agent</a></p>
          </div>
          <div class="col-md-3 ml-auto">
            <h3 class="mb-5">Agent</h3>
            <div class="person-29381">
              <div class="media-39912">
                <img src="images/person_1.jpg" alt="Image" class="img-fluid">
              </div>
              <?php foreach($res_ime as $row_ime){?>
              <h3><?php echo $row_ime['ime'] ?></h3>
              <span class="meta d-block mb-4"><?php foreach($res_ostalo as $row_ostalo){echo $row_ostalo['email']; break;} ?></span>
              <div class="social-32913">
                <a href="#"><span class="icon-facebook"></span></a>
                <a href="#"><span class="icon-twitter"></span></a>
                <a href="#"><span class="icon-instagram"></span></a>
              </div>
              <?php } ?>
            </div>
          </div>
        </div>
      </div>
    </div>

    
    <?php if($stmt_ostalo->rowCount() > 0){?>
    <div class="site-section bg-black">
     
      <div class="container">


        <div class="row justify-content-center">
          <div class="col-md-6 text-center">
            <h3 class="heading-29201 text-center text-white">More Properties From Agent</h3>
            <br>
            
          </div>
        </div>
        
        
        
        <div class="row">
        <?php 
                    foreach($res_ostalo as $row_ostalo){ ?>
          <div class="col-md-4 mb-5">
               
            <div class="media-38289">
              <a href="property-single.html" class="d-block"><img src='<?php echo "images/" . $row_ostalo['lokacija'] . "/" . $row_ostalo['id_stana'] . "/" . $row_ostalo['slika']; ?>' alt="Image" class="img-fluid"></a>
              <div class="text">
                <div class="d-flex justify-content-between mb-3">
                  <div class="sq d-flex align-items-center"><span class="wrap-icon icon-fullscreen"></span> <span><?php echo $row_ostalo['kvadratura'] . "m²"; ?></span></div>
                  <div class="bed d-flex align-items-center">Rooms: <span><?php echo $row_ostalo['broj_soba']; ?></span></div>
                  <div class="bath d-flex align-items-center"><span><?php if($row_ostalo['status'] == "prodaja"){echo "For Sale";}else{echo "For Rent";} ?></span></div>
                </div>
                <h3 class="mb-3"><a href="#"><?php echo $row_ostalo['lokacija'] ?></a></h3>
                <span class="d-block small address d-flex align-items-center"><span style="font-size: 20px;"><?php echo $row_ostalo['cena'] . "$"; ?></span></span>
                <br>
                <center><a class="view-single" href=<?php echo "property-single.php?id_stana=" . $row['id_stana']?>>View property</a></center></center>
              </div>
            </div>
            
          </div>
          <?php }}?>
          

          
          
          
        </div>
      </div>
    </div>
    
    <form action="index-res.php" method="post">
      <div class="realestate-filter" style="background-color: #90bede;border-top: 2px solid #90bede">
        <div class="container">
          <br>
        </div>
      </div>
      
      <div class="realestate-tabpane pb-5" style="background-color: #90bede;color:black;">
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
                 <input  type="submit" name="submit" class="btn btn-black py-3 btn-block" value="Search">
               </div>
             </div>
             </div>
             

           </div>
           
        </div>
      </div>
    </form>


    <div class="site-section bg-primary" >
      <div class="container block-13" >
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
            <div class="pic mr-4"><img src="images/person_1.jpg" alt=""></div>
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
            <div class="pic mr-4"><img src="images/person_1.jpg" alt=""></div>
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

