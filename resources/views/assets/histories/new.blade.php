@extends('layouts.app') @section('content')
<div class="container-fluid">
	<div class="card w-75 mx-auto shadow h-100">
		<div class="card-header d-flex py-2 justify-content-between align-items-center">
			<div><i class="fas fa-list"></i>&nbsp;&nbsp;New transaction</div>
		</div>
		<div class="card-body">
			<div class="container-fluid">
				<form action="{{ route('history.store') }}" method="POST"> 
					@csrf 
					<div class="row mb-2 justify-content-center">
						<div class="col-md-3 col-sm-2 align-items-center text-right">
							<label for="asset">Asset name</label>
						</div>
						<div class="col-md col-sm align-items-center">
							<select class="form-control @error('asset_id') is-invalid @enderror" name="asset_id"> @foreach ($assets as $ast) <option value="{{ $ast->id }}">{{ $ast->asset_name .' - '. $ast->sn }}</option> @endforeach </select>
						</div>
					</div>
					<div class="row mb-2 justify-content-center">
						<div class="col-md-3 col-sm-2 align-items-center text-right">
							<label for="type">Transaction type</label>
						</div>
						<div class="col-md col-sm align-items-center">
							<select class="form-control form-control-user @error('transaction_type') is-invalid @enderror" name="transaction_type">
								<option value="Handover">Handover</option>
								<option value="Return">Return</option>
							</select>
						</div>
					</div>
					<div class="row mb-2 justify-content-center">
						<div class="col-md-3 col-sm-2 align-items-center text-right">
							<label for="user">User</label>
						</div>
						<div class="col-md col-sm align-items-center">
							<select class="form-control form-control-user @error('person_id') is-invalid @enderror" name="person_id"> @foreach ($people as $ps) <option value="{{ $ps->id }}">{{ $ps->name .'-'.$ps->office->office_name  }}</option> @endforeach </select>
						</div>
					</div>
					<div class="row mb-2 justify-content-center mca">
						<div class="col-md-3 col-sm-2 align-items-center text-right">
							<label for="transaction">Transaction date</label>
						</div>
						<div class="col-md col-sm align-items-center">
							<input type="date" class="form-control form-control-user @error('transaction_date') is-invalid @enderror" name="transaction_date">
						</div>
					</div>
					<div class="row mb-4 justify-content-center">
						<div class="col-md-3 col-sm-2 align-items-center text-right">
							<label for="comment">Comment</label>
						</div>
						<div class="col-md col-sm align-items-center">
							<input type="text" class="form-control form-control-user @error('comment') is-invalid @enderror" name="comment">
						</div>
					</div>
					<div class="row justify-content-center">
						<div class="col-md-3">
							<button class="btn btn-primary btn-sm w-100">Save</button>
						</div>
						<div class="col-md-3">
							<a class="btn btn-outline-secondary btn-sm w-100" href="{{ route('history') }}">Cancel</a>
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>@endsection