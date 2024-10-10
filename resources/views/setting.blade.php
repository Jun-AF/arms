@extends('layouts.setting') @section('content') <div class="container-fluid mt-5">
	<div class="card w-75 mx-auto shadow mb-4">
		<div class="card-header py-3">
			<i class="fas fa-user"></i>&nbsp;Profile
		</div>
		<div class="card-body">
			<div class="profile section">
				<div class="profile-image">
					<img src="{{ asset('img/undraw_profile.svg') }}" alt="">
				</div>
				<div class="profile-user">
					{{ Auth::user()->name }}
				</div>
			</div>
			<div class="row p-2">
				<div class="col-xl-1 col-sm-0 lead text-right">
					<i class="fas fa-user-cog"></i>
				</div>
				<div class="col-xl col-sm-12 lead">Update Profile</div>
				<div class="col-xl-2 col-sm-12">
					<a class="btn btn-info btn-sm w-100" href="{{ url('profile/edit/'.Auth::user()->id) }}"><i class="fas fa-pen"></i>&nbsp;&nbsp;Edit</a>
				</div>
			</div>
			<hr>
			<div class="row p-2">
				<div class="col-xl-1 col-sm-0 lead text-right">
					<i class="fas fa-shield-alt"></i>
				</div>
				<div class="col-xl col-sm-12 lead">Update password</div>
				<div class="col-xl-2 col-sm-12">
					<form action="{{ route('password.edit') }}" method="post">
						@csrf
						<button class="btn btn-info btn-sm w-100"><i class="fas fa-pen"></i>&nbsp;&nbsp;Edit</button>
					</form>
				</div>
			</div>
		</div>
	</div>
	{{-- <div class="card w-75 mx-auto shadow mb-4">
		<div class="card-header py-3">
			<i class="fas fa-table"></i>&nbsp;Tables Operation
		</div>
		<div class="card-body">
			<div class="row p-2">
				<div class="col-1 col-sm-0 lead text-right">
					<i class="fas fa-building"></i>
				</div>
				<div class="col-xl col-sm-12 lead">Office Upload</div>
				<div class="col-xl-2 col-sm-12">
					<a class="btn btn-outline-secondary btn-sm w-100" href="{{ route('download.office') }}"><i class="fas fa-sticky-note"></i>&nbsp;&nbsp;template</a>
				</div>
				<div class="col-xl-2 col-sm-12">
					<button type="button" class="btn btn-info btn-sm w-100 text-white" data-toggle="modal" data-target="#OfficeUp">
						<i class="fas fa-upload"></i>&nbsp;&nbsp;upload </button>
				</div>
				<div class="modal fade" id="OfficeUp" tabindex="-1" role="dialog" aria-labelledby="UpModalTitle" aria-hidden="true">
					<div class="modal-dialog" role="document">
						<div class="modal-content">
							<div class="modal-header bg-danger justify-content-md-center text-white">
								<i class="fas fa-upload"></i>&nbsp;&nbsp;Select office (.csv) file
							</div>
							<div class="modal-body text-center">
								<form id="officeFormUpload" action="{{ route('import.office') }}" method="post">
									@csrf
									<input type="file" class="form-control" name="file" id="file" required>
								</form>
								<div class="ml-auto mr-autom mt-4 mb-2">
									<button type="button" class="btn btn-outline-secondary btn-sm" onclick="$('#OfficeUp').modal('hide');" data-dismiss="modal">Cancel</button>
									<button class="btn btn-primary btn-sm w-52" type="button" onclick="document.getElementById('officeFormUpload').submit()">Upload</button>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="row p-2">
				<div class="col-1 col-sm-0 lead text-right">
					<i class="fas fa-users"></i>
				</div>
				<div class="col-xl col-sm-12 lead">User Upload</div>
				<div class="col-xl-2 col-sm-12">
					<a class="btn btn-outline-secondary btn-sm w-100" href="{{ route('download.user') }}"><i class="fas fa-sticky-note"></i>&nbsp;&nbsp;template</a>
				</div>
				<div class="col-xl-2 col-sm-12">
					<button type="button" class="btn btn-info btn-sm w-100 text-white" data-toggle="modal" data-target="#UserUp">
						<i class="fas fa-upload"></i>&nbsp;&nbsp;upload </button>
				</div>
				<div class="modal fade" id="UserUp" tabindex="-1" role="dialog" aria-labelledby="UpModalTitle" aria-hidden="true">
					<div class="modal-dialog" role="document">
						<div class="modal-content">
							<div class="modal-header bg-danger justify-content-md-center text-white">
								<i class="fas fa-upload"></i>&nbsp;&nbsp;Select user (.csv) file
							</div>
							<div class="modal-body text-center">
								<form id="userFormUpload" action="{{ route('import.user') }}" method="post">
									@csrf
									<input type="file" class="form-control" name="file" id="file" required>
								</form>
								<div class="ml-auto mr-autom mt-4 mb-2">
									<button type="button" class="btn btn-outline-secondary btn-sm" onclick="$('#UserUp').modal('hide');" data-dismiss="modal">Cancel</button>
									<button class="btn btn-primary btn-sm w-52" type="button" onclick="document.getElementById('userFormUpload').submit()">Upload</button>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="row p-2">
				<div class="col-1 col-sm-0 lead text-right">
					<i class="fas fa-laptop"></i>
				</div>
				<div class="col-xl col-sm-12 lead">Asset Upload</div>
				<div class="col-xl-2 col-sm-12">
					<a class="btn btn-outline-secondary btn-sm w-100" href="{{ route('download.asset') }}"><i class="fas fa-sticky-note"></i>&nbsp;&nbsp;template</a>
				</div>
				<div class="col-xl-2 col-sm-12">
					<button type="button" class="btn btn-info btn-sm w-100 text-white" data-toggle="modal" data-target="#AssetUp">
						<i class="fas fa-upload"></i>&nbsp;&nbsp;upload </button>
				</div>
				<div class="modal fade" id="AssetUp" tabindex="-1" role="dialog" aria-labelledby="UpModalTitle" aria-hidden="true">
					<div class="modal-dialog" role="document">
						<div class="modal-content">
							<div class="modal-header bg-danger justify-content-md-center text-white">
								<i class="fas fa-upload"></i>&nbsp;&nbsp;Select asset (.csv) file
							</div>
							<div class="modal-body text-center">
								<form id="assetFormUpload" action="{{ route('import.asset') }}" method="post">
									@csrf
									<input type="file" class="form-control" name="file" id="file" required>
								</form>
								<div class="ml-auto mr-autom mt-4 mb-2">
									<button type="button" class="btn btn-outline-secondary btn-sm" onclick="$('#AssetUp').modal('hide');" data-dismiss="modal">Cancel</button>
									<button class="btn btn-primary btn-sm w-52" type="button" onclick="document.getElementById('assetFormUpload').submit()">Upload</button>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="row p-2">
				<div class="col-1 col-sm-0 lead text-right">
					<i class="fas fa-sync"></i>
				</div>
				<div class="col-xl col-sm-12 lead">Transaction Upload</div>
				<div class="col-xl-2 col-sm-12">
					<a class="btn btn-outline-secondary btn-sm w-100" href="{{ route('download.transaction') }}"><i class="fas fa-sticky-note"></i>&nbsp;&nbsp;template</a>
				</div>
				<div class="col-xl-2 col-sm-12">
					<button type="button" class="btn btn-info btn-sm w-100 text-white" data-toggle="modal" data-target="#TransactionUp">
						<i class="fas fa-upload"></i>&nbsp;&nbsp;upload </button>
				</div>
				<div class="modal fade" id="TransactionUp" tabindex="-1" role="dialog" aria-labelledby="UpModalTitle" aria-hidden="true">
					<div class="modal-dialog" role="document">
						<div class="modal-content">
							<div class="modal-header bg-danger justify-content-md-center text-white">
								<i class="fas fa-upload"></i>&nbsp;&nbsp;Select transaction (.csv) file
							</div>
							<div class="modal-body text-center">
								<form id="transactionFormUpload" action="{{ route('import.transaction') }}" method="post">
									@csrf
									<input type="file" class="form-control" name="file" id="file" required>
								</form>
								<div class="ml-auto mr-autom mt-4 mb-2">
									<button type="button" class="btn btn-outline-secondary btn-sm" onclick="$('#TransactionUp').modal('hide');" data-dismiss="modal">Cancel</button>
									<button class="btn btn-primary btn-sm w-52" type="button" onclick="document.getElementById('transactionFormUpload').submit()">Upload</button>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="row p-2">
				<div class="col-1 col-sm-0 lead text-right">
					<i class="fas fa-list"></i>
				</div>
				<div class="col-xl col-sm-12 lead">All Tables Truncatenation</div>
				<div class="col-xl-2 col-sm-12">
					<button type="button" class="btn btn-danger btn-sm w-100 text-white" data-toggle="modal" data-target="#TblTr">
						<i class="fas fa-trash"></i>&nbsp;&nbsp;remove all </button>
				</div>
			</div>
			<!-- Delete Modal-->
			<div class="modal fade" id="TblTr" tabindex="-1" role="dialog" aria-labelledby="TrModalTitle" aria-hidden="true">
				<div class="modal-dialog" role="document">
					<div class="modal-content">
						<div class="modal-header bg-danger justify-content-md-center text-white">
							<i class="fas fa-exclamation-triangle"></i>&nbsp;&nbsp;Warning!
						</div>
						<div class="modal-body text-center">
							<h5>Delete this record?</h5>
							<div class="ml-auto mr-autom mt-4 mb-2">
								<button type="button" class="btn btn-outline-secondary btn-sm" onclick="$('#TblTr').modal('hide');" data-toggle="modal" data-target="#Tr">Yes</button>
								<button class="btn btn-primary btn-sm w-52" type="button" data-dismiss="modal">Cancel</button>
							</div>
						</div>
					</div>
				</div>
			</div>
			<!-- Confirm Modal-->
			<div class="modal fade" id="Tr" tabindex="-1" role="dialog" aria-labelledby="TrModalTitle" aria-hidden="true">
				<div class="modal-dialog" role="document">
					<div class="modal-content">
						<div class="modal-body text-center">
							<h5><i class="fas fa-exclamation-triangle"></i>&nbsp;&nbsp;Password Required!</h5>
							<div class="ml-auto mr-auto mt-4 mb-2">
								<form id="Rm" action="{{ route('setting.truncate') }}" method="POST"> @csrf @method('DELETE') <div class="mt-2 mb-4">
										<input type="password" class="form-control" name="password" required>
									</div>
									<button type="button" class="btn btn-outline-secondary btn-sm" onclick="document.getElementById('Rm').submit()">Delete</button>
									<button class="btn btn-primary btn-sm w-25" type="button" data-dismiss="modal">Cancel</button>
								</form>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div> --}}
	<div class="w-50 mx-auto">
		<form action="{{ route('logout') }}" method="post"> @csrf <button class="btn btn-secondary h5 w-100">Sign Out</button>
		</form>
	</div>
</div> @endsection