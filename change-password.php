<?php
	//include header
	require('layout/header-simple.php');

	// Initialize the session
	session_start();
	 
	// Check if the user is logged in
	if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
	    header("location: signin.php");
	    exit;
	}
	 
	// Include config file
	require_once "database/config.php";

	//Define variables
	$new_password = ""; 
	$confirm_password = "";
	$new_password_e = ""; 
	$confirm_password_e = "";

	//Submit
	if(isset($_POST['submit'])){

		// Validate new password
	    if(empty(trim($_POST["new_password"]))){
	        $new_password_e = "Ingresa la nueva contraseña.";     
	    } elseif(strlen(trim($_POST["new_password"])) < 6){
	        $new_password_e = "La contraseña al menos debe tener 6 caracteres.";
	    } else{
	        $new_password = trim($_POST["new_password"]);
	    }
	    
	    // Validate confirm password
	    if(empty(trim($_POST["confirm_password"]))){
	        $confirm_password_e = "Por favor confirme la contraseña.";
	    } else{
	        $confirm_password = trim($_POST["confirm_password"]);
	        if(empty($new_password_e) && ($new_password != $confirm_password)){
	            $confirm_password_e = "Las contraseñas no coinciden.";
	        }
	    }

	    // Check input errors before updating the database
	    if(empty($new_password_err) && empty($confirm_password_err)){

	        // Prepare query
	        $sql = "UPDATE users SET password = ? WHERE id_user = ?";
	        
	        if($stmt = mysqli_prepare($link, $sql)){

	            // Bind variables
	            mysqli_stmt_bind_param($stmt, "si", $param_password, $param_id);
	            
	            // Set parameters
	            $param_password = password_hash($new_password, PASSWORD_DEFAULT);
	            $param_id = $_SESSION["id_user"];
	            
	            if(mysqli_stmt_execute($stmt)){

	                // Password updated successfully.
	                session_destroy();
	                header("location: signin.php");
	                exit();

	            } else{
	                echo "Algo salió mal, por favor vuelva a intentarlo.";
	            }
	        }
	        
	        // Close statement
	        mysqli_stmt_close($stmt);
	    }
	    
	    // Close connection
	    mysqli_close($link);

	}
?>	


<div class="container section-top">

	<div class="row justify-content-center align-items-center">

	    <div class="col-xs-12 col-sm-8 col-md-6 col-sm-offset-2 col-md-offset-3">
	    	<h2 class="space-heading">Recuperar Contraseña</h2>

    
            <form action="change-password.php" method="post"> 
                <div class="form-group">
                    <label>Nueva contraseña</label>
                    <input type="password" name="new_password" class="form-control" >
                    <span class="help-block"><?php echo $new_password_e; ?></span>
                </div>
                <div class="form-group <?php echo (!empty($confirm_password_err)) ? 'has-error' : ''; ?>">
                    <label>Confirmar contraseña</label>
                    <input type="password" name="confirm_password" class="form-control">
                    <span class="help-block"><?php echo $confirm_password_e; ?></span>
                </div>
                <div class="form-group">
                    <input type="submit" class="btn btn-primary btn-lg" value="Enviar">
                    <a class="btn btn-link" href="index.php">Cancelar</a>
                </div>
            </form>
        </div>
    </div>
</div>


<?php

	//include footer
	require('layout/footer.php');

?>    