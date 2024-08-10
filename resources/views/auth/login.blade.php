<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

		<!-- Document Title -->
		<title>{{ config('app.name', 'ARMS') }}</title>
		<link rel="icon" type="image/x-icon" href="{{ asset('img/smcc.png') }}">

		<!-- Custom fonts for this template-->
		<link href="{{ asset('vendor/fontawesome-free/css/all.min.css') }}" rel="stylesheet" type="text/css">
		<link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
		
		<!-- Custom styles for this template-->
		<link href="{{ asset('css/sb-admin-2.min.css') }}" rel="stylesheet">
		<link rel="stylesheet" href="{{ asset('welcome/dist/css/style.css') }}">
		<link rel="stylesheet" href="{{ asset('welcome/dist/css/custom.css') }}">

		<!-- Js Sources -->
		<script src="https://unpkg.com/animejs@3.0.1/lib/anime.min.js"></script>
		<script src="https://unpkg.com/scrollreveal@4.0.0/dist/scrollreveal.min.js"></script>
	</head>
	<body class="is-boxed has-animations">
		<div class="body-wrap">
			<header class="site-header">
				<div class="container">
					<div class="site-header-inner">
						<div class="brand header-brand">
							<a href="{{ url('/') }}">
								<div class="display-inline-sm">
									<img class="header-logo-image-sm" src="{{ asset('img/smcc.png') }}" alt="Logo">
									<span>
										<h5>ARMS</h5>
									</span>
								</div>
							</a>
						</div>
					</div>
				</div>
			</header>
			<main>
				<section class="pricing section">
					<div class="container-sm">
						<div class="pricing-inner section-inner">
							<div class="pricing-header text-center"></div>
							<div class="pricing-tables-wrap">
								<div class="pricing-table">
									<div class="pricing-table-inner is-revealing">
										<div class="pricing-table-features-title text-xs pt-2 pb-2 mb-4 text-center">
											<h5 class="section-title mt-0">Welcome Back</h5>
										</div>
										<form id="formLogin" class="user" method="POST" action="{{ route('login') }}">
											<div class="pricing-table-main"> @csrf <div class="form-group">
													<input type="email" class="form-control form-control-user @error('email') is-invalid @enderror" id="exampleInputEmail" aria-describedby="emailHelp" name="email" placeholder="Enter Email Address..." value="{{ old('email') }}" required>
													@error('email')
														<span class="small">Email is invalid</span>
													@enderror
												</div>
												<div class="form-group">
													<input type="password" class="form-control form-control-user @error('password') is-invalid @enderror" id="exampleInputPassword" placeholder="Password" name="password" required>
													@error('password')
														<span class="small">Password is unrecognized</span>
													@enderror
												</div>
												<div class="form-group">
													<div class="custom-control custom-checkbox small">
														<input type="checkbox" class="custom-control-input" id="customCheck">
														<label class="custom-control-label" for="customCheck">Remember Me</label>
													</div>
												</div>
											</div>
											<div class="pricing-table-cta mb-8">
												<button class="button button-primary button-shadow button-block"> Sign in </button>
											</div>
										</form>
									</div>
								</div>
							</div>
						</div>
					</div>
				</section>
			</main>
			<script src="{{ asset('vendor/jquery/jquery.min.js') }}"></script>
			<script src="{{ asset('vendor/jquery-easing/jquery.easing.min.js') }}"></script>
			<script src="{{ asset('welcome/dist/js/main.min.js') }}"></script>
			<script src="{{ asset('welcome/dist/js/custom.js') }}"></script>

			<!-- Bootstrap core JavaScript-->
			<script src="{{ asset('vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
	</body>
</html>