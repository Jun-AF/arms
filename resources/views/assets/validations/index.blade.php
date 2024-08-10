@extends('layouts.app') @section('content')
<div class="container-fluid mt-5">
	<div class="card w-50 mx-auto shadow h-100">
		<div class="card-header d-flex py-2 justify-content-center align-items-center">
			<h5>Asset Validation</h5>
		</div>
		<div class="card-body">
			<div class="row mt-3 mb-5">
				<div class="col-xl col-sm-12">
					<select class="form-control text-center" name="office" id="office"> @foreach ($offices as $ofc) <option value="{{ $ofc->office_name }}">{{ $ofc->office_name }}</option> @endforeach </select>
				</div>
			</div>
			<div class="w-50 mx-auto">
				<button class="btn btn-primary w-100" onclick="setParam()">Select</button>
			</div>
			<script>
				function setParam() {
					location.replace('validation/' + document.getElementById('office').value + '/list');
				}
			</script>
		</div>
	</div>
</div> @endsection