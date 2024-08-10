@extends('layouts.app') @section('content')
<div class="container-fluid">
	<div class="row justify-content-md-start">
		<div class="col-xl-2 col-md-2 mb-4">
			<div class="card shadow h-100 py-2">
				<div class="card-body">
					<div class="row no-gutters align-items-center">
						<div class="col mr-2 text-center">
							<div class="text-xs font-weight-bold text-success text-uppercase mb-1">Office</div>
							<div class="h5 mb-0 font-weight-bold text-gray-800">{{ $office[0]->office_name }}</div>
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
							<div class="text-xs font-weight-bold text-success text-uppercase mb-1"> Validated Asset</div>
							<div class="h5 mb-0 font-weight-bold text-gray-800">{{ $valids_count }}/{{ $total }}</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="card mx-auto shadow h-100">
		<div class="card-header d-flex py-2 justify-content-between">
			<div>
				<i class="fas fa-list"></i>&nbsp;&nbsp;Validation list
			</div>
			<div class="d-flex justify-content-end">
				<button class="btn btn-secondary btn-sm mr-2" data-toggle="modal" data-target="#tc">
						<i class="fas fa-trash fa-sm"></i>&nbsp;&nbsp;Delete validation</button>
				<form action="{{ route('export.validation') }}" method="post">
					@csrf
					<input type="hidden" name="office_name" value="{{ $office[0]->office_name }}">
					<button class="btn btn-info btn-sm shadow">
						<i class="fas fa-download fa-sm"></i>
					</button>
				</form>
				<!-- Confirmation Modal-->
				<div class="modal fade" id="tc" tabindex="-1" role="dialog" aria-labelledby="adminModalTitle" aria-hidden="true">
					<div class="modal-dialog" role="document">
						<div class="modal-content">
							<div class="modal-header bg-danger justify-content-md-center text-white">
								<i class="fas fa-exclamation-triangle"></i>&nbsp;&nbsp;Warning!
							</div>
							<div class="modal-body text-center">
								<h5>Delete this record?</h5>
								<div class="ml-auto mr-auto mt-4 mb-2">
									<button type="button" class="btn btn-outline-secondary btn-sm" onclick="$('#tc').modal('hide');" data-toggle="modal" data-target="#trucateConfirm"><i class="fas fa-check"></i>&nbsp;&nbsp;Yes</button>
									<button class="btn btn-primary btn-sm w-25" type="button" data-dismiss="modal"><i class="fas fa-times"></i>&nbsp;&nbsp;Cancel</button>
								</div>
							</div>
						</div>
					</div>
				</div>
				<!-- Delete Modal-->
				<div class="modal fade" id="trucateConfirm" tabindex="-1" role="dialog" aria-labelledby="truncateConfirmTitle" aria-hidden="true">
					<div class="modal-dialog" role="document">
						<div class="modal-content">
							<div class="modal-body text-center">
								<h5><i class="fas fa-exclamation-triangle"></i>&nbsp;&nbsp;Password Required!</h5>
								<div class="ml-auto mr-autom mt-4 mb-2">
									<form id="validRm" action="{{ route('validation.truncate') }}" method="POST"> @csrf @method('DELETE') <input type="hidden" name="office_id" value="{{ $office[0]->id }}"> <div class="mt-2 mb-4">
											<input type="password" class="form-control" name="password" required>
										</div>
										<button type="button" class="btn btn-outline-secondary btn-sm" onclick="document.getElementById('validRm').submit()"><i class="fas fa-check"></i>&nbsp;&nbsp;Delete</button>
										<button class="btn btn-primary btn-sm w-25" type="button" data-dismiss="modal"><i class="fas fa-times"></i>&nbsp;&nbsp;Cancel</button>
									</form>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="card-body">
			<div class="container-fluid">
				<div class="table-responsive">
					<table class="table table-hover" id="dataTable" width="100%" cellspacing="0">
					<thead>
						<tr class="text-center">
							<th>Name</th>
							<th>Serial number</th>
							<th>Office</th>
							<th>Condition</th>
							<th>Comment</th>
							<th>Validation period</th>
							<th style="max-width: 300px;">Actions</th>
						</tr>
					</thead>
					<tbody> @foreach ($validations as $valid) <tr>
							<td class="align-middle">{{ $valid->asset_name }}</td>
							<td class="align-middle">{{ $valid->sn }}</td>
							<td class="align-middle">{{ $valid->office_name }}</td>
							<td class="align-middle">{{ $valid->condition }}</td>
							<td class="align-middle">{{ $valid->comment }}</td>
							<td class="align-middle">{{ $valid->month_period }}</td>
							<td class="align-middle">
								<div class="d-flex justify-content-center align-items-center">
									@if ($valid->is_validate == false) 
										<button type="button" class="btn btn-outline-success btn-sm mr-2" data-toggle="modal" data-target="#valid-{{ $valid->validation_id }}"> Validate now! </button>
									@else 
										<button type="button" class="btn btn-success btn-sm mr-2" data-toggle="modal" data-target="#unvalid-{{ $valid->validation_id }}"><i class="fas fa-check"></i></button> 
										<button type="button" class="btn btn-secondary btn-sm" data-toggle="modal" data-target="#revalid-{{ $valid->validation_id }}">
											<i class="fas fa-pen"></i>&nbsp;&nbsp;Edit</button>
									@endif	
								</div>
								<!-- Validate Modal -->
								<div class="modal fade" id="valid-{{ $valid->validation_id }}" tabindex="-1" role="dialog" aria-labelledby="validationModalTitle1" aria-hidden="true">
									<div class="modal-dialog modal-dialog-centered" role="document">
										<div class="modal-content">
											<div class="modal-header">
												<h5 class="modal-title" id="validationModalTitle1">Asset info</h5>
												<button type="button" class="close" data-dismiss="modal" aria-label="Close">
													<span aria-hidden="true">&times;</span>
												</button>
											</div>
											<div class="modal-body">
												<div class="container">
													<form id="formValid-{{ $valid->validation_id }}" action="{{ route('validation.store') }}" method="POST"> @csrf <div class="row mb-2">
															<div class="col-xl-3 col-sm-2">
																<label for="asset">Asset name</label>
															</div>
															<div class="col-xl">
																<input type="hidden" name="asset_id" value="{{ $valid->id }}">
																<input class="form-control" type="text" value="{{ $valid->asset_name }}" readonly>
															</div>
														</div>
														<input type="hidden" name="validator_id" value="{{ Auth::id() }}">
														<div class="row mb-2">
															<div class="col-xl-3 col-sm-2">
																<label for="asset">Office name</label>
															</div>
															<div class="col-xl">
																<input type="hidden" name="office_id" value="{{ $valid->office_id }}">
																<input class="form-control" type="text" value="{{ $valid->office_name }}" readonly>
															</div>
														</div>
														<div class="row mb-2">
															<div class="col-xl-3 col-sm-2">
																<label for="asset">Condition</label>
															</div>
															<div class="col-xl">
																<select class="form-control" name="condition">
																	<option value="Good">Good</option>
																	<option value="Obsolete">Obsolete</option>
																	<option value="Broken">Broken</option>
																</select>
															</div>
														</div>
														<div class="row mb-2">
															<div class="col-xl-3 col-sm-2">
																<label for="asset">Comment</label>
															</div>
															<div class="col-xl">
																<input type="text" class="form-control" name="comment">
															</div>
														</div>
													</form>
												</div>
											</div>
											<div class="modal-footer">
												<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
												<button type="button" class="btn btn-primary" onclick="document.getElementById('formValid-{{ $valid->validation_id }}').submit();">Save changes</button>
											</div>
										</div>
									</div>
								</div>
								<!-- ReValidate Modal -->
								<div class="modal fade" id="revalid-{{ $valid->validation_id }}" tabindex="-1" role="dialog" aria-labelledby="validationModalTitle2" aria-hidden="true">
									<div class="modal-dialog modal-dialog-centered" role="document">
										<div class="modal-content">
											<div class="modal-header">
												<h5 class="modal-title" id="validationModalTitle2">Asset info</h5>
												<button type="button" class="close" data-dismiss="modal" aria-label="Close">
													<span aria-hidden="true">&times;</span>
												</button>
											</div>
											<div class="modal-body">
												<div class="container">
													<form id="formReValid-{{ $valid->validation_id }}" action="{{ url('validation/revalidate') }}" method="POST"> @csrf @method('PATCH') <div class="row mb-2">
															<div class="col-xl-3 col-sm-2">
																<label for="asset">Asset name</label>
															</div>
															<div class="col-xl">
																<input type="hidden" name="asset_id" value="{{ $valid->validation_id }}">
																<input class="form-control" type="text" value="{{ $valid->asset_name }}" readonly>
															</div>
														</div>
														<input type="hidden" name="validator_id" value="{{ Auth::id() }}">
														<div class="row mb-2">
															<div class="col-xl-3 col-sm-2">
																<label for="asset">Office name</label>
															</div>
															<div class="col-xl">
																<input type="hidden" name="office_id" value="{{ $valid->office_id }}">
																<input class="form-control" type="text" value="{{ $valid->office_name }}" readonly>
															</div>
														</div>
														<div class="row mb-2">
															<div class="col-xl-3 col-sm-2">
																<label for="asset">Condition</label>
															</div>
															<div class="col-xl">
																<select class="form-control" name="condition">
																	<option value="Good" {{ ($valid->condition == "Good") ? "selected":"" }}>Good</option>
																	<option value="Obsolete" {{ ($valid->condition == "Obsolete") ? "selected":"" }}>Obsolete</option>
																	<option value="Broken" {{ ($valid->condition == "Broken") ? "selected":"" }}>Broken</option>
																</select>
															</div>
														</div>
														<div class="row mb-2">
															<div class="col-xl-3 col-sm-2">
																<label for="asset">Comment</label>
															</div>
															<div class="col-xl">
																<input type="text" class="form-control" name="comment" value="{{ $valid->comment }}">
															</div>
														</div>
													</form>
												</div>
											</div>
											<div class="modal-footer">
												<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
												<button type="button" class="btn btn-primary" onclick="document.getElementById('formReValid-{{ $valid->validation_id }}').submit();">Update record?</button>
											</div>
										</div>
									</div>
								</div>
								<!-- Delete Modal-->
								<div class="modal fade" id="unvalid-{{ $valid->validation_id }}" tabindex="-1" role="dialog" aria-labelledby="validationModalTitle3" aria-hidden="true">
									<div class="modal-dialog" role="document">
										<div class="modal-content">
											<div class="modal-header bg-danger justify-content-md-center text-white">
												<i class="fas fa-exclamation-triangle"></i>&nbsp;&nbsp;Warning!
											</div>
											<div class="modal-body text-center">
												<h5>Unvalidate this record?</h5>
												<div class="ml-auto mr-auto mt-4 mb-2">
													<form id="rmValidation-{{ $valid->validation_id }}" action="{{ route('validation.delete') }}" method="POST"> 
														@csrf 
														@method('DELETE')
														<input type="hidden" name="id" value="{{ $valid->validation_id }}">
														<button class="btn btn-outline-secondary btn-sm" type="button" data-dismiss="modal">Cancel</button>
														<button class="btn btn-primary btn-sm w-25" onclick="document.getElementById('rmValidation-{{ $valid->validation_id }}').submit()">Delete</button>
													</form>
												</div>
											</div>
										</div>
									</div>
								</div>
							</td>
						</tr> @endforeach 
					</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
</div> @endsection