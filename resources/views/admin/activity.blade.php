@extends('layouts.app') @section('content') 
	<div class="container-fluid">
	<div class="card mx-auto shadow h-100">
		<div class="card-header d-flex py-2 justify-content-between align-items-center">
			<div>
				<i class="fas fa-running"></i>&nbsp;&nbsp;Activities
			</div>
			<div class="d-flex">
				<form action="{{ route('activity.read') }}" method="post">
					@csrf
					<button class="btn btn-outline-info btn-small mr-2"><i class="fas fa-check"></i>&nbsp;&nbsp;Read all</button>
				</form>
				<form action="{{ route('user.truncateActivity') }}" method="post"> @csrf @method('DELETE') <button class="btn btn-outline-secondary btn-small mr-2" title="remove all activities">
						<i class="fas fa-trash"></i>&nbsp;&nbsp;Delete All
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
								<th>Actor</th>
								<th>Token</th>
								<th>Message</th>
								<th>Actions</th>
							</tr>
						</thead>
						<tbody> @foreach ($data as $act) <tr class="@if ($act->is_read == false) bg-gray-400 text-white @endif">
								<td class="align-middle">{{ $act->actor }}</td>
								<td class="align-middle">{{ $act->token }}</td>
								<td class="align-middle">{{ $act->message }}</td>
								<td class="align-middle text-center">
									<a href="{{ route('activity.detail',$act->act_id) }}" class="btn btn-outline-secondary btn-sm">
										<i class="fas fa-search"></i></a>
								</td>
							</tr> @endforeach </tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
	</div> @endsection