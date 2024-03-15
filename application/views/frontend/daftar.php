<!DOCTYPE html>
<html lang="zxx" class="no-js">
	<head>
		<!-- Mobile Specific Meta -->
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
		<!-- Favicon-->
		<link rel="shortcut icon" href="img/elements/fav.png">
		<!-- Author Meta -->
		<meta name="author" content="colorlib">
		<!-- Meta Description -->
		<meta name="description" content="">
		<!-- Meta Keyword -->
		<meta name="keywords" content="">
		<!-- meta character set -->
		<meta charset="UTF-8">
		<!-- Site Title -->
		<!-- Log on to codeastro.com for more projects -->
		<title>Register</title>
		<link href="https://fonts.googleapis.com/css?family=Poppins:100,200,400,300,500,600,700" rel="stylesheet">
		<!--CSS-->
		<link rel="stylesheet" type="text/css" href="<?php echo base_url() ?>assets/frontend/datepicker/dcalendar.picker.css">
		<?php $this->load->view('frontend/include/base_css'); ?>
	</head>
	<body>
		<!-- navbar -->
		<?php $this->load->view('frontend/include/base_nav'); ?>
		<section class="service-area section-gap relative">
			<div class="overlay overlay-bg"></div>
			<div class="container">
				<div class="row d-flex justify-content-center">
					<div class="col-lg-8">
						<!-- Default Card Example -->
						<div class="card ">
							<div class="card-header">
								<i class="fas fa-user"></i> Customer Registration
							</div>
							<div class="card-body">
								<form action="<?php echo base_url() ?>login/daftar" method="post">
								<div class="form-group">
									<div class="form-group">
										<div class="form-label-group">
											<input type="text" name="first_name" class="form-control" required="" placeholder="First Name" value="<?php echo set_value('first_name') ?>">
											<?php echo form_error('first_name'),'<small class="text-danger pl-3">','</small>'; ?>
										</div>
									</div>
								</div>

								<div class="form-group">
									<div class="form-group">
										<div class="form-label-group">
											<input type="text" name="middle_name" class="form-control" placeholder="Middle Name" value="<?php echo set_value('middle_name') ?>">
											<?php echo form_error('middle_name'),'<small class="text-danger pl-3">','</small>'; ?>
										</div>
									</div>
								</div>

								<div class="form-group">
									<div class="form-group">
										<div class="form-label-group">
											<input type="text" name="last_name" class="form-control" required="" placeholder="Last Name" value="<?php echo set_value('last_name') ?>">
											<?php echo form_error('last_name'),'<small class="text-danger pl-3">','</small>'; ?>
										</div>
									</div>
								</div>
									<div class="form-group">
										<div class="form-row">
											<div class="col-md-6">
												<div class="form-label-group">
													<input type="text" name="email" class="form-control" required="" placeholder="Email" value="<?php echo set_value('email') ?>">
													<?php echo form_error('email'),'<small class="text-danger pl-3">','</small>'; ?>
												</div>
											</div>
											<div class="col-md-6">
												<div class="form-label-group">
													<input type="text" id="nomor" name="nomor" class="form-control" required="" placeholder="Contact Number" value="<?php echo set_value('nomor') ?>">
													<?php echo form_error('nomor'),'<small class="text-danger pl-3">','</small>'; ?>
												</div>
											</div>
										</div>
									</div>
									<div class="form-group">
										<label for="username">Address</label>
										<div class="form-label-group">
											<textarea class="form-control" name="alamat"><?php echo set_value('alamat') ?></textarea>
											<?php echo form_error('alamat'),'<p class="text-danger pl-3">','</p>'; ?>
										</div>
									</div>
									<div class="form-group">
										<div class="form-label-group">
											<input type="text" id="username" name="username" class="form-control" required="" placeholder="Username" >
											<?php echo form_error('username'),'<small class="text-danger pl-3">','</small>'; ?>
										</div>
									</div>
									<div class="form-group row">
										<div class="col-sm-6 mb-3 mb-sm-0">
											<input type="password" class="form-control form-control-user" name="password1" placeholder="Password">
										</div>
										<div class="col-sm-6">
											<input type="password" class="form-control form-control-user" name="password2" placeholder="Repeat Password">
										</div>
									</div>
									<?php echo form_error('password1'),'<small class="text-danger pl-3">','</small>'; ?>
									<button class="btn btn-info btn-block">Register</button>
								</form>
								<hr>
								<div class="text-center">
									<p>Already Registered? <a class="" href="<?php echo base_url() ?>login">Login Now</a></p>
								</div>
							</div>
						</div>
					</div>
				</section>
				<script>
					var firstName = document.getElementById("first_name").val();
					var middleName = document.getElementById("middle_name").val();
					var lastName = document.getElementById("last_name").val();
alert(firstName)				</script>
				<!-- End banner Area -->
				<!-- Log on to codeastro.com for more projects -->
				<!-- start footer Area -->
				<?php $this->load->view('frontend/include/base_footer'); ?>
				<!-- js -->
				<?php $this->load->view('frontend/include/base_js'); ?>
			</body>
		</html>