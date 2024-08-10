@extends('layouts.app') @section('content')
<div class="container-fluid">
	<div class="card w-75 mx-auto shadow h-100">
		<div class="card-header d-flex py-2 justify-content-between align-items-center">
			<div><i class="fas fa-list"></i>&nbsp;&nbsp;Edit transaction</div>
		</div>
		<div class="card-body">
			<div class="container-fluid">
				<form action="{{ route('history.detail.update',$uniqueid) }}" method="POST"> 
					@csrf 
					@method('PATCH')
					<input type="hidden" name="id" value="{{ $history->id }}">
					<div class="row mb-2 justify-content-center">
						<div class="col-md-3 col-sm-2 align-items-center text-right">
							<label for="type">Transaction type</label>
						</div>
						<div class="col-md col-sm align-items-center">
							<select class="form-control form-control-user @error('transaction_type') is-invalid @enderror" name="transaction_type">
								<option value="handover" {{ ($history->transaction_type == 'handover') ? 'selected':'' }}>Handover</option>
								<option value="return" {{ ($history->transaction_type == 'return') ? 'selected':'' }}>Return</option>
							</select>
						</div>
					</div>
					<div class="row mb-2 justify-content-center">
						<div class="col-md-3 col-sm-2 align-items-center text-right">
							<label for="user">User</label>
						</div>
						<div class="col-md col-sm align-items-center">
							<select class="form-control form-control-user @error('person_id') is-invalid @enderror" name="person_id"> @foreach ($people as $ps) <option value="{{ $ps->id }}" {{ ($history->person_id == $ps->id) ? 'selected':'' }}>{{ $ps->name .'-'.$ps->office->office_name }}</option> @endforeach </select>
						</div>
					</div>
					<div class="row mb-2 justify-content-center mca">
						<div class="col-md-3 col-sm-2 align-items-center text-right">
							<label for="transaction">Transaction date</label>
						</div>
						<div class="col-md col-sm align-items-center">
							<input type="date" class="form-control form-control-user @error('transaction_date') is-invalid @enderror" name="transaction_date" value="{{ $history->transaction_date }}">
						</div>
					</div>
					<div class="row mb-4 justify-content-center">
						<div class="col-md-3 col-sm-2 align-items-center text-right">
							<label for="comment">Comment</label>
						</div>
						<div class="col-md col-sm align-items-center">
							<input type="text" class="form-control form-control-user @error('comment') is-invalid @enderror" name="comment" value="{{ $history->comment }}">
						</div>
					</div>
					<div class="row justify-content-center">
						<div class="col-md-3">
							<button class="btn btn-primary btn-sm w-100">Save</button>
						</div>
						<div class="col-md-3">
							<a class="btn btn-outline-secondary btn-sm w-100" href="{{ url('transaction/detail/'.$unique) }}">Cancel</a>
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>@endsection