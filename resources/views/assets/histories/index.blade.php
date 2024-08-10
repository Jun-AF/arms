@extends('layouts.app') @section('content')
<div class="container-fluid">
	<div class="row justify-content-md-left">
		<div class="col-xl-3 col-md-6 mb-4">
			<div class="card border-left-success shadow h-100 py-2">
				<div class="card-body">
					<div class="row no-gutters align-items-center">
						<div class="col mr-2">
							<div class="text-xs font-weight-bold text-success text-uppercase mb-1"> Standby</div>
							<div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stb }}</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="col-xl-3 col-md-6 mb-4">
			<div class="card border-left-success shadow h-100 py-2">
				<div class="card-body">
					<div class="row no-gutters align-items-center">
						<div class="col mr-2">
							<div class="text-xs font-weight-bold text-success text-uppercase mb-1"> In Use</div>
							<div class="h5 mb-0 font-weight-bold text-gray-800">{{ $non_stb }}</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="col-xl col-md-6 mb-4 justify-content-between text-right">
			<a href="{{ route('asset') }}" class="btn btn-secondary btn-sm shadow"><i class="fas fa-laptop"></i>&nbsp;&nbsp;Asset lists</a>
		</div>
	</div>
	<div class="card mx-auto shadow h-100">
		<div class="card-header d-flex py-2 justify-content-between">
			<div>
				<i class="fas fa-sync"></i>&nbsp;&nbsp;List
			</div>
			<div class="d-flex justify-content-end">
				<a href="{{ route('history.create') }}" class="btn btn-secondary btn-sm mr-2"><i class="fas fa-plus"></i>&nbsp;&nbsp;New transaction</a>
				<form action="{{ route('export.transaction') }}" method="post">
					@csrf
					<button class="btn btn-info btn-sm shadow">
						<i class="fas fa-download fa-sm"></i>
					</button>
				</form>
			</div>
		</div>
		<div class="card-body">
			<div class="container-fluid">
				<div class="table-responsive">
					<table class="table table-hover" id="dataTable" width="100%" cellspacing="0">
					<thead>
						<tr class="text-center">
							<th>Unique</th>
							<th>Name</th>
							<th>Sn</th>
							<th>User</th>
							<th>Transation type</th>
							<th>Office</th>
							<th>Transaction date</th>
							<th>Comment</th>
							<th>Actions</th>
						</tr>
					</thead>
					<tbody> @foreach ($histories as $hs) 
						<tr>
							<td class="align-middle">
								<a href="{{ url('transaction/detail/'.$hs->uniqueid) }}">{{ $hs->uniqueid }}</a>
							</td>
							<td class="align-middle">{{ $hs->asset_name }}</td>
							<td class="align-middle">{{ $hs->sn }}</td>
							<td class="align-middle">{{ $hs->name }}</td>
							<td class="align-middle">{{ $hs->transaction_type }}</td>
							<td class="align-middle">{{ $hs->office_name }}</td>
							<td class="align-middle">{{ $hs->transaction_date }}</td>
							<td class="align-middle">{{ $hs->comment }}</td>
							<td class="align-middle">
								<div class="d-flex justify-content-center align-items-center">
									<a class="btn btn-outline-secondary btn-sm mr-2" href="{{ url('transaction/edit_transaction/'.$hs->id) }}"><i class="fas fa-pen"></i>&nbsp;&nbsp;Edit</a>
									<button type="button" class="btn btn-danger btn-sm" data-toggle="modal" data-target="#delete-{{ $hs->id }}">
										<i class="fas fa-trash"></i>&nbsp;&nbsp;Delete</button>
								</div>
							</td>
							<!-- Delete Modal-->
							<div class="modal fade" id="delete-{{ $hs->id }}" tabindex="-1" role="dialog" aria-labelledby="personModalTitle" aria-hidden="true">
								<div class="modal-dialog" role="document">
									<div class="modal-content">
										<div class="modal-header bg-danger justify-content-md-center text-white">
											<i class="fas fa-exclamation-triangle"></i>&nbsp;&nbsp;Warning!
										</div>
										<div class="modal-body text-center">
											<h5>Delete this record?</h5>
											<div class="ml-auto mr-auto mt-4 mb-2">
												<form id="historyRm-{{ $hs->id }}" action="{{ route('history.delete') }}" method="POST"> @csrf @method('DELETE') 
													<input type="hidden" name="id" value="{{ $hs->id }}">
													<button type="button" class="btn btn-outline-secondary btn-sm" onclick="document.getElementById('historyRm-{{ $hs->id }}').submit()"><i class="fas fa-check"></i>&nbsp;&nbsp;Delete</button>
													<button class="btn btn-primary btn-sm w-25" type="button" data-dismiss="modal"><i class="fas fa-times"></i>&nbsp;&nbsp;Cancel</button>
												</form>
											</div>
										</div>
									</div>
								</div>
							</div>
						</tr>
						@endforeach
					</tbody>
				</table>
				</div>
			</div>
		</div>
	</div>
</div> @endsection