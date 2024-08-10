@extends('layouts.app') @section('content')
<div class="container-fluid">
	<div class="card w-75 mx-auto shadow h-100">
		<div class="card-header d-flex py-2 justify-content-between align-items-center">
			<div><i class="fas fa-user"></i>&nbsp;&nbsp;Update User</div>
		</div>
		<div class="card-body">
			<div class="container-fluid">
				<form id="editForm" action="{{ route('person.update') }}" method="POST"> 
					@csrf
					@method('PATCH')
					<input type="hidden" name="id" value="{{ $people->id }}">
					<div class="row mb-2 justify-content-center">
						<div class="col-md-3 col-sm-2 align-items-center text-right">
							<label for="name">Name</label>
						</div>
						<div class="col-md col-sm align-items-center">
							<input type="text" class="form-control form-control-user @error('name') is-invalid @enderror" name="name" value="{{ $people->name }}" required>
						</div>
					</div>
					<div class="row mb-2 justify-content-center">
						<div class="col-md-3 col-sm-2 align-items-center text-right">
							<label for="job title">Job title</label>
						</div>
						<div class="col-md col-sm align-items-center">
							<input type="text" class="form-control form-control-user @error('job_title') is-invalid @enderror" name="job_title" value="{{ $people->job_title }}">
						</div>
					</div>
					<div class="row mb-4 justify-content-center">
						<div class="col-md-3 col-sm-2 align-items-center text-right">
							<label for="location">Location</label>
						</div>
						<div class="col-md col-sm align-items-center">
							<select class="form-control form-control-user @error('office_id') is-invalid @enderror" name="office_id" required> @foreach ($offices as $ofc) <option value="{{ $ofc->id }}" {{ ($ofc->id == $people->office_id) ? 'selected':'' }}>{{ $ofc->office_name }}</option> @endforeach </select>
						</div>
					</div>
					<div class="row justify-content-center">
						<div class="col-md-3">
							<button type="button" class="btn btn-primary btn-sm w-100" onclick="document.getElementById('editForm').submit()">Save</button>
						</div>
						<div class="col-md-3">
							<a class="btn btn-outline-secondary btn-sm w-100" href="{{ route('person') }}">Cancel</a>
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>@endsection