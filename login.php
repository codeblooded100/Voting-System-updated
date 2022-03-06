<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <title>Admin | Voting System</title>
 	

<?php include('./header.php'); ?>
<?php 

session_start();
if(isset($_SESSION['login_id']))
header("location:index.php?page=home");
?>

</head>
<style>
	body{
		width: 100%;
	    height: calc(100%);
	    /*background: #007bff;*/
		background-image: url("./assets/img/cossa4.jpg");
		background-repeat: no-repeat;
        background-attachment: fixed;
        background-size: cover;
		
	}
	main#main{
		width:100%;
		height: calc(100%);
		background:white;
		background-image: url("./assets/img/cossa4.jpg");
		background-repeat: no-repeat;
        background-attachment: fixed;
        background-size: cover;
		
	}
	#login-right{
		
		right:0;
		width:50%;
		height: calc(100%);
		background:white;
		margin: 10% auto;
		align-items: center;
		box-shadow: 1px 1px 4px 4px black;
		
	}
	#login-left{
		position: absolute;
		left:0;
		width:40%;
		height: calc(100%);
		background:black;
		display: flex;
		align-items: center;
	}
	#login-right .card{
		margin: auto
	}
	.logo {
    margin: auto;
    font-size: 8rem;
    background: transparent;
    padding: .5em 0.8em;
    border-radius: 50% 50%;
    color: #000000b3;
}

img{
	width: 80%;
	height: 30%;
	margin: 10% auto;
}

@media screen and (max-width: 800px) {
	#login-right{
        margin-top: 50%;
		margin-left: 10% auto;
		width:80%;
		height: calc(100%);
		background:white;
		align-items: center;
	}

	body{
		width: 100%;
	    height: calc(100%);
	    /*background: #007bff;*/
		background-image: url("./assets/img/cossa4.jpg");
		background-repeat: no-repeat;
        background-attachment: fixed;
        background-size: cover;
		
	}
img{
	width: 60%;
	height: 60%;
	margin: 10% auto;
}
}
</style>

<body>


  <main id="main" class=" alert-info">
  		<!-- <div id="login-left">
  			<div class="logo">
  				 <i class="fa fa-poll-h"></i> 
				  <img src="./assets/img/cossa.jpeg" alt="cossa" width = "80%"; height = "80%">
  			</div>
  		</div> -->
  		<div id="login-right">
  			<div class="card col-md-8">
			  <!-- <img src="./assets/img/cossa.jpeg" alt="cossa logo"> -->
  				<div class="card-body">
					  
  					<form id="login-form" >
					  
  						<div class="form-group">
							
  							<label for="username" class="control-label">Username</label>
  							<input type="text" id="username" name="username" class="form-control">
  						</div>
  						<div class="form-group">
  							<label for="password" class="control-label">Password</label>
  							<input type="password" id="password" name="password" class="form-control">
  						</div>
  						<center><button class="btn-sm btn-block btn-wave col-md-4 btn-primary">Login</button></center>
  					</form>
  				</div>
  			</div>
  		</div>
   <!-- Footer -->
<footer class="page-footer font-small blue">

<!-- Copyright -->
<div class="footer-copyright text-center" style="color: white; font-size: 10px;" >Powered by :
  <a href="#" style="color: white;"> CODE BLOODED</a>
</div>
<!-- Copyright -->

</footer>
<!-- Footer -->

  </main>

  <a href="#" class="back-to-top"><i class="icofont-simple-up"></i></a>


</body>
<script>
	$('#login-form').submit(function(e){
		e.preventDefault()
		$('#login-form button[type="button"]').attr('disabled',true).html('Logging in...');
		if($(this).find('.alert-danger').length > 0 )
			$(this).find('.alert-danger').remove();
		$.ajax({
			url:'ajax.php?action=login',
			method:'POST',
			data:$(this).serialize(),
			error:err=>{
				console.log(err)
		$('#login-form button[type="button"]').removeAttr('disabled').html('Login');

			},
			success:function(resp){
				if(resp == 1){
					location.href ='index.php?page=home';
				}else if(resp == 2){
					location.href ='voting.php';
				}else{
					$('#login-form').prepend('<div class="alert alert-danger">Username or password is incorrect.</div>')
					$('#login-form button[type="button"]').removeAttr('disabled').html('Login');
				}
			}
		})
	})
</script>	
</html>