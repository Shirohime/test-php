<?php

    //include header
    require('layout/header-simple.php');

    // Initialize the session
    session_start();

 
    // Check if the user is already logged in, if yes then redirect to index
    if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true){
      header("location: home.php");
      exit;
    }

    // Config File
    require_once('database/config.php');

     
    // Define variables
    $username = ""; 
    $password = "";
    $username_e = ""; 
    $password_e = "";
     
    // Submit
    if(isset($_POST['submit'])){


        $username = $_POST['username'];
        $password = $_POST['password'];
     
        // Check if username is empty
        if(empty($_POST["username"])){
            $username_e = "Por favor ingrese su usuario.";
        } else{
            $username = $_POST["username"];
        }
        
        // Check if password is empty
        if(empty(trim($_POST["password"]))){
            $password_e = "Por favor ingrese su contraseña.";
        } else{
            $password = trim($_POST["password"]);
        }
        
        // Validate credentials
        if(empty($username_e) && empty($password_e)){

            // Prepare a select statement
            $sql = "SELECT id_user, username, password FROM users WHERE username = ?";

            
            if($stmt = mysqli_prepare($link, $sql)){
                // Bind variables
                mysqli_stmt_bind_param($stmt, "s", $param_username);
                
                // Set parameters
                $param_username = $username;
                
                // Attempt to execute the prepared statement
                if(mysqli_stmt_execute($stmt)){

                    // Store result
                    mysqli_stmt_store_result($stmt);
                    
                    // Check if username exists
                    if(mysqli_stmt_num_rows($stmt) == 1){  

                        // Bind result variables
                        mysqli_stmt_bind_result($stmt, $id_user, $username, $hashed_password);
                        if(mysqli_stmt_fetch($stmt)){
                            if(password_verify($password, $hashed_password)){

                                // Password is correct
                                session_start();
                                
                                // Store data in session variables
                                $_SESSION["loggedin"] = true;
                                $_SESSION["id_user"] = $id_user;
                                $_SESSION["username"] = $username;                            
                                
                                // Redirect user to home
                                header("location: home.php");
                            } else{
                                // Display an error message
                                $password_e = "La contraseña que has ingresado no es válida.";
                            }
                        }
                    } else{
                        // Display an error message
                        $username_e = "No existe cuenta registrada con ese nombre de usuario.";
                    }
                } else{
                    echo "Algo salió mal, por favor vuelve a intentarlo.";
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
	    	<h2 class="">Iniciar Sesión</h2>
	    	<small class="text-muted">Ingresar la información correspondiente.</small><br><br>
	    	
	    	<form action="signin.php" method="post">
                <div class="form-group">
                    <label>Usuario</label>
                    <input type="text" name="username" class="form-control">
                    <span class="help-block"><?php echo $username_e; ?></span>
                </div>    
                <div class="form-group">
                    <label>Contraseña</label>
                    <input type="password" name="password" class="form-control">
                    <span class="help-block"><?php echo $password_e; ?></span>
                </div>
                <div class="row">
					<div class="col-xs-9 col-sm-9 col-md-9">
						<small class="text-muted">¿No recuerdas tu contraseña?, recúperala </small><a href='change-password.php'>Aquí</a>
					</div>
				</div><br>
                <div class="form-group">
                    <input type="submit" class="btn btn-primary btn-lg btn-block" value="Ingresar">
                </div>
            </form>
	    	
	    </div>
	   
	</div>
</div>	
 

<?php

	//include footer
	require('layout/footer.php');

?>      
