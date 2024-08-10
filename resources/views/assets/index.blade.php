@extends('layouts.app') @section('content')
<div class="container-fluid">
	<div class="row justify-content-md-between">
		<div class="col-xl-3 col-md-6 mb-4">
			<div class="card border-left-success shadow h-100 py-2">
				<div class="card-body">
					<div class="row no-gutters align-items-center">
						<div class="col mr-2">
							<div class="text-xs font-weight-bold text-success text-uppercase mb-1"> Total Assets</div>
							<div class="h5 mb-0 font-weight-bold text-gray-800">{{ $assets_count }}</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="col-xl-2 col-md-6 mb-4 justify-content-between text-right">
			<a href="{{ route('history') }}" class="btn btn-secondary btn-sm shadow"><i class="fas fa-sync"></i>&nbsp;&nbsp;Transaction lists</a>
		</div>
	</div>
	<div class="card mx-auto shadow h-100">
		<div class="card-header d-flex py-2 justify-content-between">
			<div>
				<i class="fas fa-laptop"></i>&nbsp;&nbsp;Asset
			</div>
			<a href="{{ route('asset.create') }}" class="btn btn-secondary btn-sm"><i class="fas fa-plus"></i>&nbsp;&nbsp;New asset</a>
		</div>
		<div class="card-body">
			<div class="container-fluid">
				<div class="table-responsive">
					<table class="table table-hover" id="dataTable" width="100%" cellspacing="0">
					<thead>
						<tr class="text-center">
							<th>Unique</th>
							<th>Name</th>
							<th>Type</th>
							<th>Sn</th>
							<th>OS</th>
							<th>Hostname</th>
							<th>Mac address</th>
							<th>Office</th>
							<th>Purchased date</th>
							<th>Asset in</th>
							<th style="width: 150px;">Actions</th>
						</tr>
					</thead>
					<tbody> @foreach ($assets as $ast) <tr>
							<td class="align-middle">{{ $ast->uniqueid }}</td>
							<td class="align-middle">{{ $ast->asset_name }}</td>
							<td class="align-middle">{{ $ast->type }}</td>
							<td class="align-middle">{{ $ast->sn }}</td>
							<td class="align-middle">{{ $ast->os }}</td>
							<td class="align-middle">{{ $ast->hostname }}</td>
							<td class="align-middle">{{ $ast->mac_address }}</td>
							<td class="align-middle">{{ $ast->office_name }}</td>
							<td class="align-middle">{{ $ast->purchase_date }}</td>
							<td class="align-middle">{{ $ast->asset_in }}</td>
							<td class="align-middle">
								<div class="d-flex justify-content-center align-items-center">
									<a class="btn btn-outline-secondary btn-sm mr-2" href="{{ url('asset/edit_asset/'.$ast->id) }}"><i class="fas fa-pen"></i>&nbsp;&nbsp;Edit</a>
									<button type="button" class="btn btn-danger btn-sm" data-toggle="modal" data-target="#delete-{{ $ast->id }}">
										<i class="fas fa-trash"></i>&nbsp;&nbsp;Delete</button>
								</div>
							</td>
							<!-- Delete Modal-->
							<div class="modal fade" id="delete-{{ $ast->id }}" tabindex="-1" role="dialog" aria-labelledby="assetModalTitle" aria-hidden="true">
								<div class="modal-dialog" role="document">
									<div class="modal-content">
										<div class="modal-header bg-danger justify-content-md-center text-white">
											<i class="fas fa-exclamation-triangle"></i>&nbsp;&nbsp;Warning!
										</div>
										<div class="modal-body text-center">
											<h5>Delete this record?</h5>
											<div class="ml-auto mr-auto mt-4 mb-2">
												<form id="assetRm-{{ $ast->id }}" action="{{ route('asset.delete') }}" method="POST"> @csrf @method('DELETE') 
													<input type="hidden" name="id" value="{{ $ast->id }}">
													<button type="button" class="btn btn-outline-secondary btn-sm" onclick="document.getElementById('assetRm-{{ $ast->id }}').submit()"><i class="fas fa-check"></i>&nbsp;&nbsp;Delete</button>
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