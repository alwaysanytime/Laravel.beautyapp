@extends('layouts.backend')

@section('title', __('Milestones'))

@section('content')

@php 
$gtext = gtext(); 
$getStripeInfo = getStripeInfo();
@endphp

<!-- main Section -->
<div class="main-body">
	<div class="container-fluid">
		@php $vipc = vipc(); @endphp
		@if($vipc['bkey'] == 0) 
		@include('backend.partials.vipc')
		@else
		<div class="row mb-10">
			<div class="col-lg-12">
				<div id="tw-loader" class="tw-loader">
					<div class="tw-ellipsis">
						<div></div><div></div><div></div><div></div>
					</div>						
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-lg-12">
				<div class="card">
					<div class="card-header">
						<div class="row">
							<div class="col-lg-8">
								<h5 class="mb-0" id="project_name"></h5>
								<small class="text-muted">{{ __('All Payment Milestones') }} </small>
							</div>
							<div class="col-lg-4">
								<div class="float-right">
									<a onClick="onFormPanel()" href="javascript:void(0);" class="btn green-btn btn-form fl-right"><i class="fa fa-plus"></i> {{ __('New Milestone') }}</a>
									<a onClick="onListPanel()" href="javascript:void(0);" class="btn warning-btn btn-list fl-right dnone"><i class="fa fa-plus"></i> {{ __('Back to List') }}</a>
								</div>
							</div>
						</div>
					</div>
					<div class="card-body tabs-area p-0">
						<div class="tabs-body w-100 border-left-none">
							<!--/Data grid-->
							<div id="list-panel">
								<div class="row">
									<div class="col-lg-12">
										<div class="table-responsive">
											<table id="DataTableId" class="table table-striped table-bordered">
												<thead>
												  <tr>
													<th>{{ __('SL') }}</th>
													<th>{{ __('Invoice No') }}</th>
													<th>{{ __('Title') }}</th>
													<th>{{ __('Deadline') }}</th>
													<th>{{ __('Amount') }} ({{ __('Currency') }})</th>
													<th>{{ __('Payment Method') }}</th>
													<th>{{ __('Status') }}</th>
													<th>{{ __('Invoice') }}</th>
													<th>{{ __('Action') }}</th>
												  </tr>
												</thead>
												<tbody></tbody>
											</table>
										</div>
									</div>
								</div>
							</div><!--/Data grid-->
							<!--/Data Entry Form-->
							<div id="form-panel" class="dnone">
								<form novalidate="" data-validate="parsley" id="DataEntry_formId">
									<div class="row">
										<div class="col-md-6">
											<div class="form-group">
												<label for="title"><span class="red">*</span> {{ __('Title') }}</label>
												<input type="text" name="title" id="title" class="form-control parsley-validated" data-required="true">
											</div>
										</div>
										<div class="col-md-6">
											<div class="form-group">
												<label for="amount"><span class="red">*</span> {{ __('Amount') }} ( {{ __('Currency') }} )</label>
												<input type="number" name="amount" id="amount" class="form-control parsley-validated" data-required="true">
											</div>
										</div>
									</div>
									<div class="row">
										<div class="col-md-6">
											<div class="form-group">
												<label for="deadline"><span class="red">*</span> {{ __('Deadline') }}</label>
												<input type="text" name="deadline" id="deadline" class="form-control parsley-validated" data-required="true" placeholder="yyyy-mm-dd">
											</div>
										</div>
										<div class="col-md-6">
											<div class="form-group">
												<label for="payment_status_id"><span class="red">*</span> {{ __('Status') }}</label>
												<select name="payment_status_id" id="payment_status_id" class="chosen-select form-control parsley-validated" data-required="true" tabindex="2">
												</select>
											</div>
										</div>
									</div>
									
									<div class="row">
										<div class="col-md-6">
											<div class="form-group">
												<label for="payment_method_id"><span class="red">*</span> {{ __('Payment Method') }}</label>
												<select name="payment_method_id" id="payment_method_id" class="chosen-select form-control parsley-validated" data-required="true" tabindex="2">
												</select>
											</div>
										</div>
										<div class="col-md-6">
										</div>
									</div>
									
									<div id="cardInformation" class="dnone">
										@if($getStripeInfo['isenable'] == 1)
										<div class="row">
											<div class="col-md-12">
												<h5>{{ __('Enter your card information') }}</h5>
											</div>
										</div>
										<div class="row">
											<div class="col-md-6">
												<div class="form-group p_card">
													<label for="card-element"><span class="red">*</span> {{ __('Card Information') }}</label>
													<div class="form-control" id="card-element"></div>
													<span class="card-errors" id="card-errors"></span>
												</div>
											</div>
											<div class="col-md-6">
												<div class="form-group">
													<label for="name_on_card"><span class="red">*</span> {{ __('Name on Card') }}</label>
													<input type="text" name="name_on_card" id="name_on_card" class="form-control parsley-validated" data-required="true">
												</div>
											</div>
										</div>
										<div class="row">
											<div class="col-md-6">
												<div class="form-group">
													<label for="ClientEmail"><span class="red">*</span> {{ __('Email Address') }}</label>
													<input type="text" name="ClientEmail" id="ClientEmail" class="form-control parsley-validated" data-required="true">
												</div>
											</div>
											<div class="col-md-6">
												<div class="form-group">
													<label for="ClientPhone"><span class="red">*</span> {{ __('Phone') }}</label>
													<input type="text" name="ClientPhone" id="ClientPhone" class="form-control parsley-validated" data-required="true">
												</div>
											</div>
										</div>
										@else
										<div class="row">
											<div class="col-md-12">
												<strong class="red">{{ __('Please provide stripe information') }}</strong> <a href="{{ route('backend.settings') }}">{{ __('Click here') }}</a>
											</div>
										</div>
										@endif
									</div>

									<input type="text" id="RecordId" name="RecordId" class="dnone"/>
									<input type="text" id="project_id" name="project_id" class="dnone" value="{{ $project_id }}"/>

									<div class="row tabs-footer mt-15">
										<div class="col-lg-12">
											<a id="submit-form" href="javascript:void(0);" class="btn green-btn mr-10">{{ __('Save') }}</a>
											<a onClick="onListPanel()" href="javascript:void(0);" class="btn danger-btn">{{ __('Cancel') }}</a>
										</div>
									</div>
								</form>
							</div>
							<!--/Data Entry Form-->
						</div>
					</div>
				</div>
			</div>
		</div>
		@endif
	</div>
</div>
<!-- /main Section -->

<!-- Invoice View Modal -->
<div class="modal fade" id="InvoiceView_Id">
	<div class="modal-dialog">
		<div class="modal-content">
			<input type="text" id="payment_id" class="dnone">
			<!-- Modal Header -->
			<div class="modal-header">
				<ul class="export-icon">
					<li class="print"><a id="printButton" title="print" href="javascript:void(0);"><i class="fa fa-print"></i></a></li>
					<li class="pdf"><a title="pdf" onClick="onInvoicePdf()" href="javascript:void(0);"><i class="fa fa-file-pdf-o"></i></a></li>
				</ul>
				<button type="button" class="close" data-dismiss="modal">&times;</button>
			</div>
			<!-- Modal body -->
			<div id="printElement" class="modal-body pt-0 pr-40 pl-40">
				<div class="invoice-header">
					<div class="company-logo">
						<img src="{{ $gtext['logo'] ? asset('public/media/'.$gtext['logo']) : asset('public/assets/images/logo.png') }}">
					</div>
					<div class="invoice-name">
						{{ __('Invoice') }}
					</div>
				</div>
				<div class="invoice-info pt-40">
					<div class="inv-info-left">
						<h3>{{ __('Bill From') }}:</h3>
						<h4 id="client_inv"></h4>
						<p id="address_inv"></p>
					</div>
					<div class="inv-info-right">
						<p id="payment_method"></p>
						<p id="date_inv"></p>
						<p id="invoice_no_inv"></p>
						<p id="payment_status_inv"></p>
					</div>
				</div>
				<div class="invoice-info pt-60">
					<div class="inv-info-left">
						<h3>{{ __('To') }}:</h3>
						<h4>{{ $gtext['company_name'] }}</h4>
					</div>
				</div>
				<div class="invoice-info pt-50 pb-40">
					<div class="inv-info">
						<p id="project_inv"></p>
					</div>
				</div>
				<div class="inv-col">
					<span class="float-left">{{ __('Milestone') }}</span>
					<span class="float-right">{{ __('Total') }}</span>
				</div>
				<div class="invoice-body">
					<div class="inv-body-left" id="title_inv"></div>
					<div class="inv-body-right" id="amount_inv"></div>
				</div>
				<div class="inv-col mt-60 mb-80">
					<span class="float-right" id="Subtotal_inv"></span>
				</div>
				<div class="invoice-footer pt-10 pb-40">
					<p>{{ __('invoice-footer-1') }}</p>
					<p>{{ __('invoice-footer-2') }}</p>
					<p>{{ $gtext['toMailAddress'] }}</p>
					<p><a href="{{ $gtext['siteurl'] }}">{{ $gtext['siteurl'] }}</a></p>
				</div>
			</div>
		</div>
	</div>
</div>
<!-- /Invoice View Modal -->
@endsection

@push('scripts')
<!-- datatables css/js -->
<link rel="stylesheet" href="{{asset('public/assets/datatables/dataTables.bootstrap4.min.css')}}">
<script src="{{asset('public/assets/datatables/jquery.dataTables.min.js')}}"></script>
<script src="{{asset('public/assets/datatables/dataTables.bootstrap4.min.js')}}"></script>
<script type="text/javascript">
var base_url = "{{ url('/') }}";
var public_path = "{{ asset('public') }}";
var project_id = "{{ $project_id }}";
var user_roleid = "{{ Auth::user()->role_id }}";
var stripe_key = "{{ $getStripeInfo['stripe_key'] }}";
var isenable_stripe = "{{ $getStripeInfo['isenable'] }}";
var TEXT = [];
	TEXT['Edit'] = "{{ __('Edit') }}";
	TEXT['Delete'] = "{{ __('Delete') }}";
	TEXT['View invoice'] = "{{ __('View invoice') }}";
	TEXT['Do you really want to edit this record'] = "{{ __('Do you really want to edit this record') }}";
	TEXT['Do you really want to delete this record'] = "{{ __('Do you really want to delete this record') }}";
	TEXT['Project Name'] = "{{ __('Project Name') }}";
	TEXT['Due Date'] = "{{ __('Due Date') }}";
	TEXT['Invoice No'] = "{{ __('Invoice No') }}";
	TEXT['Status'] = "{{ __('Status') }}";
	TEXT['Currency'] = "{{ __('Currency') }}";
	TEXT['Subtotal'] = "{{ __('Subtotal') }}";
	TEXT['Please type valid card number'] = "{{ __('Please type valid card number') }}";
	TEXT['Payment Method'] = "{{ __('Payment Method') }}";
</script>
<!-- js -->
<script src="{{asset('public/assets/js/print.js')}}"></script>
@if($getStripeInfo['isenable'] == 1)
<script src="https://js.stripe.com/v3/"></script>
<script>
	var style = {
		base: {
			color: '#495057',
			fontSmoothing: 'antialiased',
			'::placeholder': {
				color: '#495057'
			}
		},
		invalid: {
			color: '#fa755a',
			iconColor: '#fa755a'
		}
	};

	const stripe = Stripe(stripe_key, { locale: 'en' }); // Create a Stripe client.
	const elements = stripe.elements(); // Create an instance of Elements.
	const cardElement = elements.create('card', { style: style }); // Create an instance of the card Element.
	cardElement.mount('#card-element'); // Add an instance of the card Element into the `card-element` <div>.

	// Handle real-time validation errors from the card Element.
	cardElement.addEventListener('change', function(event) {
		if(event.complete){
			validCardNumer = 1;
		}else{
			validCardNumer = 0;
		}
		
		var displayError = document.getElementById('card-errors');
		if (event.error) {
			displayError.textContent = event.error.message;
		} else {
			displayError.textContent = '';
		}
	});

	function onConfirmPayment(clientSecret, msg) {
		
		stripe.handleCardPayment(clientSecret, cardElement, {
			payment_method_data: {
				billing_details: {
					name: $("#name_on_card").val(),
					email: $("#ClientEmail").val(),
					phone: $("#ClientPhone").val()
				}
			}
		})
		.then(function(result) {
			if (result.error) {
				// Inform the user if there was an error.
				var errorElement = document.getElementById('card-errors');
				errorElement.textContent = result.error.message;
			} else {
				cardElement.clear();
				// cardElement.destroy();
				onSuccessMsg(msg);
				onListPanel();
			}
		});
	}
</script>
@endif
<script src="{{asset('public/pages/milestones.js')}}"></script>
@endpush