<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

		<!-- CSRF Token -->
		<meta name="csrf-token" content="{{ csrf_token() }}">

		<!-- Document Title -->
		<title>{{ config('app.name', 'ARMS') }}</title>
		<link rel="icon" type="image/x-icon" href="{{ asset('img/smcc.png') }}">

		<!-- Custom fonts for this template-->
		<link href="{{ asset('vendor/fontawesome-free/css/all.min.css') }}" rel="stylesheet" type="text/css">
		<link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
		
		<!-- Custom styles for this template-->
		<link href="{{ asset('css/sb-admin-2.min.css') }}" rel="stylesheet">
		<link href="{{ asset('app/app.css') }}" rel="stylesheet">
		<link rel="stylesheet" href="{{ asset('welcome/dist/css/custom.css') }}">
		
		<!-- Page level plugins -->
		<script src="{{ asset('vendor/chart.js/Chart.min.js') }}"></script>
	</head>
	<body id="page-top">
		<!-- Page Wrapper -->
		<div id="wrapper">
			<!-- Sidebar -->
			<ul class="navbar-nav bg-dark sidebar sidebar-dark accordion" id="accordionSidebar">
				<!-- Sidebar - Brand -->
				<a class="sidebar-brand d-flex align-items-center justify-content-center" href="{{ route('home') }}">
					<div class="sidebar-brand-icon">
						<img class="header-logo-image-sm" src="{{ asset('img/smcc.png') }}" alt="ARMS">
					</div>
					<div class="sidebar-brand-text mx-3">ARMS</div>
				</a>
				<!-- Divider -->
				<hr class="sidebar-divider my-0">
				<!-- Nav Item - Dashboard -->
				<li class="nav-item">
					<a class="nav-link" href="{{ route('home') }}">
						<i class="fas fa-fw fa-tachometer-alt"></i>
						<span>Dashboard</span>
					</a>
				</li>
				<!-- Divider -->
				<hr class="sidebar-divider">
				<!-- Heading -->
				<div class="sidebar-heading"> Pages </div>
				<li class="nav-item">
					<a class="nav-link" href="{{ route('office') }}">
						<i class="fas fa-fw fa-building"></i>
						<span>Offices</span>
					</a>
				</li>
				<li class="nav-item">
					<a class="nav-link" href="{{ route('person') }}">
						<i class="fas fa-fw fa-users"></i>
						<span>Admin & User</span>
					</a>
				</li>
				<li class="nav-item">
					<a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#assetCollection" aria-expanded="true" aria-controls="assetCollection">
						<i class="fas fa-fw fa-list"></i>
						<span>Asset & Validation</span>
					</a>
					<div id="assetCollection" class="collapse" aria-labelledby="assetCollection" data-parent="#accordionSidebar">
						<div class="bg-white py-2 collapse-inner rounded">
							<h6 class="collapse-header">Menus:</h6>
							<a class="collapse-item" href="{{ route('asset') }}">Assets</a>
							<a class="collapse-item" href="{{ route('history') }}">Transaction</a>
							<a class="collapse-item" href="{{ route('validation') }}">Validations</a>
						</div>
					</div>
				</li>
                {{-- @if (Auth::user()->role == 'Super admin')
				<li class="nav-item">
					<a class="nav-link" href="{{ route('setting') }}">
						<i class="fas fa-fw fa-user-cog"></i>
						<span>Settings</span>
					</a>
				</li>
                @endif --}}
				<!-- Divider -->
				<hr class="sidebar-divider d-none d-md-block">
				<!-- Sidebar Toggler (Sidebar) -->
				<div class="text-center d-none d-md-inline">
					<button class="rounded-circle border-0" id="sidebarToggle"></button>
				</div>
			</ul>
			<!-- End of Sidebar -->
			<!-- Content Wrapper -->
			<div id="content-wrapper" class="d-flex flex-column">
				<!-- Main Content -->
				<div id="content">
					<!-- Begin Page Content -->
					@yield('content')
				</div>
				<!-- End of Main Content -->
				@if (session()->get('condition') == 'Success')
				<div class="position-fixed p-3" style="z-index: 11">
					<div id="liveToast" class="toast hide" role="alert" aria-live="assertive" aria-atomic="true">
						<div class="toast-header">
							<i class="rounded me-2 fas fa-check-circle"></i>&nbsp;&nbsp; <strong class="me-auto">{{ session()->get('condition') }}</strong>
							<button id="toastClose" type="button" class="border-0 bg-transparent ml-auto" aria-label="Close">x</button>
						</div>
						<div class="toast-body">
							{{ session()->get('notif') }}
						</div>
					</div>
				</div> 
				@elseif (session()->get('condition') == 'Fails')
				<div class="position-fixed p-3" style="z-index: 11">
					<div id="liveToast" class="toast hide" role="alert" aria-live="assertive" aria-atomic="true">
						<div class="toast-header">
							<i class="rounded me-2 fas fa-times-circle"></i>&nbsp;&nbsp; <strong class="me-auto">{{ session()->get('condition') }}</strong>
							<button id="toastClose" type="button" class="border-0 bg-transparent ml-auto" aria-label="Close">x</button>
						</div>
						<div class="toast-body">
							{{ session()->get('notif') }}
						</div>
					</div>
				</div> 
				@else
				@endif
				<!-- Footer -->
				<footer class="sticky-footer bg-transparent">
					<div class="container my-auto">
						<div class="copyright text-center my-auto">
							<span>&copy; SMCC Utama Indonesia 2024</span>
						</div>
					</div>
				</footer>
				<!-- End of Footer -->
			</div>
			<!-- End of Content Wrapper -->
		</div>
		<!-- End of Page Wrapper -->
		<!-- Scroll to Top Button-->
		<a id="scrollTop" class="scroll-to-top rounded" href="#page-top">
			<i class="fas fa-angle-up"></i>
		</a>
		<!-- Bootstrap core JavaScript-->
		<script src="{{ asset('vendor/jquery/jquery.min.js') }}"></script>
		<script src="{{ asset('vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
		<!-- Core plugin JavaScript-->
		<script src="{{ asset('vendor/jquery-easing/jquery.easing.min.js') }}"></script>
		<!-- Custom scripts for all pages-->
		<script src="{{ asset('js/sb-admin-2.min.js') }}"></script>
		<script src="{{ asset('app/app.js') }}"></script>
		<script>
			window.onload = (event) => {
				var toastStart = $('#liveToast');
				toastStart.removeClass('hide');
			}
			$('#toastClose').on('click', () => {
				var toastStart = $('#liveToast');
				toastStart.addClass('hide');
			});
		</script>
	</body>
</html>