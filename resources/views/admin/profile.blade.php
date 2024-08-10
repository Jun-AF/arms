@extends('layouts.app') @section('content') 
	<div class="container-fluid">
	<div class="card w-75 mx-auto shadow h-100">
		<div class="card-header d-flex py-2 justify-content-between align-items-center">
			<div><i class="fas fa-user"></i>&nbsp;&nbsp;Profile</div>
			<a class="btn btn-sm btn-outline-secondary border rounded" href="{{ url('profile/edit/'.$user->id) }}"><i class="fas fa-pen"></i>&nbsp;&nbsp;Edit profile</a>
		</div>
		<div class="card-body">
			<div class="container-fluid">
				<div class="profile section mb-4">
					<div class="profile-image">
						<img src="{{ asset('img/undraw_profile.svg') }}" alt="">
					</div>
				</div>
				<div class="row mb-2 justify-content-center">
					<div class="col-md-1 col-sm-2 align-items-center">
						<label for="name">Name</label>
					</div>
					<div class="col-sm-1">:</div>
					<div class="col-md col-sm align-items-center text-right border-bottom">
						<label>{{ $user->name }}</label>
					</div>
				</div>
				<div class="row mb-2 justify-content-center">
					<div class="col-md-1 col-sm-2 align-items-center">
						<label for="name">Email</label>
					</div>
					<div class="col-sm-1">:</div>
					<div class="col-md col-sm align-items-center text-right border-bottom">
						<label>{{ $user->email }}</label>
					</div>
				</div>
				<div class="row mb-3 justify-content-center">
					<div class="col-md-1 col-sm-2 align-items-center">
						<label for="name">Role</label>
					</div>
					<div class="col-sm-1">:</div>
					<div class="col-md col-sm align-items-center text-right border-bottom">
						<label>{{ $user->role }}</label>
					</div>
				</div>
				<div class="row justify-content-center">
					<div class="col-md-1 col-sm-2 align-items-center">
						<label for="name">Password</label>
					</div>
					<div class="col-sm-1">:</div>
					<div class="col-md col-sm text-right align-items-center">
						<form action="{{ route('password.edit') }}" method="post">
							@csrf
							<button class="btn btn-sm p-0 alert-link small">Edit password</button>
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>
	</div> @endsection