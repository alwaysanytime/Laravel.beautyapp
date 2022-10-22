@extends('layouts.backend')

@section('title', __('Dashboard'))

@section('content')
<!-- main Section -->
<div class="main-body">
	<div class="container-fluid">
		<div class="row">
			<div class="col">
				<div class="dash-heading mb-10">
					<div class="row">
						<div class="col-md-12">
							<h2 style="padding-left: 15px">{{ __('Dashboard') }}</h2>
						</div>
					</div>
				</div>
				<div id="tw-loader" class="tw-loader">
					<div class="tw-ellipsis">
						<div></div><div></div><div></div><div></div>
					</div>						
				</div>
			</div>
		</div>
		
	</div>
</div>
<!-- /main Section -->
@endsection
@push('scripts')
<!-- Chart js -->
<script src="{{asset('public/assets/js/Chart.min.js')}}"></script>
<!-- datatables css/js -->
<link rel="stylesheet" href="{{asset('public/assets/datatables/dataTables.bootstrap4.min.css')}}">
<script src="{{asset('public/assets/datatables/jquery.dataTables.min.js')}}"></script>
<script src="{{asset('public/assets/datatables/dataTables.bootstrap4.min.js')}}"></script>
<script type="text/javascript">
var base_url = "{{ url('/') }}";
var public_path = "{{ asset('public') }}";
var userid = "{{ Auth::user()->id }}";
var roleid = "{{ Auth::user()->role }}";
var TEXT = [];
	TEXT['View'] = "{{ __('View') }}";
	TEXT['Milestones View'] = "{{ __('Milestones View') }}";
</script>
<script src="{{asset('public/pages/client.js')}}"></script>
@endpush