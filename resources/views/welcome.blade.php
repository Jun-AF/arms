<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
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
		
		<!-- Custom fonts for this template-->
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
								<div class="display-inline">
									<img class="header-logo-image" src="{{ asset('img/smcc.png') }}" alt="Logo">
									<span>
										<h2>ARMS</h2>
									</span>
								</div>
							</a>
						</div>
					</div>
				</div>
			</header>
			<main>
				<section id="hero" class="hero">
					<div class="container">
						<div class="hero-inner">
							<div class="hero-copy">
								<h1 class="hero-title mt-0">Register, Validate, and Export</h1>
								<p class="hero-paragraph">ARMS makes asset data recording easier, standardized, organized, and pretier</p>
								<div class="hero-cta">
									<a class="button button-primary shadow" href="{{ url('login') }}">Sign in</a>
									<a class="button shadow" id="showInfo" href="#info">What Is ARMS</a>
								</div>
							</div>
							<div class="hero-figure anime-element">
								<svg class="placeholder" width="528" height="396" viewBox="0 0 528 396">
									<rect width="528" height="396" style="fill:transparent;" />
								</svg>
								<div class="hero-figure-box hero-figure-box-01" data-rotation="45deg"></div>
								<div class="hero-figure-box hero-figure-box-02" data-rotation="-45deg"></div>
								<div class="hero-figure-box hero-figure-box-03" data-rotation="0deg"></div>
								<div class="hero-figure-box hero-figure-box-04" data-rotation="-135deg"></div>
								<div class="hero-figure-box hero-figure-box-05">
									<img src="{{ asset('img/leaks.png') }}" alt="">
									<img src="{{ asset('img/login-leaks.png') }}" alt="">
								</div>
								{{-- <div class="hero-figure-box hero-figure-box-06"></div> --}}
								<div class="hero-figure-box hero-figure-box-07"></div>
								<div class="hero-figure-box hero-figure-box-08" data-rotation="-22deg"></div>
								<div class="hero-figure-box hero-figure-box-09" data-rotation="-52deg"></div>
								<div class="hero-figure-box hero-figure-box-10" data-rotation="-50deg"></div>
							</div>
						</div>
					</div>
				</section>
				<div class="mt-5"></div>
				<div id="info"></div>
				<section class="info section">
					<div class="container">
						<h4 class="m-0">What IS ARMS</h4>
						<div class="explain">ARMS is an abreviation of Asset Resources Management System, it was develope by SMCC Utama IT team to achieve asset records standardization. ARMS comes with features for asset records registration, asset transaction track, and asset validation check. The data presentations make lots pretty and organized with data table</div>
					</div>
				</section>
				<section class="features section">
					<div class="container">
						<div class="features-inner section-inner has-bottom-divider">
							<hr>
							<h4 class="m-0 text-center mb-2 mt-2">FEATURES</h4>
							<div class="features-wrap">
								<div class="feature text-center is-revealing">
									<div class="feature-inner">
										<div class="feature-icon">
											<img src="{{ asset('welcome/dist/images/carton.png') }}" alt="Asset Recording">
										</div>
										<h4 class="feature-title mt-24">Asset Registration</h4>
										<p class="text-sm mb-0">Store the new coming asset and present it in the table view. Chart bar also helps for review the analytics of the asset transaction</p>
									</div>
								</div>
								<div class="feature text-center is-revealing">
									<div class="feature-inner">
										<div class="feature-icon">
											<img src="{{ asset('welcome/dist/images/giving.png') }}" alt="Asset Transaction">
										</div>
										<h4 class="feature-title mt-24">Asset Transaction</h4>
										<p class="text-sm mb-0">Track all the asset transaction such as handover or return. Present the asset status with its user in the proper way</p>
									</div>
								</div>
								<div class="feature text-center is-revealing">
									<div class="feature-inner">
										<div class="feature-icon">
											<img src="{{ asset('welcome/dist/images/check-list.png') }}" alt="Asset Validation">
										</div>
										<h4 class="feature-title mt-24">Asset Validation</h4>
										<p class="text-sm mb-0">Make validation by the office, assets will be present up to its office name. Make the validation work easy by clicking the validate button.</p>
									</div>
								</div>
							</div>
						</div>
					</div>
				</section>
			</main>
			<footer class="site-footer">
				<div class="container">
					<div class="site-footer-inner">
						<div class="brand footer-brand">
							<img class="header-logo-image-sm" src="{{ asset('img/smcc.png') }}" alt="Logo">
							<span>
								<h6>ARMS</h6>
							</span>
						</div>
						<ul class="footer-links list-reset">
							<li>
								<a href="https://www.sumicon.co.id/contact" target="_blank">Contact</a>
							</li>
							<li>
								<a href="https://www.sumicon.co.id" target="_blank">About us</a>
							</li>
							<li>
								<a href="mailto:support@sumicon.co.id" target="_blank" rel="noopener noreferrer">Support</a>
							</li>
						</ul>
						<ul class="footer-social-links list-reset"></ul>
						<div class="footer-copyright">&copy;SMCC Utama Indonesia 2024</div>
					</div>
				</div>
			</footer>
		</div>
		<!-- Page level Script -->
		<script src="{{ asset('vendor/jquery/jquery.min.js') }}"></script>
		<script src="{{ asset('vendor/jquery-easing/jquery.easing.min.js') }}"></script>
		<script src="{{ asset('welcome/dist/js/main.min.js') }}"></script>
		<script src="{{ asset('welcome/dist/js/custom.js') }}"></script>

		<!-- Bootstrap core JavaScript-->
		<script src="{{ asset('vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
	</body>
</html>