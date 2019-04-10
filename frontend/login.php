<?php
if(!$callMark){exit;}
?><!DOCTYPE html>
<html>
  <head>
    <title>Bootstrap Admin Theme v3</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Bootstrap -->
    <link href="frontend/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <!-- styles -->
    <link href="frontend/css/styles.css" rel="stylesheet">

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
      <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
    <![endif]-->
  </head>
  <body class="login-bg">
  	<div class="header">
	     <div class="container">
	        <div class="row">
	           <div class="col-md-12">
	              <!-- Logo -->
	              <div class="logo">
	                 <h1><a href="index.html">Bootstrap Admin Theme</a></h1>
	              </div>
	           </div>
	        </div>
	     </div>
	</div>

	<div class="page-content container">
		<div class="row">
			<div class="col-md-4 col-md-offset-4">
				<div class="login-wrapper">
			        <div class="box">
			            <div class="content-wrap">
							<form onsubmit="return login(this)">
								<h6>Sign In</h6>
								<input class="form-control" type="text" placeholder="Username">
								<input class="form-control" type="password" placeholder="Password">
								<div class="action">
									<button type=submit class="btn btn-primary signup">Login</button>
								</div>                
							</form>
			            </div>
			        </div>

			        <div class="already">
			            <p>Don't have an account yet?</p>
			            <a href="signup.html">Sign Up</a>
			        </div>
			    </div>
			</div>
		</div>
	</div>



    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="https://code.jquery.com/jquery.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="frontend/bootstrap/js/bootstrap.min.js"></script>
	<script src="frontend/js/custom.js"></script>
	<script>
		function login(form){
			var form=$(form);
			$.ajax({
				url : "api/api.php?mod=login",
				dataType : 'json',
				type : "POST",
				data : form.serialize(),
				beforeSend : function(){
					form.find('.signup').html("Logging in").prop('disabled',true);
				},
				success : function(){
					
					form.find('.signup').html("Login").prop('disabled',true);
				},
				error : function(){

				}
			});
			return false;
		}
	</script>
  </body>
</html>