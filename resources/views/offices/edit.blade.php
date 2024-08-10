@extends('layouts.app') @section('content')
<div class="container-fluid">
	<div class="card w-75 mx-auto shadow h-100">
		<div class="card-header d-flex py-2 justify-content-between align-items-center">
			<div><i class="fas fa-building"></i>&nbsp;&nbsp;Office update</div>
		</div>
		<div class="card-body">
			<div class="container-fluid">
				<form action="{{ route('office.update') }}" method="POST"> 
					@csrf
					@method('PATCH')
					<input type="hidden" name="id" value="{{ $offices->id }}">
					<div class="row mb-2 justify-content-center">
						<div class="col-md-3 col-sm-2 align-items-center text-right">
							<label for="office name">Office name</label>
						</div>
						<div class="col-md col-sm align-items-center">
							<input type="text" class="form-control form-control-user @error('office_name') is-invalid @enderror" name="office_name" value="{{ $offices->office_name }}" required>
						</div>
					</div>
					<div class="row mb-4 justify-content-center">
						<div class="col-md-3 col-sm-2 align-items-center text-right">
							<label for="location">Location</label>
						</div>
						<div class="col-md col-sm align-items-center">
							<input type="text" class="form-control form-control-user @error('location') is-invalid @enderror" name="location" value="{{ $offices->location }}" required>
						</div>
					</div>
					<div class="row justify-content-center">
						<div class="col-md-3">
							<button class="btn btn-primary btn-sm w-100">Save</button>
						</div>
						<div class="col-md-3">
							<a class="btn btn-outline-secondary btn-sm w-100" href="{{ route('office') }}">Cancel</a>
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>
@endsection