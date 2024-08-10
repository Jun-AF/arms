@extends('layouts.app') @section('content') 
	<div class="container-fluid">
		<div class="card w-75 mx-auto shadow h-100">
			<div class="card-header d-flex py-2 justify-content-between align-items-center">
				<div>  
					<i class="fas fa-paperclip"></i>&nbsp;&nbsp;Activity Details
				</div>
			</div>
				<div class="card-body">
					<div class="container-fluid">
						<div class="row mb-2 justify-content-center">
							<div class="col-md-2 col-sm-2 align-items-center">
								<label for="actor name">Actor name</label>
							</div>
							<div class="col-sm-1">:</div>
							<div class="col-md col-sm align-items-center border-bottom">
								<label>{{ $data[0]->actor }}</label>
							</div>
						</div>
						<div class="row mb-2 justify-content-center">
							<div class="col-md-2 col-sm-2 align-items-center">
								<label for="token">Token</label>
							</div>
							<div class="col-sm-1">:</div>
							<div class="col-md col-sm align-items-center border-bottom">
								<label>{{ $data[0]->token }}</label>
							</div>
						</div>
						<div class="row mb-2 justify-content-center">
							<div class="col-md-2 col-sm-2 align-items-center">
								<label for="message">Message</label>
							</div>
							<div class="col-sm-1">:</div>
							<div class="col-md col-sm align-items-center border-bottom">
								<label>{{ $data[0]->message }}</label>
							</div>
						</div>
						<div class="row mb-2 justify-content-center">
							<div class="col-md-2 col-sm-2 align-items-center">
								<label for="message">Type</label>
							</div>
							<div class="col-sm-1">:</div>
							<div class="col-md col-sm align-items-center border-bottom">
								<label>{{ $data[0]->type }}</label>
							</div>
						</div>
						<div class="row mb-4 justify-content-center">
								<div class="col-md-2 col-sm-2 align-items-center">
								<label for="created date">Done at</label>
							</div>
							<div class="col-sm-1">:</div>
							<div class="col-md col-sm align-items-center border-bottom">
								<label>{{ $data[0]->created_at }}</label>
							</div>
						</div>
						<div class="row justify-content-center">
							<div class="col-md-3">
								<a class="btn btn-outline-secondary btn-sm w-100" href="{{ route('activity') }}">Back</a>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div> @endsection