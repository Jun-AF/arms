@extends('layouts.app') @section('content')
<div class="container-fluid">
	<div class="card w-75 mx-auto shadow h-100">
		<div class="card-header d-flex py-2 justify-content-between align-items-center">
			<div><i class="fas fa-user-shield"></i>&nbsp;&nbsp;New admin</div>
		</div>
		<div class="card-body">
			<div class="container-fluid">
				<form id="adminForm" action="{{ route('admin.store') }}" method="POST"> 
					@csrf 
					<div class="row mb-2 justify-content-center">
						<div class="col-md-3 col-sm-2 align-items-center text-right">
							<label for="name">Name</label>
						</div>
						<div class="col-md col-sm align-items-center">
							<input type="text" class="form-control form-control-user @error('name') is-invalid @enderror" name="name" required>
						</div>
					</div>
					<div class="row mb-2 justify-content-center">
						<div class="col-md-3 col-sm-2 align-items-center text-right">
							<label for="email">Email</label>
						</div>
						<div class="col-md col-sm align-items-center">
							<input type="email" class="form-control form-control-user @error('email') is-invalid @enderror" name="email" required>
						</div>
					</div>
					<div class="row mb-2 justify-content-center">
						<div class="col-md-3 col-sm-2 align-items-center text-right">
							<label for="password">Password</label>
						</div>
						<div class="col-md col-sm align-items-center">
							<div class="input-group">
                                <input type="password" id="Password" class="form-control form-control-user @error('password') is-invalid @enderror" name="password" minlength="6" aria-describedby="showPassword" required>
                                <span class="input-group-text" id="showPassword">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-eye" viewBox="0 0 16 16">
                                        <path d="M16 8s-3-5.5-8-5.5S0 8 0 8s3 5.5 8 5.5S16 8 16 8M1.173 8a13 13 0 0 1 1.66-2.043C4.12 4.668 5.88 3.5 8 3.5s3.879 1.168 5.168 2.457A13 13 0 0 1 14.828 8q-.086.13-.195.288c-.335.48-.83 1.12-1.465 1.755C11.879 11.332 10.119 12.5 8 12.5s-3.879-1.168-5.168-2.457A13 13 0 0 1 1.172 8z"></path>
                                        <path d="M8 5.5a2.5 2.5 0 1 0 0 5 2.5 2.5 0 0 0 0-5M4.5 8a3.5 3.5 0 1 1 7 0 3.5 3.5 0 0 1-7 0"></path>
                                    </svg>
                                </span>
                            </div>
						</div>
					</div>
					<div class="row mb-2 justify-content-center">
						<div class="col-md-3 col-sm-2 align-items-center text-right">
							<label for="role">Role</label>
						</div>
						<div class="col-md col-sm align-items-center">
							<select class="form-control form-control-user @error('role') is-invalid @enderror" name="role" required>
								<option value="Super admin">Super admin</option>
								<option value="Admin">Admin</option>
							</select>
						</div>
					</div>
					<div class="row justify-content-center">
						<div class="col-md-3">
							<span id="Submit" class="btn btn-primary btn-sm w-100">Save</span>
						</div>
						<div class="col-md-3">
							<a class="btn btn-outline-secondary btn-sm w-100" href="{{ route('admin') }}">Cancel</a>
						</div>
					</div>
					<script src="{{ asset('app/password.js') }}"></script>
				</form>
			</div>
		</div>
	</div>
</div>
@endsection