<?php
	// Initialize the session
	session_start();
	 
	// Check if the user is logged in, if not then redirect him to login page
	if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
	    header("location: signin.php");
	    exit;
	}

	//include header
	require('layout/header-simple.php');
?>

<section class="section section-top section-full">

      <!-- Cover -->
      <div class="bg-cover" style="background-image: url(assets/images/wall-1.png);"></div>

      <!-- Overlay -->
      <div class="bg-overlay"></div>


      <!-- Content -->
      <div class="container">
        <div class="row justify-content-center align-items-center">
          <div class="col-md-8 col-lg-7">
            
            <!-- Heading -->
            <h1 class="text-white text-center mb-4 animate" data-toggle="animation" data-animation="fadeUp" data-animation-order="1" data-animation-trigger="load">
             Bienvenid@, <b><?php echo htmlspecialchars($_SESSION["username"]); ?></b>.
            </h1>

            <p>
		        <a href="change-password.php" class="btn btn-white">Cambia tu contraseña</a>
		        <a href="logout.php" class="btn btn-white">Cerrar sesión</a>
		    </p>

          </div>
        </div> <!-- / .row -->
      </div> <!-- / .container -->


</section>   
    
<?php

	//include footer
	require('layout/footer.php');

?>