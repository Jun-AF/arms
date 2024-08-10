@extends('layouts.app') @section('content')
<div class="container-fluid">
	<div class="card w-75 mx-auto shadow h-100 mb-4">
		<div class="card-header d-flex py-2 justify-content-between align-items-center">
			<div><i class="fas fa-laptop"></i>&nbsp;&nbsp;Asset</div>
		</div>
		<div class="card-body">
			<div class="container-fluid">
				<div class="row mb-2 justify-content-center">
					<div class="col-md-2 col-sm-2 align-items-center">
						<label for="unique">Unique id</label>
					</div>
					<div class="col-sm-1">:</div>
					<div class="col-md col-sm align-items-center text-right border-bottom">
						<label>{{ $asset->uniqueid }}</label>
					</div>
				</div>
				<div class="row mb-2 justify-content-center">
					<div class="col-md-2 col-sm-2 align-items-center">
						<label for="asset name">Asset name</label>
					</div>
					<div class="col-sm-1">:</div>
					<div class="col-md col-sm align-items-center text-right border-bottom">
						<label>{{ $asset->asset_name }}</label>
					</div>
				</div>
				<div class="row mb-2 justify-content-center">
					<div class="col-md-2 col-sm-2 align-items-center">
						<label for="sn">SN</label>
					</div>
					<div class="col-sm-1">:</div>
					<div class="col-md col-sm align-items-center text-right border-bottom">
						<label>{{$asset->sn }}</label>
					</div>
				</div>
				<div class="row mb-2 justify-content-center">
					<div class="col-md-2 col-sm-2 align-items-center">
						<label for="type">Type</label>
					</div>
					<div class="col-sm-1">:</div>
					<div class="col-md col-sm align-items-center text-right border-bottom">
						<label>{{ $asset->type }}</label>
					</div>
				</div>
				<div class="row mb-3 justify-content-center">
					<div class="col-md-2 col-sm-2 align-items-center">
						<label for="office">Location</label>
					</div>
					<div class="col-sm-1">:</div>
					<div class="col-md col-sm align-items-center text-right border-bottom">
						<label>{{$asset->office_name }}</label>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="card mx-auto shadow h-100">
		<div class="card-header d-flex py-2 justify-content-between">
			<div>
				<i class="fas fa-list"></i>&nbsp;&nbsp;List
			</div>
			<div class="justify-content-end">
				<a href="{{ url('transaction/'.$asset->unique.'/new_transaction') }}" class="btn btn-secondary btn-sm"><i class="fas fa-plus"></i>&nbsp;&nbsp;New transaction</a>
				<a href="#" class="btn btn-info btn-sm shadow">
					<i class="fas fa-download fa-sm"></i></a>
			</div>
		</div>
		<div class="card-body">
			<div class="container-fluid">
				<div class="table-responsive">
					<table class="table table-hover" id="dataTable" width="100%" cellspacing="0">
					<thead>
						<tr class="text-center">
							<th>Name</th>
							<th>Sn</th>
							<th>User</th>
							<th>Transation type</th>
							<th>Office</th>
							<th>Transaction date</th>
							<th>Comment</th>
							<th style="width: 150px;">Actions</th>
						</tr>
					</thead>
					<tbody> @foreach ($histories as $hs) <tr>
							<td class="align-middle">{{ $hs->asset_name }}</td>
							<td class="align-middle">{{ $hs->sn }}</td>
							<td class="align-middle">{{ $hs->name }}</td>
							<td class="align-middle">{{ $hs->transaction_type }}</td>
							<td class="align-middle">{{ $hs->office_name }}</td>
							<td class="align-middle">{{ $hs->transaction_date }}</td>
							<td class="align-middle">{{ $hs->comment }}</td>
							<td class="align-middle">
								<div class="d-flex justify-content-center align-items-center">
									<a class="btn btn-outline-secondary btn-sm mr-2" href="{{ url('transaction/'.$hs->unique.'/edit_transaction',$hs->id) }}"><i class="fas fa-pen"></i>&nbsp;&nbsp;Edit</a>
									<button type="button" class="btn btn-danger btn-sm" data-toggle="modal" data-target="#delete-{{ $hs->id }}">
										<i class="fas fa-trash"></i>&nbsp;&nbsp;Delete</button>
								</div>
							</td>
							<!-- Delete Modal-->
							<div class="modal fade" id="delete-{{ $hs->id }}" tabindex="-1" role="dialog" aria-labelledby="transactionModalTitle" aria-hidden="true">
								<div class="modal-dialog" role="document">
									<div class="modal-content">
										<div class="modal-header bg-danger text-white">
											<i class="fas fa-exclamation-triangle"></i>&nbsp;Warning! Confirmation required
										</div>
										<div class="modal-body text-center">
											<h5>Delete this record?</h5>
											<div class="ml-auto mr-auto mt-4 mb-2">
												<form action="{{ url('transaction/'.$hs->unique.'/'.$hs->id) }}" method="POST"> @csrf @method('DELETE') <button class="btn btn-primary btn-sm" type="button" data-dismiss="modal">Cancel</button>
													<button class="btn btn-outline-danger btn-sm">Delete</button>
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
</div> @endsection