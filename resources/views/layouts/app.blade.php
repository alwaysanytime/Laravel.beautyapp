<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
	@php $gtext = gtext(); @endphp
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title') | {{ $gtext['company_title'] }}</title>
	<meta name="description" content="">
	<link rel="shortcut icon" href="{{ $gtext['favicon'] ? asset('public/media/'.$gtext['favicon']) : asset('public/assets/images/favicon.ico') }}" type="image/x-icon">
	<link rel="icon" href="{{ $gtext['favicon'] ? asset('public/media/'.$gtext['favicon']) : asset('public/assets/images/favicon.ico') }}" type="image/x-icon">	
	<!-- General CSS -->
	<link rel="stylesheet" href="{{asset('public/assets/css/bootstrap.min.css')}}">
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
	<link rel="stylesheet" href="{{asset('public/assets/css/chosen/bootstrap-chosen.min.css')}}">
	<link rel="stylesheet" href="{{asset('public/assets/css/global.css')}}">
	<link rel="stylesheet" href="{{asset('public/assets/css/style.css')}}">
	<!-- Theme color changes in Global Setting -->
	<style>
		::-moz-selection{ background: <?php echo $gtext['theme_color']; ?>; color: #ffffff;}
		::selection{background: <?php echo $gtext['theme_color']; ?>; color: #ffffff;}
		.loader, .btn.white-btn:hover, .btn.green-btn, .login .login-btn, .be-header .search ul.search-result li:hover a, ul.top-navbar li ul.sub-navbar li:hover a, .btn-primary:not(:disabled):not(.disabled).active, .btn-primary:not(:disabled):not(.disabled):active,  .show > .btn-primary.dropdown-toggle, .tw_checkbox input:checked ~ span, .tw_radio .checkround:after, .tw_box .tw_control, .tasks-board .owl-nav div, .tasks-board .owl-dot.active, .task-group .task-footer:hover a, .inner-search-box button.search-btn, .page-item.active .page-link, ul.controlBox li:hover a, .tw-ellipsis div, a.editIconBtn, .chosen-container .chosen-results li.highlighted, a.start-request {background: <?php echo $gtext['theme_color']; ?>; color: #ffffff;}
		.left-sidebar ul.left-main-menu > li::before, .progress-bar-bg {background: <?php echo $gtext['theme_color']; ?>;}
		[data-themecolor]::before {background: <?php echo $gtext['theme_color']; ?>;}
		a, button, .btn.green-btn:hover, a:focus, a:hover, .main-body .project .project-title a:hover, .left-sidebar ul.left-main-menu > li.active::before, .left-sidebar ul.left-main-menu > li:hover::before, .left-sidebar ul.left-main-menu > li:hover a, .left-sidebar ul.left-main-menu > li.active a, ul.profile_info li i.fa, .task-body ul.task-list li ul.task-action li.task-edit a, .task-group .task-header ul.head-action li.head-edit a, .task-body ul.task-list li p a:hover, ul.activity-list li.media ul.comments-control li.com-edit a, ul.attachment-list li ul.attach-control li.att-edit a, .form-group label.attachment a:hover, ul.activity-list li.media ul.attachment-list li .attach-info a:hover, ul.tabs-nav li a:hover, ul.tabs-nav li a.active, .inner-search-box ul.search-result li a:hover, .page-link, .page-link:hover, ul.top-navbar li ul.search-results li a:hover, .btn.green-btn.active {color: <?php echo $gtext['theme_color']; ?>;}
		.btn.green-btn:hover, input.form-control:focus, .form-group input:focus, .form-group textarea:focus, .card .card-body input:focus, .card .card-body textarea:focus, .btn-primary:not(:disabled):not(.disabled).active, .btn-primary:not(:disabled):not(.disabled):active, .show > .btn-primary.dropdown-toggle, .tw_checkbox input:checked ~ span, .checkround, .tw_box .tw_img_circle, .profile_head .profile_image, ul.activity-list li img, .page-item.active .page-link, .invoice-header, .invoice-body, .invoice-footer, .btn.green-btn.active {border-color: <?php echo $gtext['theme_color']; ?>;}
	</style>
	@stack('style')
</head>
<body>

    @yield('content')

	<!-- General JS Scripts -->
	<script src="{{asset('public/assets/js/jquery-3.5.1.min.js')}}"></script>
	<script src="{{asset('public/assets/js/popper.min.js')}}"></script>
	<script src="{{asset('public/assets/js/bootstrap.min.js')}}"></script>
	<script src="{{asset('public/assets/js/parsley.min.js')}}"></script>
	<script src="{{asset('public/assets/js/chosen.jquery.min.js')}}"></script>
	@stack('scripts')
</body>
</html>
