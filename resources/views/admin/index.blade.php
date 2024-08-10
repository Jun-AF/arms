@extends('layouts.app') @section('content') 
<div class="container-fluid">
	<div class="row justify-content-md-between">
		<div class="col-xl-3 col-md-6 mb-4">
			<div class="card border-left-success shadow h-100 py-2">
				<div class="card-body">
					<div class="row no-gutters align-items-center">
						<div class="col mr-2">
							<div class="text-xs font-weight-bold text-success text-uppercase mb-1"> Total Admins</div>
							<div class="h5 mb-0 font-weight-bold text-gray-800">{{ $users_count }}</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="col-xl-2 col-md-6 mb-4 justify-content-between text-right">
			<a href="{{ route('person') }}" class="btn btn-secondary btn-sm shadow"><i class="fas fa-users"></i>&nbsp;&nbsp;User list</a>
		</div>
	</div>
	<div class="card mx-auto shadow h-100">
		<div class="card-header d-flex py-2 justify-content-between">
			<div>
				<i class="fas fa-user-shield"></i>&nbsp;&nbsp;Admin
			</div>
			<a href="{{ route('admin.create') }}" class="btn btn-secondary btn-sm"><i class="fas fa-plus"></i>&nbsp;&nbsp;New admin</a>
		</div>
		<div class="card-body">
			<div class="container-fluid">
				<div class="table-responsive">
					<table class="table table-hover" id="dataTable" width="100%" cellspacing="0">
					<thead>
						<tr class="text-center">
							<th>Name</th>
							<th>Email</th>
							<th>Role</th>
							<th style="max-width: 300px;">Actions</th>
						</tr>
					</thead>
					<tbody> @foreach ($users as $usr) <tr>
							<td class="align-middle">{{ $usr->name }}</td>
							<td class="align-middle">{{ $usr->email }}</td>
							<td class="align-middle">{{ $usr->role }}</td>
							<td class="align-middle">
								@if ($usr->id != 1)
								<div class="d-flex justify-content-center align-items-center">
									<a class="btn btn-outline-info btn-sm mr-2" href="{{ url('admin/edit_admin/'.$usr->id) }}"><i class="fas fa-pen"></i>&nbsp;&nbsp;Edit</a>
									<form action="{{ route('admin.editPassword') }}" method="post">
										@csrf
										<input type="hidden" name="id" value="{{ $usr->id }}">
										<button class="btn btn-outline-secondary btn-sm mr-2"><i class="fas fa-eye"></i>&nbsp;&nbsp;Edit password</button>
									</form>
									<button type="button" class="btn btn-danger btn-sm" data-toggle="modal" data-target="#delete-{{ $usr->id }}">
										<i class="fas fa-trash"></i>&nbsp;&nbsp;Delete</button>
								</div>
								@endif
							</td>
							<!-- Confirmation Modal-->
							<div class="modal fade" id="delete-{{ $usr->id }}" tabindex="-1" role="dialog" aria-labelledby="adminModalTitle" aria-hidden="true">
								<div class="modal-dialog" role="document">
									<div class="modal-content">
										<div class="modal-header bg-danger justify-content-md-center text-white">
											<i class="fas fa-exclamation-triangle"></i>&nbsp;&nbsp;Warning!
										</div>
										<div class="modal-body text-center">
											<h5>Delete this record?</h5>
											<div class="ml-auto mr-auto mt-4 mb-2">
												<button type="button" class="btn btn-outline-secondary btn-sm" onclick="$('#delete-{{ $usr->id }}').modal('hide');" data-toggle="modal" data-target="#deleteConfirm-{{ $usr->id }}"><i class="fas fa-check"></i>&nbsp;&nbsp;Yes</button>
												<button class="btn btn-primary btn-sm w-25" type="button" data-dismiss="modal"><i class="fas fa-times"></i>&nbsp;&nbsp;Cancel</button>
											</div>
										</div>
									</div>
								</div>
							</div>
							<!-- Delete Modal-->
							<div class="modal fade" id="deleteConfirm-{{ $usr->id }}" tabindex="-1" role="dialog" aria-labelledby="deleteConfirmTitle" aria-hidden="true">
								<div class="modal-dialog" role="document">
									<div class="modal-content">
										<div class="modal-body text-center">
											<h5><i class="fas fa-exclamation-triangle"></i>&nbsp;&nbsp;Password Required!</h5>
											<div class="ml-auto mr-autom mt-4 mb-2">
												<form id="adminRm-{{ $usr->id }}" action="{{ route('admin.delete') }}" method="POST"> @csrf @method('DELETE') <input type="text" hidden name="id" value="{{ $usr->id }}"> <div class="mt-2 mb-4">
														<input type="password" class="form-control" name="password" required>
													</div>
													<button type="button" class="btn btn-outline-secondary btn-sm" onclick="document.getElementById('adminRm-{{ $usr->id }}').submit()"><i class="fas fa-check"></i>&nbsp;&nbsp;Delete</button>
													<button class="btn btn-primary btn-sm w-25" type="button" data-dismiss="modal"><i class="fas fa-times"></i>&nbsp;&nbsp;Cancel</button>
												</form>
											</div>
										</div>
									</div>
								</div>
							</div>
						</tr> @endforeach </tbody>
				</table>
				</div>
			</div>
		</div>
	</div>
</div> @endsection