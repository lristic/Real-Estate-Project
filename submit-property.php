<?php
    session_start();
    
    $lokacijaError= "";
    $cenaError = "";
    $opisError = "";
    $statusError = "";
    $kvadraturaError = "";
    $brsobaError = "";
    $slikaError = "";
    $usloviError = "";
    $uspeh = "";

    if(isset($_POST['submit'])){
        try{
            require "config.php";
            $connection = new PDO($dsn, $username, $password, $options);
            
            if(isset($_POST['lokacija']) && $_POST['lokacija'] != ""){
                $lokacija = $_POST['lokacija'];
            }else{
                $lokacijaError = "Morate uneti lokaciju.";
            }
            
            if(isset($_POST['cena']) && $_POST['cena'] != ""){
                if($_POST['cena'] < 0){
                    $cenaError = "Cena mora biti veca od 0.";
                }else{
                    $cena = $_POST['cena'];
                }
            }else{
                $cenaError = "Morate uneti cenu.";
            }
            
            
            
            if(isset($_POST['opis']) && $_POST['opis'] != ""){
                $opis = $_POST['opis'];
            }else{
                $opisError = "Morate uneti opis.";
            }
            
            if(isset($_POST['status']) && $_POST['status'] != ""){
                if($_POST['status'] === "prodaja")
                    $status = "P";
                else
                    $status = "I";
            }else{
                $statusError = "Morate uneti status nekretnine.";
            }
            
            if(isset($_POST['kvadratura']) && $_POST['kvadratura'] != ""){
                if($_POST['kvadratura'] < 0){
                    $kvadraturaError = "Kvadratura mora biti veca od 0.";
                }else{
                    $kvadratura = $_POST['kvadratura'];
                }
            }else{
                $kvadraturaError = "Morate uneti kvadraturu.";
            }
            
            if(isset($_POST['brsoba']) && $_POST['brsoba'] != ""){
                $brsoba = $_POST['brsoba'];
            }else{
                $brsobaError = "Morate uneti broj soba.";
            }
            
            if(isset($_FILES['slika']['name']) && $_FILES['slika']['name'] != ""){
                            
                
                $tipfajla = strtolower(pathinfo($_FILES['slika']['name'], PATHINFO_EXTENSION));
                
                $ekstenzije = array("jpg", "png", "jpeg");
                
                if(in_array($tipfajla, $ekstenzije)){
                    $slika = $_FILES['slika']['name'];
                }else{
                    $slikaError = "Neispravan tip slike, ekstenzija mora biti jpg, jpeg ili png.";
                }
            }else{
                $slikaError = "Morate uneti sliku.";
            }
            
            
            if($lokacijaError != "" ||
               $cenaError != "" ||
               $kvadraturaError != "" ||
               $opisError != "" ||
               $brsobaError != "" ||
               $statusError != "" ||
               $slikaError != ""){
                throw new PDOException();
            }else{
                $dir = "images/";
                
                if(!file_exists($dir . $lokacija)){
                    mkdir($dir . $lokacija, 0777, true);    
                }
                
        
                $sql_broji = "SELECT MAX(id_stana)
                                FROM stanovi";
                
                
                
                $stmt_broji = $connection->prepare($sql_broji);
                $stmt_broji->execute();
                                                
                $novi_id = $stmt_broji->fetchColumn() + 1;
                            
                
                mkdir($dir . $lokacija . "/" . $novi_id, 0777, true);
                
                move_uploaded_file($_FILES['slika']['tmp_name'], $dir . $lokacija . "/" . $novi_id . "/" . $slika);
                
                $sql = "INSERT INTO stanovi
                        (id, lokacija, cena, kvadratura, slika, status, broj_soba, opis)
                        VALUES
                        (:id, :lokacija, :cena, :kvadratura, :slika, :status, :broj_soba, :opis)";
                
                $id = $_SESSION['id'];
                
                
                
                $statement = $connection->prepare($sql);
                $statement->bindParam(":id", $id, PDO::PARAM_STR);
                $statement->bindParam(":lokacija", $lokacija, PDO::PARAM_STR);
                $statement->bindParam(":cena", $cena, PDO::PARAM_STR);
                $statement->bindParam(":kvadratura", $kvadratura, PDO::PARAM_STR);
                $statement->bindParam(":slika", $slika, PDO::PARAM_STR);
                $statement->bindParam(":status", $status, PDO::PARAM_STR);
                $statement->bindParam(":broj_soba", $brsoba, PDO::PARAM_STR);
                $statement->bindParam(":opis", $opis, PDO::PARAM_STR);
                
                $statement->execute();
                
                if($statement->rowCount() > 0)
                    $uspeh = "Nekretnina uspesno dodata!";
                else
                    $uspeh = "Ne radi retarde";
                
            }
            
            
            
        }catch(PDOException $error){
            echo $error->getMessage();
        }
    }

    if(isset($_POST['update'])){
        try{
            require "config.php";
            $connection = new PDO($dsn, $username, $password, $options);
            
            if(isset($_POST['lokacija']) && $_POST['lokacija'] != ""){
                $lokacija = $_POST['lokacija'];
            }else{
                $lokacijaError = "Morate uneti lokaciju.";
            }
            
            if(isset($_POST['cena']) && $_POST['cena'] != ""){
                if($_POST['cena'] < 0){
                    $cenaError = "Cena mora biti veca od 0.";
                }else{
                    $cena = $_POST['cena'];
                }
            }else{
                $cenaError = "Morate uneti cenu.";
            }
            
            
            
            if(isset($_POST['opis']) && $_POST['opis'] != ""){
                $opis = $_POST['opis'];
            }else{
                $opisError = "Morate uneti opis.";
            }
            
            if(isset($_POST['status']) && $_POST['status'] != ""){
                if($_POST['status'] === "prodaja")
                    $status = "P";
                else
                    $status = "I";
            }else{
                $statusError = "Morate uneti status nekretnine.";
            }
            
            if(isset($_POST['kvadratura']) && $_POST['kvadratura'] != ""){
                if($_POST['kvadratura'] < 0){
                    $kvadraturaError = "Kvadratura mora biti veca od 0.";
                }else{
                    $kvadratura = $_POST['kvadratura'];
                }
            }else{
                $kvadraturaError = "Morate uneti kvadraturu.";
            }
            
            if(isset($_POST['brsoba']) && $_POST['brsoba'] != ""){
                $brsoba = $_POST['brsoba'];
            }else{
                $brsobaError = "Morate uneti broj soba.";
            }
            /*
            if(isset($_FILES['slika']['name']) && $_FILES['slika']['name'] != ""){
                            
                
                $tipfajla = strtolower(pathinfo($_FILES['slika']['name'], PATHINFO_EXTENSION));
                
                $ekstenzije = array("jpg", "png", "jpeg");
                
                if(in_array($tipfajla, $ekstenzije)){
                    $slika = $_FILES['slika']['name'];
                }else{
                    $slikaError = "Neispravan tip slike, ekstenzija mora biti jpg, jpeg ili png.";
                }
            }else{
                $slikaError = "Morate uneti sliku.";
            }*/
            
            
            if($lokacijaError != "" ||
               $cenaError != "" ||
               $kvadraturaError != "" ||
               $opisError != "" ||
               $brsobaError != "" ||
               $statusError != ""){
                throw new PDOException();
            }else{
                /*$dir = "images/";
                
                if(!file_exists($dir . $lokacija)){
                    mkdir($dir . $lokacija, 0777, true);    
                }
                */
        
                $sql_broji = "SELECT MAX(id_stana)
                                FROM stanovi";
                
                                
                $stmt_broji = $connection->prepare($sql_broji);
                $stmt_broji->execute();
                                                
                $novi_id = $stmt_broji->fetchColumn() + 1;
                
                /*
                if(!file_exists($dir . $lokacija . "/" . $novi_id)){
                    mkdir($dir . $lokacija . "/" . $novi_id, 0777, true);
                }
                
                */
                //move_uploaded_file($_FILES['slika']['tmp_name'], $dir . $lokacija . "/" . $novi_id . "/" . $slika);
                
                $sql = "UPDATE stanovi
                        SET lokacija = :lokacija,
                            cena = :cena,
                            kvadratura = :kvadratura,
                            status = :status,
                            broj_soba = :broj_soba,
                            opis = :opis
                        WHERE id_stana = :id_stana";
                
                $id_stana = $_GET['id_stana'];
                
                
                
                $statement = $connection->prepare($sql);
                $statement->bindParam(":lokacija", $lokacija, PDO::PARAM_STR);
                $statement->bindParam(":cena", $cena, PDO::PARAM_STR);
                $statement->bindParam(":kvadratura", $kvadratura, PDO::PARAM_STR);
                //$statement->bindParam(":slika", $slika, PDO::PARAM_STR);
                $statement->bindParam(":status", $status, PDO::PARAM_STR);
                $statement->bindParam(":broj_soba", $brsoba, PDO::PARAM_STR);
                $statement->bindParam(":opis", $opis, PDO::PARAM_STR);
                $statement->bindParam(":id_stana", $id_stana, PDO::PARAM_STR);
                
                $statement->execute();
                
                if($statement->rowCount() > 0)
                    $uspeh = "Nekretnina uspesno azurirana!";
                
                
                
                
                
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
            <div class="col-lg-12 text-center">
              
              <?php if(isset($_GET['id_stana'])){
                    require "config.php";
                                        $sql_update = "SELECT *
                                                       FROM stanovi
                                                       WHERE id_stana = :id_stana";
                                        $id_stana = $_GET['id_stana'];
    
                                        
                                        $connection = new PDO($dsn, $username, $password, $options);
    
                                        $stmt_update = $connection->prepare($sql_update);
                                        $stmt_update->bindParam(":id_stana", $id_stana, PDO::PARAM_STR);
                                        $stmt_update->execute();
                                        
                                        $res_update = $stmt_update->fetchAll();
                ?>
                <h1 class="mb-4" data-aos="fade-up" data-aos-delay="100"><br>Update Property</h1>
              <?php }else{?>
                <h1 class="mb-4" data-aos="fade-up" data-aos-delay="100"><br>Submit Property</h1>
              <?php } ?>
              
<!------------------------------------------------ FORM ------------------------------------------------>
    
    <form method="post" enctype="multipart/form-data" style="height: 500px;">
                  <div class="realestate-filter">
                    <div class="container">
                      <br>
                      
                    </div>
                  </div>
      
      <?php
            if(isset($_GET['id_stana'])){ 
               foreach($res_update as $row_update)
                                        
      ?>
      
<!------------------------------------------------ UPDATE ------------------------------------------------>
      
      <div class="realestate-tabpane pb-5">
        <div class="container tab-content">
           <div class="tab-pane active" id="for-rent" role="tabpanel" aria-labelledby="rent-tab">

             <div class="row">
                   <div class="col-md-6 form-group" style="margin-bottom: 0;">
                       <label>Location </label>
                            <input style="<?php if(isset($_GET['id_stana'])) echo "font-weight:600;" ?>" name="lokacija" type="text" class="form-control" placeholder="Npr. Vozdovac" value="<?php echo $row_update['lokacija']; ?>">
                            <?php 
                                if(isset($_POST['update'])){
                                    if($lokacijaError != ""){
                                        echo "<p style='color:red;'>" . $lokacijaError . "</p>"; 
                                    }
                                }
                            ?>
                   </div>
                   <div class="col-md-6 form-group" style="margin-bottom: 0;">
                    <label>Cena <small>(obavezno)</small></label>
                    <input style="<?php if(isset($_GET['id_stana'])) echo "font-weight:600;" ?>" name="cena" type="text" class="form-control" placeholder="3330000" value="<?php echo $row_update['cena']; ?>">
                    <?php 
                        if(isset($_POST['update'])){
                            if($cenaError != ""){
                                echo "<p style='color:red;'>" . $cenaError . "</p>"; 
                            }
                        }
                    ?>
                </div>
               </div>              
              
              <div class="row">
               
               </div>
               
               
               <div class="row">
                   <div class="col-md-6 form-group">
                       <label for="kvadratura">Property Size: (m2):</label>
                       <input style="<?php if(isset($_GET['id_stana'])) echo "font-weight:600;" ?>" type="text" name="kvadratura" class="form-control" placeholder="Npr. 100" value="<?php echo $row_update['kvadratura']; ?>">
                    <?php 
                        if(isset($_POST['update'])){
                            if($kvadraturaError != ""){
                                echo "<p style='color:red;'>" . $kvadraturaError . "</p>"; 
                            }
                        }
                    ?>
                   </div>
               
               
                <div class="col-md-6 form-group" style="margin-bottom: 0;">
                   <label>Opis nekretnine:</label>
                   <textarea style="<?php if(isset($_GET['id_stana'])) echo "font-weight:600;" ?>" name="opis" class="form-control" ><?php echo $row_update['opis']; ?></textarea>
                   <?php 
                      if(isset($_POST['update'])){
                         if($opisError != ""){
                             echo "<p style='color:red;'>" . $opisError . "</p>"; 
                           }
                      }
                   ?>
                    
               </div>
             </div>
              
              <div class="row">
                   <div class="col-md-4 form-group">
                       <label for="status">Status nekretnine  :</label>
                            <select <?php if(isset($_GET['id_stana'])) echo "style='font-weight: 600;'"; ?> name="status" id="basic" class="form-control show-tick" >
                                <option 
                                    <?php if(!isset($_GET['id_stana'])) echo "selected disabled";else echo "disabled"; ?>>
                                    -- Prodaja ili izdavanje --
                                </option>
                                <option
                                    <?php if(isset($_GET['id_stana'])){
                                            if($row_update['status'] == "P"){
                                                echo "selected";
                                            }
                                    } ?> 
                                    value="prodaja">
                                    Prodaja
                                </option>
                                <option <?php if(isset($_GET['id_stana'])){
                                                if($row_update['status'] == "I"){
                                                    echo "selected";
                                                }
                                        } ?> 
                                    value="izdavanje">
                                    Izdavanje
                                </option>
                            </select>
                            <?php 
                                if(isset($_POST['update'])){
                                    if($statusError != ""){
                                        echo "<p style='color:red;'>" . $statusError . "</p>"; 
                                    }
                                }
                            ?>
                   </div>
                   
                   <div class="col-md-4 form-group">
                       <label for="brsoba">Broj soba:</label>
                            <select style="<?php if(isset($_GET['id_stana'])) echo "font-weight:600;" ?>" id="basic" name="brsoba" class=" show-tick form-control">
                                <option  <?php if(!isset($_GET['id_stana'])) echo "selected disabled";else echo "disabled"; ?>>-- Izaberite broj soba --</option>
                                <option 
                                        <?php if(isset($_GET['id_stana'])){
                                            if($row_update['broj_soba'] == 0.5)
                                                echo "selected";
                                        } ?> value="0.5">0.5</option>
                                <option <?php if(isset($_GET['id_stana'])){
                                            if($row_update['broj_soba'] == 1)
                                                echo "selected";
                                        } ?>  value="1">1</option>
                                <option <?php if(isset($_GET['id_stana'])){
                                            if($row_update['broj_soba'] == 1.5)
                                                echo "selected";
                                        } ?>  value="1.5">1.5</option>
                                <option <?php if(isset($_GET['id_stana'])){
                                            if($row_update['broj_soba'] == 2)
                                                echo "selected";
                                        } ?>  value="2">2</option>
                                <option <?php if(isset($_GET['id_stana'])){
                                            if($row_update['broj_soba'] == 2.5)
                                                echo "selected";
                                        } ?>  value="2.5">2.5</option>
                                <option <?php if(isset($_GET['id_stana'])){
                                            if($row_update['broj_soba'] == 3)
                                                echo "selected";
                                        } ?>  value="3">3</option>
                                <option <?php if(isset($_GET['id_stana'])){
                                            if($row_update['broj_soba'] == 3.5)
                                                echo "selected";
                                        } ?>  value="3.5">3.5</option>  
                            </select>
                            <?php 
                                if(isset($_POST['update'])){
                                    if($brsobaError != ""){
                                        echo "<p style='color:red;'>" . $brsobaError . "</p>"; 
                                    }
                                }
                            ?>
                   </div>
                   
                   <div class="col-md-4 form-group">
                       <label for="status">Status nekretnine  :</label>
                            <select <?php if(isset($_GET['id_stana'])) echo "style='font-weight: 600;'"; ?> name="status" id="basic" class="form-control show-tick" >
                                <option 
                                    <?php if(!isset($_GET['id_stana'])) echo "selected disabled";else echo "disabled"; ?>>
                                    -- Prodaja ili izdavanje --
                                </option>
                                <option
                                    <?php if(isset($_GET['id_stana'])){
                                            if($row_update['status'] == "P"){
                                                echo "selected";
                                            }
                                    } ?> 
                                    value="prodaja">
                                    Prodaja
                                </option>
                                <option <?php if(isset($_GET['id_stana'])){
                                                if($row_update['status'] == "I"){
                                                    echo "selected";
                                                }
                                        } ?> 
                                    value="izdavanje">
                                    Izdavanje
                                </option>
                            </select>
                            <?php 
                                if(isset($_POST['update'])){
                                    if($statusError != ""){
                                        echo "<p style='color:red;'>" . $statusError . "</p>"; 
                                    }
                                }
                            ?>
                   </div>
                   
               </div>
               
               
                <div class="row">
               <div class="col-md-12">
                 <input type="submit" style="height:55px;" name="update" class="btn btn-black py-3 btn-block login-btn" value="Update">
               </div>
               <div class="col-md-6 mt-2">
                   <b style="font-size: 12px;">Already have an account?</b><a style="color:black!important;font-size: 12px;" href="login.php"> Sign in</a>
                </div>
             </div>
             
             <?php 
                    if(isset($_POST['update'])){
                            if($uspeh != "")
                                echo "<p style='color:#178550;font-size:20px;'>Property updated sucessfully! <a style='color: black!important;' href='property-single.php?id_stana=" . $_GET['id_stana'] . "'>View property</a>.</p>";
                    }
             ?>
             
               </div>
               
             </div>
             

           </div>
           
           
<!------------------------------------------------ ADD NEW ------------------------------------------------>
           
           <?php }else{?>
           
           <div class="realestate-tabpane pb-5 col-md-12">
        <div class="container tab-content">
           <div class="tab-pane active" id="for-rent" role="tabpanel" aria-labelledby="rent-tab">

             <div class="row">
                   <div class="col-md-6 form-group" style="margin-bottom: 0;">
                       <label>Property Location:</label>
                            <input name="lokacija" type="text" class="form-control" placeholder="E.g. New York">
                            <?php 
                                if(isset($_POST['submit'])){
                                    if($lokacijaError != ""){
                                        echo "<p style='color:red;'>" . $lokacijaError . "</p>"; 
                                    }
                                }
                            ?>
                   </div>
                   <div class="col-md-6 form-group" style="margin-bottom: 0;">
                    <label>Property Price ($):</label>
                    <input name="cena" type="text" class="form-control" placeholder="E.g. 1000" >
                    <?php 
                        if(isset($_POST['submit'])){
                            if($cenaError != ""){
                                echo "<p style='color:red;'>" . $cenaError . "</p>"; 
                            }
                        }
                    ?>
                </div>
               </div>  
               
                                       
              <div class="row">
                  <div class="col-md-6 form-group">
                       <label for="kvadratura">Property Size (m²):</label>
                       <input  type="text" name="kvadratura" class="form-control" placeholder="E.g. 100" >
                        <?php 
                            if(isset($_POST['submit'])){
                                if($kvadraturaError != ""){
                                    echo "<p style='color:red;'>" . $kvadraturaError . "</p>"; 
                                }
                            }
                        ?>
                   </div>
                   <div class="col-md-6 form-group" style="margin-bottom: 0;">
                   <label>Property Description:</label>
                    <textarea  name="opis" class="form-control" placeholder="E.g. A nice villa in a quite part of town"></textarea>
                    <?php 
                        if(isset($_POST['submit'])){
                            if($opisError != ""){
                                echo "<p style='color:red;'>" . $opisError . "</p>"; 
                            }
                        }
                    ?>
                    
               </div>
                   
               </div>
              
                             
              <div class="row">
                   <div class="col-md-4 form-group">
                       <label for="status">Property Status:</label>
                        <select  name="status" id="basic" class="form-control show-tick" >
                            <option selected disabled>
                                -- For Sale or For Rent --
                            </option>
                            <option value="prodaja">For Sale</option>
                            <option value="izdavanje">For Rent</option>
                        </select>
                        <?php 
                            if(isset($_POST['submit'])){
                                if($statusError != ""){
                                    echo "<p style='color:red;'>" . $statusError . "</p>"; 
                                }
                            }
                        ?>
                   </div>
                   <div class="col-md-4 form-group">
                       <label for="brsoba">Number of Rooms:</label>
                            <select  id="basic" name="brsoba" class=" show-tick form-control">
                                <option selected disabled>-- Choose Room Number --</option>
                                <option value="0.5">0.5</option>
                                <option value="1">1</option>
                                <option value="1.5">1.5</option>
                                <option value="2">2</option>
                                <option value="2.5">2.5</option>
                                <option value="3">3</option>
                                <option value="3.5">3.5</option>  
                            </select>
                        <?php 
                            if(isset($_POST['submit'])){
                                if($brsobaError != ""){
                                    echo "<p style='color:red;'>" . $brsobaError . "</p>"; 
                                }
                            }
                        ?>
                   </div>
                   <div class="col-md-4">
                       <label for="property-images">Property images:</label>
                        <input class="form-control" name="slika" type="file" id="property-images">
                        <?php 
                            if(isset($_POST['submit'])){
                                if($slikaError != ""){
                                    echo "<p style='color:red;'>" . $slikaError . "</p>"; 
                                }
                            }
                                                               
                            if(isset($_GET['id_stana'])){
                                echo "<h5 style='color:red'>Dodajte novu sliku</h5>";
                            }

                        ?>
                   </div>
                   
               </div>               
               
               
             
               <br>
               
                <div class="row">
               <div class="col-md-12">
                 <input type="submit" name="submit" style="height: 55px;" class="btn btn-black py-3 btn-block login-btn" value="Submit">
               </div>
               <div class="col-md-12 mt-2">
                   <b style="font-size: 12px;">Already have a property?</b><a style="color:black!important;font-size: 12px;" href="profile.php"> Update here</a>
                </div>
             </div>
             
             <?php 
                    if(isset($_POST['submit'])){
                            if($uspeh != "")
                                echo "<p style='color:#178550;font-size:20px;'>Property added sucessfully! <a style='color: black!important;' href='property-single.php?id_stana=" . $novi_id . "'>View property</a>.</p>";
                    }
             ?>
             
               </div>
               
             </div>
             

           </div>
           
           <?php }?>
           
        </div>
      </div>
    </form>
            </div>
          </div>
        </div>
      </div>
    </div>

    

    

    <footer class="site-footer footer-submit">
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

