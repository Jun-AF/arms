@extends('layouts.app') @section('content')
<div class="container-fluid">
	<div class="card w-75 mx-auto shadow h-100">
		<div class="card-header d-flex py-2 justify-content-between align-items-center">
			<div><i class="fas fa-laptop"></i>&nbsp;&nbsp;Edit asset</div>
		</div>
		<div class="card-body">
			<div class="container-fluid">
				<form action="{{ route('asset.update') }}" method="POST"> 
					@csrf
					@method("PATCH")
					<input type="hidden" name="id" value="{{ $asset[0]->id }}">
					<div class="row mb-2 justify-content-center">
						<div class="col-md-3 col-sm-2 align-items-center text-right">
							<label for="asset">Asset name</label>
						</div>
						<div class="col-md col-sm align-items-center">
							<input type="text" class="form-control form-control-user @error('asset_name') is-invalid @enderror" name="asset_name" value="{{ $asset[0]->asset_name }}" required>
						</div>
					</div>
					<div class="row mb-2 justify-content-center">
						<div class="col-md-3 col-sm-2 align-items-center text-right">
							<label for="type">Type</label>
						</div>
						<div class="col-md col-sm align-items-center">
							<select id="type" class="form-control form-control-user @error('type') is-invalid @enderror" name="type"> @foreach ($type as $t) <option value="{{ $t }}" {{ ($asset[0]->type == $t) ? "selected":"" }}>{{ $t }}</option> @endforeach </select>
							<script src="{{ asset('app/mac.js') }}"></script>
						</div>
					</div>
					<div class="row mb-2 justify-content-center">
						<div class="col-md-3 col-sm-2 align-items-center text-right">
							<label for="serial num">Serial number</label>
						</div>
						<div class="col-md col-sm align-items-center">
							<input type="text" class="form-control form-control-user @error('sn') is-invalid @enderror" name="sn" value="{{ $asset[0]->sn }}" required>
						</div>
					</div>
					<div class="row mb-2 justify-content-center mca">
						<div class="col-md-3 col-sm-2 align-items-center text-right">
							<label for="os">OS</label>
						</div>
						<div class="col-md col-sm align-items-center">
							<input type="text" class="form-control form-control-user @error('os') is-invalid @enderror" name="os" value="{{ $asset[0]->os }}" required>
						</div>
					</div>
					<div class="row mb-2 justify-content-center mca">
						<div class="col-md-3 col-sm-2 align-items-center text-right">
							<label for="hostname">Hostname</label>
						</div>
						<div class="col-md col-sm align-items-center">
							<input type="text" class="form-control form-control-user @error('hostname') is-invalid @enderror" name="hostname" value="{{ $asset[0]->hostname }}" required>
						</div>
					</div>
					<div class="row mb-2 justify-content-center mca">
						<div class="col-md-3 col-sm-2 align-items-center text-right">
							<label for="mac">Mac address</label>
						</div>
						<div class="col-md col-sm align-items-center">
							<input type="text" class="form-control form-control-user @error('mac_address') is-invalid @enderror" name="mac_address" maxlength="17" placeholder="00:00:00:00:00:00" value="{{ $asset[0]->mac_address }}">
						</div>
					</div>
					<div class="row mb-2 justify-content-center">
						<div class="col-md-3 col-sm-2 align-items-center text-right">
							<label for="purchase date">Purchase date</label>
						</div>
						<div class="col-md col-sm align-items-center">
							<input type="date" class="form-control form-control-user @error('purchase_date') is-invalid @enderror" name="purchase_date" value="{{ $asset[0]->purchase_date }}">
						</div>
					</div>
					<div class="row mb-2 justify-content-center">
						<div class="col-md-3 col-sm-2 align-items-center text-right">
							<label for="office">Office</label>
						</div>
						<div class="col-md col-sm align-items-center">
							<select class="form-control form-control-user @error('office_id') is-invalid @enderror" name="office_id"> @foreach ($offices as $ofc) <option value="{{ $ofc->id }}" {{ ($ofc->id == $asset[0]->office_id) ? "selected":"" }}>{{ $ofc->office_name }}</option> @endforeach </select>
						</div>
					</div>
					<div class="row mb-4 justify-content-center">
						<div class="col-md-3 col-sm-2 align-items-center text-right">
							<label for="purchase date">Asset receive date</label>
						</div>
						<div class="col-md col-sm align-items-center">
							<input type="date" class="form-control form-control-user @error('asset_in') is-invalid @enderror" name="asset_in" value="{{ $asset[0]->asset_in }}">
						</div>
					</div>
					<div class="row justify-content-center">
						<div class="col-md-3">
							<button class="btn btn-primary btn-sm w-100">Save</button>
						</div>
						<div class="col-md-3">
							<a class="btn btn-outline-secondary btn-sm w-100" href="{{ route('asset') }}">Cancel</a>
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>
</div> @endsection