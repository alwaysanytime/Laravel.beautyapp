@extends('layouts.backend')

@section('title', __('Chat'))
@push('style')
<style type="text/css">
.msg_count {display: none;}
</style>
@endpush
@section('content')

<!-- main Section -->
<div class="main-body">
	<div class="container-fluid">
		@php $vipc = vipc(); @endphp
		@if($vipc['bkey'] == 0) 
		@include('backend.partials.vipc')
		@else
		<div class="row">
			<div class="col-lg-12">
				<div class="card">
					<div class="card-body chatbot-area p-0">
						<div class="chatbot-sidebar">
							<div class="chat-me">
								<a class="btnShowLeft userSidebarCollapse" href="javascript:void(0);"><i class="fa fa-exchange"></i></a>
								<div class="chat-me-avatar">
									<img src="{{ Auth::user()->photo ? asset('public/media/'.Auth::user()->photo) : asset('public/assets/images/default.png') }}" alt="" />
									<div class="online-status online"><i class="fa fa-circle"></i></div>
								</div>
								<div class="me-name">{{ Auth::user()->name }}</div>
							</div>
							<div class="chat-user-search">
								<input id="chatUserSearch" name="chatUserSearch" class="user-search" type="text" placeholder="Search.." />
							</div>
							<div id="user_loader" class="tw-loader">
								<div class="tw-ellipsis">
									<div></div><div></div><div></div><div></div>
								</div>						
							</div>
							<div class="sidebar-user-list">
								<ul class="chatbot-list" id="chat-users"></ul>
							</div>
						</div>
						<div class="chatbot-body" id="welcome_id">
							<a class="btn-show-hide wl-Collapse userSidebarCollapse" href="javascript:void(0);"><i class="fa fa-exchange"></i></a>
							<div class="chatbot-welcome">
								<h3>{{ __('Welcome') }}, {{ Auth::user()->name }}</h3>
								<div class="wl-avatar">
									<img src="{{ Auth::user()->photo ? asset('public/media/'.Auth::user()->photo) : asset('public/assets/images/default.png') }}" alt="" />
									<div class="online-status online"><i class="fa fa-circle"></i></div>
								</div>
							</div>
						</div>
						<div class="chatbot-body dnone" id="message_chatbot">
							<div class="chatbot-header">
								<a class="btn-show-hide userSidebarCollapse" href="javascript:void(0);"><i class="fa fa-exchange"></i></a>
								<div id="connect_user"></div>
								<ul class="pik_icon">
									<li><a id="msgSearchCollapse" href="javascript:void(0);"><i class="fa fa-search"></i></a></li>
									<li><a id="msgRefresh" href="javascript:void(0);"><i class="fa fa-refresh"></i></a></li>
								</ul>
								<div id="msgSearchBox_collapse" class="msgSearchBox">
									<input class="msgSearch" id="msgSearch" name="msgSearch" type="text" placeholder="Search.." />
								</div>
								<a href="javascript:void(0);" id="older_msg" class="older_msg dnone">{{ __('Older Messages') }}</a>
							</div>

							<ul class="chatbot-content" id="message_list"></ul>
							
							<div class="chatbot-footer">
								<div class="relative">
									<a href="javascript:void(0);" id="newer_msg" class="newer_msg dnone">{{ __('Newer Messages') }}</a>
								</div>
								<div id="file_loader" class="tw-loader dnone">
									<div class="tw-ellipsis">
										<div></div><div></div><div></div><div></div>
									</div>						
								</div>
								<div class="chat-box">
									<form method="POST" id="chat_formid">
										<input type="text" class="type_message" id="type_message" name="type_message" placeholder="Type a message" />
										<input type="text" class="dnone" id="message_id" name="message_id" />
										<button type="submit" class="chat-submit" id="chat_submit"><i class="fa fa-paper-plane-o"></i></button>
									</form>
									<input type="text" class="dnone" id="user_id" name="user_id" />
								</div>
								<div class="chat-files">
									<input type="file" name="chat_mes_file[]" id="chat_mes_file" class="dnone" multiple="multiple">
									<label for="chat_mes_file" class="chat-file" title="{{ __('Attach files') }}"><i class="fa fa-paperclip"></i></label>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		@endif
	</div>
</div>
<!-- /main Section -->
@endsection

@push('scripts')
<script type="text/javascript">
var base_url = "{{ url('/') }}";
var public_path = "{{ asset('public') }}";
var me_id = "{{ Auth::user()->id }}";
var TEXT = [];
	TEXT['Delete'] = "{{ __('Delete') }}";
	TEXT['Edit'] = "{{ __('Edit') }}";
	TEXT['Download'] = "{{ __('Download') }}";
	TEXT['New'] = "{{ __('New') }}";
</script>

<link rel="stylesheet" href="{{asset('public/assets/css/lity.css')}}" />
<script src="{{asset('public/assets/js/lity.min.js')}}"></script>

<script src="{{asset('public/pages/chat.js')}}"></script>
@endpush