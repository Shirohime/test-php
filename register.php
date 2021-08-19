<?php

	// Config File
	require_once('database/config.php');

	//include header
	require('layout/header-simple.php');

	//include functions
	require('functions.php');

	// Variables
	$username = "";
	$email = ""; 
	$password = ""; 
	$confirm_password = "";
	$username_e = ""; 
	$email_e = "";
	$password_e = ""; 
	$confirm_password_e = "";

	// Submit
	if(isset($_POST['submit'])){

		$username = $_POST['username'];
		$email = $_POST['email'];
		$password = $_POST['password'];

		// Validate username
	    if(empty($_POST["username"])){
	        $username_e = "Por favor ingrese el Nombre."; 
	    }else{
	    	// Prepare a select statement
	        $sql = "SELECT id_user FROM users WHERE username = ?";
	        
	        if($stmt = mysqli_prepare($link, $sql)){

	            // Bind variables
	            mysqli_stmt_bind_param($stmt, "s", $param_username);
	            
	            // Set parameters
	            $param_username = $_POST["username"];
	            
	            if(mysqli_stmt_execute($stmt)){

	                /* store result */
	                mysqli_stmt_store_result($stmt);
	                
	                if(mysqli_stmt_num_rows($stmt) == 1){
	                    $username_err = "Este usuario ya existe.";
	                } else{
	                    $username = $_POST["username"];
	                }
	            } else{
	                echo "Al parecer algo salió mal.";
	            }
	        }    

	        // Close statement
        	mysqli_stmt_close($stmt);    
	    }


	    //Validate email
	    if (!empty($_POST["email"])) {
		   if (validate_email($_POST['email']))
		   	  $email = $_POST["email"];
		   else
		   	  $email_e = "Email incorrecto";
		}


	    // Validate password
	    if(empty(trim($_POST["password"]))){
	        $password_e = "Por favor ingresa una contraseña.";     
	    } elseif(strlen(trim($_POST["password"])) < 6){
	        $password_e = "La contraseña al menos debe tener 6 caracteres.";
	    } else{
	        $password = trim($_POST["password"]);
	    }
	    

	    // Validate confirm password
	    if(empty(trim($_POST["confirm_password"]))){
	        $confirm_password_e = "Confirma tu contraseña.";     
	    } else{
	        $confirm_password = trim($_POST["confirm_password"]);
	        if(empty($password_e) && ($password != $confirm_password)){
	            $confirm_password_e = "No coincide la contraseña.";
	        }
	    }


	    // Check input errors
	    if(empty($username_e) && empty($email_e) && empty($password_e) && empty($confirm_password_e)){

	    	// Prepare query
	        $sql = "INSERT INTO users (username, email, password) VALUES (?, ?, ?)";
	         
	        if($stmt = mysqli_prepare($link, $sql)){

	            // Bind variables
	            mysqli_stmt_bind_param($stmt, "sss", $param_username, $param_email, $param_password);
	            
	            // Set parameters
	            $param_username = $username;
	            $param_email = $email;
	            $param_password = password_hash($password, PASSWORD_DEFAULT); 
	            
	            // Attempt to execute the prepared statement
	            if(mysqli_stmt_execute($stmt)){

	                // Redirect to signin
	                header("location: signin.php");
	            } else{
	                echo "Algo salió mal, por favor inténtalo de nuevo.";
	            }
	        }
   
	        
	    }

	    // Close connection
    	mysqli_close($link);

	}

?>

<div class="container section-top">

	<div class="row justify-content-center align-items-center">

	    <div class="col-xs-12 col-sm-8 col-md-6 col-sm-offset-2 col-md-offset-3">
	    	<h2 class="space-heading">Registro</h2>
			<form role="form" method="post" action="register.php" autocomplete="off">
				
				<div class="form-group">
					<input type="text" name="username" id="username" class="form-control input-lg" placeholder="Nombre" value="<?= $username ?>" tabindex="1">
					<div class="invalid-feedback"><?= $username_e; ?></div>
				</div>
				<div class="form-group">
					<input type="email" name="email" id="email" class="form-control input-lg" placeholder="Correo Electrónico" value="<?= $email ?>" tabindex="2">
					<div class="invalid-feedback"><?= $email_e; ?></div>
				</div>
				<div class="row">
					<div class="col-xs-6 col-sm-6 col-md-6">
						<div class="form-group">
							<input type="password" name="password" id="password" class="form-control input-lg" placeholder="Contraseña" tabindex="3">
							<div class="invalid-feedback"><?= $password_e; ?></div>
						</div>
					</div>
					<div class="col-xs-6 col-sm-6 col-md-6">
						<div class="form-group">
							<input type="password" name="confirm_password" id="passwordConfirm" class="form-control input-lg" placeholder="Confirmar Contraseña" tabindex="4">
							<div class="invalid-feedback"><?= $confirm_password_e; ?></div>
						</div>
					</div>
				</div>

				<div class="row">
					<div class="col-xs-9 col-sm-9 col-md-9">
						<small class="text-muted">Si ya tienes una cuenta, inicia sesión </small><a href='signin.php'>Aquí</a>
					</div>
				</div><br>

				<div class="row">
					<!--<div class="col-md-6"></div>-->
					<div class="col-xs-12 col-md-12">
						<input type="submit" name="submit" value="Enviar" class="btn btn-primary btn-block btn-lg" tabindex="5">
					</div>
				</div>
			    
			</form>
		</div>
		
	</div>
	
</div>	


<?php

	//include footer
	require('layout/footer.php');

?>