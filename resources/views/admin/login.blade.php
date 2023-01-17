@extends('layouts.app')
@section('content')
<!--begin::Body-->
	<body id="kt_body" class="header-fixed header-mobile-fixed subheader-enabled subheader-fixed aside-enabled aside-fixed aside-minimize-hoverable page-loading">
		<!--begin::Main-->
		<div class="d-flex flex-column flex-root">
			<!--begin::Login-->
			<div class="login login-1 login-signin-on d-flex flex-column flex-lg-row flex-column-fluid bg-white" id="kt_login">
				<!--begin::Aside-->
				<div class="login-aside d-flex flex-row-auto bgi-size-cover bgi-no-repeat p-10 p-lg-10" style="background-image: url(assets/media/bg/bg-9.jpg);">
					<!--begin: Aside Container-->
					<div class="d-flex flex-row-fluid flex-column justify-content-between">
						<!--begin: Aside header-->
						<a href="#" class="flex-column-auto mt-5 pb-lg-0 pb-10">
							<img src="img/logo.svg" class="max-h-70px" alt="" />
						</a>
						<!--end: Aside header-->
						<!--begin: Aside content-->
						<div class="flex-column-fluid d-flex flex-column justify-content-center">
							<h3 class="font-size-h1 mb-5 text-black">Hotdeal Admin</h3>
							<p class="font-weight-lighter text-black opacity-80">Welcome to Hotdeal Admin, a one stop where you can control all stuff Hotdeal related such as manage products, events and more!</p>
						</div>
						<!--end: Aside content-->
						<!--begin: Aside footer for desktop-->
						<div class="d-none flex-column-auto d-lg-flex justify-content-between mt-10">
							<div class="opacity-70 font-weight-bold text-black">© 2022 Hotdeal</div>
						</div>
						<!--end: Aside footer for desktop-->
					</div>
					<!--end: Aside Container-->
				</div>
				<!--begin::Aside-->
				<!--begin::Content-->
				<div class="d-flex flex-column flex-row-fluid position-relative p-7 overflow-hidden">
					<div class="d-flex flex-column-fluid flex-center mt-30 mt-lg-0">
						<div class="login-form login-signin login_admin_with_verification">
							<div class="text-center mb-10 mb-lg-20">
								<h3 class="font-size-h1">Sign In</h3>
								<p class="text-muted font-weight-bold">Enter your username and password</p>
							</div>
							<form class="form" novalidate="novalidate" id="kt_login_signin_form">
                                @csrf
								<div class="form-group">
									<input class="form-control form-control-solid h-auto py-5 px-6" type="text" placeholder="Email" name="email" autocomplete="off" />
								</div>
								<div class="form-group">
									<input class="form-control form-control-solid h-auto py-5 px-6" type="password" placeholder="Password" name="password" autocomplete="off" />
								</div>
								<div class="form-group d-flex flex-wrap justify-content-between align-items-center">
									<!-- <a href="javascript:;" class="text-dark-50 text-hover-primary my-3 mr-2" id="kt_login_forgot">Forgot Password ?</a> -->
									<button type="submit" id="kt_login_signin_submit" class="btn btn-primary font-weight-bold btn-block px-9 py-4 my-3">Sign In</button>
								</div>
							</form>
						</div>
						<div class="login-form two_way_second_verification" style="display: none;">
							<div class="text-center mb-10 mb-lg-20">
								<h3 class="font-size-h1">Enter your OTP</h3>
								<p class="text-muted font-weight-bold">Two Way Verification</p>
							</div>
							<form class="form" novalidate="novalidate">
                                @csrf
								<div class="form-group">
									<input class="form-control form-control-solid h-auto py-5 px-6" id="otp_verification_code" type="text" placeholder="Your OTP..." name="otp" autocomplete="off" />
								</div>
								<div class="form-group d-flex flex-wrap justify-content-between align-items-center">
									<button type="submit" id="kt_login_otp_submit" class="btn btn-primary font-weight-bold btn-block px-9 py-4 my-3">Submit</button>
								</div>
							</form>
						</div>
						<!--end::Signin-->
						<!--begin::Signup-->
						<div class="login-form login-signup">
							<div class="text-center mb-10 mb-lg-20">
								<h3 class="font-size-h1">Sign Up</h3>
								<p class="text-muted font-weight-bold">Enter your details to create your account</p>
							</div>
							<!--begin::Form-->
							<form class="form" novalidate="novalidate" id="kt_login_signup_form">
								<div class="form-group">
									<input class="form-control form-control-solid h-auto py-5 px-6" type="text" placeholder="Fullname" name="fullname" autocomplete="off" />
								</div>
								<div class="form-group">
									<input class="form-control form-control-solid h-auto py-5 px-6" type="email" placeholder="Email" name="email" autocomplete="off" />
								</div>
								<div class="form-group">
									<input class="form-control form-control-solid h-auto py-5 px-6" type="password" placeholder="Password" name="password" autocomplete="off" />
								</div>
								<div class="form-group">
									<input class="form-control form-control-solid h-auto py-5 px-6" type="password" placeholder="Confirm password" name="cpassword" autocomplete="off" />
								</div>
								<div class="form-group">
									<label class="checkbox mb-0">
									<input type="checkbox" name="agree" />
									<span></span>I Agree the
									<a href="#">terms and conditions</a></label>
								</div>
								<div class="form-group d-flex flex-wrap flex-center">
									<button type="button" id="kt_login_signup_submit" class="btn btn-primary font-weight-bold px-9 py-4 my-3 mx-4">Submit</button>
									<button type="button" id="kt_login_signup_cancel" class="btn btn-light-primary font-weight-bold px-9 py-4 my-3 mx-4">Cancel</button>
								</div>
							</form>
							<!--end::Form-->
						</div>
						<!--end::Signup-->
						<!--begin::Forgot-->
						<div class="login-form login-forgot">
							<div class="text-center mb-10 mb-lg-20">
								<h3 class="font-size-h1">Forgotten Password ?</h3>
								<p class="text-muted font-weight-bold">Enter your email to reset your password</p>
							</div>
							<!--begin::Form-->
							<form class="form" novalidate="novalidate" id="kt_login_forgot_form">
								<div class="form-group">
									<input class="form-control form-control-solid h-auto py-5 px-6" type="email" placeholder="Email" name="email" autocomplete="off" />
								</div>
								<div class="form-group d-flex flex-wrap flex-center">
									<button type="button" id="kt_login_forgot_submit" class="btn btn-primary font-weight-bold px-9 py-4 my-3 mx-4">Submit</button>
									<button type="button" id="kt_login_forgot_cancel" class="btn btn-light-primary font-weight-bold px-9 py-4 my-3 mx-4">Cancel</button>
								</div>
							</form>
							<!--end::Form-->
						</div>
						<!--end::Forgot-->
					</div>
					<!--end::Content body-->
					<!--begin::Content footer for mobile-->
					<div class="d-flex d-lg-none flex-column-auto flex-column flex-sm-row justify-content-between align-items-center mt-5 p-5">
						<div class="text-dark-50 font-weight-bold order-2 order-sm-1 my-2">© 2021 Metronic</div>
						<div class="d-flex order-1 order-sm-2 my-2">
							<a href="#" class="text-dark-75 text-hover-primary">Privacy</a>
							<a href="#" class="text-dark-75 text-hover-primary ml-4">Legal</a>
							<a href="#" class="text-dark-75 text-hover-primary ml-4">Contact</a>
						</div>
					</div>
					<!--end::Content footer for mobile-->
				</div>
				<!--end::Content-->
			</div>
			<!--end::Login-->
        
@endsection
