@extends('layouts.app') @section('content')
<div class="container-fluid">
	<div class="card w-75 mx-auto shadow h-100">
		<div class="card-header d-flex py-2 justify-content-between align-items-center">
			<div><i class="fas fa-user-shield"></i>&nbsp;&nbsp;New admin</div>
		</div>
		<div class="card-body">
			<div class="container-fluid">
				<form id="editForm" action="{{ route('admin.update') }}" method="POST"> 
					@csrf
					@method('PATCH')
					<input type="hidden" name="id" value="{{ $user->id }}" required>
					<div class="row mb-2 justify-content-center">
						<div class="col-md-3 col-sm-2 align-items-center text-right">
							<label for="name">Name</label>
						</div>
						<div class="col-md col-sm align-items-center">
							<input type="text" class="form-control form-control-user @error('name') is-invalid @enderror" name="name" value="{{ $user->name }}" required>
						</div>
					</div>
					<div class="row mb-2 justify-content-center">
						<div class="col-md-3 col-sm-2 align-items-center text-right">
							<label for="email">Email</label>
						</div>
						<div class="col-md col-sm align-items-center">
							<input type="email" class="form-control form-control-user @error('email') is-invalid @enderror" name="email" value="{{ $user->email }}" required>
						</div>
					</div>
					<div class="row mb-2 justify-content-center">
						<div class="col-md-3 col-sm-2 align-items-center text-right">
							<label for="role">Role</label>
						</div>
						<div class="col-md col-sm align-items-center">
							<select class="form-control form-control-user @error('role') is-invalid @enderror" name="role" required>
								<option value="Super admin" {{ ($user->role == 'Super admin') ? 'selected':'' }}>Super admin</option> @if (Auth::id() == 1) <option value="Admin" {{ ($user->role == 'Admin') ? 'selected':'' }}>Admin</option> @endif
							</select>
						</div>
					</div>
					<div class="row justify-content-center">
						<div class="col-md-3">
							<button type="button" class="btn btn-primary btn-sm w-100" onclick="document.getElementById('editForm').submit()">Save</button>
						</div>
						<div class="col-md-3">
							<a class="btn btn-outline-secondary btn-sm w-100" href="{{ route('admin') }}">Cancel</a>
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>
</div> 
@endsection