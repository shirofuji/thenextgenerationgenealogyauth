<!DOCTYPE html>
<html>
	<head>
		<title>{{ env('APP_NAME','Auth') }} | password</title>
		<link rel="stylesheet" href="{{ asset('assets/css/auth.css') }}" />
		<link rel="stylesheet" href="{{ asset('../templates/template18/css/templatestyle.css') }}" />
		<meta name="csrf-token" content="{{ csrf_token() }}">
		<meta name="google-signin-client_id" content="{{ env('GOOGLE_OAUTH_CLIENT_ID') }}">
		<script src="//code.jquery.com/jquery-3.3.1.min.js"></script>
		
	</head>
	<body>
		<div class="theader">
			<div id="thomemast" class="mast">
				<h1>Surname Genealogy Pages</h1>
				<span class="tsubtitle">a history of the surname family</span>
			</div>
		</div>
		<div id="container">
		  
		  <!-- Cover Box -->
		  <div id="cover">
		      <!-- Sign Up Section -->
		      <h1 class="sign-up">Password Reset</h1>
		      <p class="sign-up">Please enter your password and we will send a link to your email to continue resetting your password.</p>
		      <!-- Sign In Section -->
		      <h1 class="sign-in">Create a new password</h1>
		      <p class="sign-in">Please create a new secure password for your account.</p>
		      <br>
		  </div>
		  
		  <!-- Login Box -->
		  <div id="login">
		    <h1>Enter your Email</h1>
		    <form>
		      <input type="email" id="email" placeholder="Email" autocomplete="off"><br>
		      <div class="error-message error-message-email"></div>
		      <input class="submit-btn submit-reset" type="submit" value="Reset">
		    </form>
		  </div>
		  
		  <!-- Register Box -->
		  <div id="register">
		    <h1>Enter your new password</h1>
		    <form>
		      @isset($_GET['tk'])
		      <input type="hidden" id="token" value="{{ $_GET['tk'] }}" />
		      @endisset
		      <div class="error-message error-message-token"></div>
		      <input type="password" placeholder="Password" id="password" autocomplete="off" required><br>
		      <div class="error-message error-message-password"></div>
		      <input type="password" placeholder="Verify Password" id="password2" autocomplete="off" required><br>
		      <input class="submit-btn submit-setpass" type="submit" value="Save">
		    </form>
		  </div>
		  
		</div> <!-- END Container -->
		<script>
			jQuery(document).ready(function ($) {
				$.ajaxSetup({
					headers: {
						'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
					}
				});

				$('.submit-reset').on('click', function (e) {
					e.preventDefault()

					$.ajax({
						url: '{{ asset('index.php/submit_reset') }}',
						type:'post',
						data: {
							email: $('#email').val()
						},
						dataType: 'json',
						success: function (resp) {
							if (resp.success) {
								alert('A temporary password was sent to your email address, please check your messages to proceed.')
							} else {
								$.each(resp.errors, function (field, errors) {
									if (errors.length) {
										$('.error-message-' + field).text(errors[0])
									}
								})
							}
						}
					})
				})

				$('.submit-setpass').on('click', function (e) {
					e.preventDefault()

					$.ajax({
						url: '{{ asset('index.php/create_password') }}',
						type:'post',
						data: {
							token: $('#token').val(),
							password: $('#password').val(),
							password_confirmation: $('#password2').val()
						},
						dataType: 'json',
						success: function (resp) {
							console.log(resp)
							if (resp.success) {
								alert('Password updated')
								window.location.href = '{{ asset('/') }}'
							} else {

								$.each(resp.errors, function (field, errors) {
									if (errors.length) {
										$('.error-message-' + field).text(errors[0])
									}
								})
							}
						}
					})
				})
			})

			
		</script>
	</body>
</html>