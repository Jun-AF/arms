@extends('layouts.app') @section('content') <div class="container-fluid">
	<div class="row justify-content-md-center">
		<!-- Asset Count -->
		<div class="col-xl-3 col-md-6 mb-4">
			<div class="card border-left-success shadow h-100 py-2">
				<div class="card-body">
					<div class="row no-gutters align-items-center">
						<div class="col mr-2">
							<div class="text-xs font-weight-bold text-success text-uppercase mb-1"> Total Assets</div>
							<div class="h5 mb-0 font-weight-bold text-gray-800">{{ $assets_count }}</div>
						</div>
						<div class="col-auto">
							<i class="fas fa-laptop fa-2x text-gray-300"></i>
						</div>
					</div>
				</div>
			</div>
		</div>
		<!-- Office Count -->
		<div class="col-xl-3 col-md-6 mb-4">
			<div class="card border-left-warning shadow h-100 py-2">
				<div class="card-body">
					<div class="row no-gutters align-items-center">
						<div class="col mr-2">
							<div class="text-xs font-weight-bold text-success text-uppercase mb-1"> Total Office</div>
							<div class="h5 mb-0 font-weight-bold text-gray-800">{{ $offices_count }}</div>
						</div>
						<div class="col-auto">
							<i class="fas fa-building fa-2x text-gray-300"></i>
						</div>
					</div>
				</div>
			</div>
		</div>
		<!-- User Count -->
		<div class="col-xl-3 col-md-6 mb-4">
			<div class="card border-left-danger shadow h-100 py-2">
				<div class="card-body">
					<div class="row no-gutters align-items-center">
						<div class="col mr-2">
							<div class="text-xs font-weight-bold text-warning text-uppercase mb-1"> Total Users</div>
							<div class="h5 mb-0 font-weight-bold text-gray-800">{{ $users_count }}</div>
						</div>
						<div class="col-auto">
							<i class="fas fa-users fa-2x text-gray-300"></i>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<!-- Content Row -->
	<div class="d-sm-flex align-items-end justify-content-between mb-4"></div>
	<div class="row">
		<!-- Bar Chart -->
		<div class="col-xl-10 col-lg-12 col-sm mx-auto">
			<div class="card shadow mb-4">
				<!-- Card Header - Dropdown -->
				<div id="assetHeader" class="card-header d-flex py-2 justify-content-between">
					<a id="print-button" class="btn border rounded btn-sm" href="javascript:void();"><i class="fas fa-download fa-sm"></i>&nbsp;&nbsp;Generate Report&nbsp;</a>
					<script src="{{ asset('app/print.js') }}"></script>
					<div class="d-none d-sm-inline-block border rounded">
						<form action="{{ route('home') }}" id="yearAInput">
							<select class="form-control bg-transparent border-0 btn-sm" name="year" onchange="document.getElementById('yearAInput').submit();"> @for ($i = 2020; $i <= 2099; $i++) <option value="{{ $i }}" {{ ($year == $i) ? "selected":"" }}>{{ $i }}</option> @endfor </select>
						</form>
					</div>
				</div>
				<!-- Card Body -->
				<div class="card-body">
					<div class="chart-legend text-center mb-4">
						<h4 class="h4">All Asset Records in {{ $year }}</h4>
					</div>
					<script>
						let as = @json($assets_pool);
					</script>
					<div class="chart-bar">
						<canvas id="Bar1"></canvas>
					</div>
					<script src="{{ asset('app/assetPool.js') }}"></script>
				</div>
			</div>
		</div>
	</div>
	<div class="row">
		<!-- Bar Chart -->
		<div class="col-xl-10 col-lg-12 col-sm mx-auto">
			<div class="card shadow mb-4">
				<!-- Card Body -->
				<div class="card-body">
					<div class="chart-legend text-center mb-4">
						<h4 class="h4">All Asset Transactions in {{ $year }}</h4>
					</div>
					<script>
						let hs = @json($histories_pool);
					</script>
					<div class="chart-bar">
						<canvas id="Bar2"></canvas>
					</div>
					<script src="{{ asset('app/historyPool.js') }}"></script>
				</div>
			</div>
		</div>
	</div>
</div> @endsection