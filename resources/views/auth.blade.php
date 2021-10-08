<!DOCTYPE html>
<html>
	<head>
		<title>{{ env('APP_NAME','Auth') }} | authentication</title>
		<link rel="stylesheet" href="{{ asset('assets/css/auth.css') }}" />
		<link rel="stylesheet" href="{{ asset('../templates/template18/css/templatestyle.css') }}" />
		<meta name="csrf-token" content="{{ csrf_token() }}">
		<meta name="google-signin-client_id" content="{{ env('GOOGLE_OAUTH_CLIENT_ID') }}">
		<script src="//code.jquery.com/jquery-3.3.1.min.js"></script>
		<script src="https://apis.google.com/js/platform.js" async defer></script>
		<script src="https://apis.google.com/js/api.js"></script>
		<script>
		  window.fbAsyncInit = function() {
		    FB.init({
		      appId      : '{{ env('FB_APP_ID') }}',
		      cookie     : true,
		      xfbml      : true,
		      version    : 'v12.0'
		    });
		      
		    FB.AppEvents.logPageView();   
		      
		  };

		  (function(d, s, id){
		     var js, fjs = d.getElementsByTagName(s)[0];
		     if (d.getElementById(id)) {return;}
		     js = d.createElement(s); js.id = id;
		     js.src = "https://connect.facebook.net/en_US/sdk.js";
		     fjs.parentNode.insertBefore(js, fjs);
		   }(document, 'script', 'facebook-jssdk'));
		</script>
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
		      <h1 class="sign-up">Hello, Friend!</h1>
		      <p class="sign-up">Enter your personal details<br> and start a journey with us</p>
		      <a class="button sign-up" href="#cover">Sign Up</a>
		    
		      <!-- Sign In Section -->
		      <h1 class="sign-in">Welcome Back!</h1>
		      <p class="sign-in">To keep connected with us please<br> login with your personal info</p>
		      <br>
		      <a class="button sub sign-in" href="#">Sign In</a>
		  </div>
		  
		  <!-- Login Box -->
		  <div id="login">
		    <h1>Sign In</h1>
		    @isset($_GET['msg'])
		    <div class="success-message">
		    	{{ $_GET['msg'] }}
		    </div>
		    @endisset
		    <a href="#"><img class="social-login auth-fb" src="https://image.flaticon.com/icons/png/128/59/59439.png"></a>
		    <a href="#"><img class="social-login auth-google" src="https://image.flaticon.com/icons/png/128/49/49026.png"></a>
		    <div class="error-message error-message-social-login">
		    	@isset($_GET['error_description'])
		    	{{ $_GET['error_description'] }}
		    	@endisset
		    </div>
		    <p>or use your email account:</p>
		    <form>
		      <input type="email" id="login-email" placeholder="Email or Username" autocomplete="off"><br>
		      <div class="error-message error-message-login-email"></div>
		      <input type="password" id="login-password" placeholder="Password" autocomplete="off"><br>
		      <div class="error-message error-message-login-password"></div>
		      <a id="forgot-pass" href="{{ asset('index.php/password') }}">Forgot your password?</a><br>
		      <input class="submit-btn submit-login" type="submit" value="Sign In">
		    </form>
		  </div>
		  
		  <!-- Register Box -->
		  <div id="register">
		    <h1>Create Account</h1>
		    <a href="#"><img class="social-login auth-fb" src="https://image.flaticon.com/icons/png/128/59/59439.png"></a>
		    <a href="#"><img class="social-login auth-google" src="https://image.flaticon.com/icons/png/128/49/49026.png"></a>
		    <p>or use your email for registration:</p>
		    <form>
		      <input type="text" placeholder="Username" id="register-username" autocomplete="off" required><br>
		      <div class="error-message error-message-username"></div>
		      <input type="text" placeholder="Real Name" id="register-realname" autocomplete="off" required><br>
		      <div class="error-message error-message-realname"></div>
		      <input type="email" placeholder="Email" id="register-email" autocomplete="off" required><br>
		      <div class="error-message error-message-email"></div>
		      <input type="password" placeholder="Password" id="register-password" autocomplete="off" required><br>
		      <div class="error-message error-message-password"></div>
		      <input type="password" placeholder="Verify Password" id="register-password2" autocomplete="off" required><br>
		      <input class="submit-btn submit-register" type="submit" value="Sign Up">
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

				$('.submit-register').on('click', function (e) {
					e.preventDefault()

					var data = {
						'username':$('#register-username').val(),
						'realname':$('#register-realname').val(),
						'email': $('#register-email').val(),
						'password': $('#register-password').val(),
						'password_confirmation': $('#register-password2').val()
					}

					$.ajax({
						url: '{{ asset('index.php/signup') }}',
						type: 'post',
						data: data,
						dataType: 'json',
						beforeSend: function () {
							$('.error-message').text('')
						},
						success: function (r) {
							if (r.success) {
								alert('Please check your email to continue with your registration.')
								window.location.hash = '';
								return
							}

							$.each(r.errors, function (field, errors) {
								if (errors.length > 0) {
									$('.error-message-' + field).text(errors[0])
								}
							})
						}
					})
				})

				$('.submit-login').on('click', function (e) {
					e.preventDefault()
					xhr = new XMLHttpRequest();
					xhr.onreadystatechange = function () {
						if (xhr.readyState == XMLHttpRequest.DONE) {
							var redirect = xhr.responseURL;
							var redirect_data = redirect.split('?')
							var errors = false

							if (redirect_data.length > 1) {
								errors = redirect_data[1]
							}

							console.log(errors)
							if (redirect.indexOf('admin_login') != -1) {
								error_message = 'Invalid username or password.'
								if (errors.indexOf('logininactive') != -1) {
									error_message = 'Account has not yet been activated, please check your email to activate your account.'
								} else if (errors.indexOf('norights') != -1) {
									error_message = 'Permission Denied.'
								}
								$('.error-message-login-password').text(error_message);
								return
							}

							window.location.href = '//maram.santhiran.com/'
						}
					}

					$.ajax({
						url: '{{ asset('../processlogin.php') }}',
						type: 'post',
						data: {
							'tngusername': $('#login-email').val(),
							'tngpassword': $('#login-password').val(),
							'admin_login': 1,
							'continue':''
						},
						xhr: function () {
							return xhr;
						},
						dataType: 'json',
						beforeSend: function () {
							$('.error-message').text('')
						}
					})
				})
				
				$('.auth-fb').on('click', function () {
					FB.getLoginStatus(function(response) {
						console.log(response)
						if (!response.authResponse) {
							FB.login(function (loginResponse) {
								if (loginResponse.authResponse) {
									access_token = loginResponse.authResponse.accessToken;
									user_id = loginResponse.authResponse.userID
									FB.api('/me?fields=id,email,name', function (meResponse) {
										if (meResponse.id) {
											$.ajax({
							            		url: '{{ asset('index.php/authfb') }}',
							            		type: 'post',
							            		data: {
							            			username: user_id,
							            			token: access_token,
							            			email: meResponse.email,
							            			realname: meResponse.name
							            		},
							            		dataType: 'json',
							            		beforeSend: function () {
							            			$('.error-message-social-login').html('')
							            		},
							            		success: function (response) {
							            			console.log('Sending username and token');
							            			if (response.success) {
							            				xhr = new XMLHttpRequest();
														xhr.onreadystatechange = function () {
															if (xhr.readyState == XMLHttpRequest.DONE) {
																var redirect = xhr.responseURL;

																if (redirect.indexOf('admin_login') != -1) {
																	$('.error-message-social-login').text('Login failed!');
																	return
																}

																window.location.href = '//maram.santhiran.com/'
															}
														}

														$.ajax({
															url: '{{ asset('../processlogin.php') }}',
															type: 'post',
															data: {
																'tngusername': user_id,
																'tngpassword': access_token,
																'admin_login': 1,
																'continue':''
															},
															xhr: function () {
																return xhr;
															},
															dataType: 'json',
															beforeSend: function () {
																$('.error-message').text('')
															}
														})
							            			} else {
							            				var errors = 'Authentication failed.'

							            				$('.error-message-social-login').html(errors)
							            			}
							            		}
							            	})
										} else if (meResponse.error) {
											$('.error-message-social-login').html(meResponse.error.message)
										}
									})									
								}
							}, {scope:'public_profile,email'})
						} else {
							access_token = response.authResponse.accessToken;
							user_id = response.authResponse.userID
							FB.api('/me?fields=id,email,name', function (meResponse) {
								if (meResponse.id) {
									$.ajax({
					            		url: '{{ asset('index.php/authfb') }}',
					            		type: 'post',
					            		data: {
					            			username: user_id,
					            			token: access_token,
					            			email: meResponse.email,
					            			relanem: meResponse.name
					            		},
					            		dataType: 'json',
					            		beforeSend: function () {
					            			$('.error-message-social-login').html('')
					            		},
					            		success: function (response) {
					            			if (response.success) {
					            				xhr = new XMLHttpRequest();
												xhr.onreadystatechange = function () {
													if (xhr.readyState == XMLHttpRequest.DONE) {
														var redirect = xhr.responseURL;

														if (redirect.indexOf('admin_login') != -1) {
															$('.error-message-social-login').text('Login failed!');
															return
														}

														window.location.href = '//maram.santhiran.com/'
													}
												}

												$.ajax({
													url: '{{ asset('../processlogin.php') }}',
													type: 'post',
													data: {
														'tngusername': user_id,
														'tngpassword': access_token,
														'admin_login': 1,
														'continue':''
													},
													xhr: function () {
														return xhr;
													},
													dataType: 'json',
													beforeSend: function () {
														$('.error-message').text('')
													}
												})
					            			} else {
					            				var errors = 'Authentication failed.'

					            				$('.error-message-social-login').html(errors)
					            			}
					            		}
					            	})
								} else if (meResponse.error) {
									$('.error-message-social-login').html(meResponse.error.message)
								}
							})
						}
					});
				})
			});


			function googleOnSignIn(signedin) {
	            console.log( "signedin");
	            if (signedin) {
	            	var googleUser = gapi.auth2.getAuthInstance().currentUser.get(),
	            		profile = googleUser.getBasicProfile(),
	            		token = googleUser.getAuthResponse().id_token

	            	console.log(profile.getEmail())

	            	$.ajax({
	            		url: '{{ asset('index.php/authgoogle') }}',
	            		type: 'post',
	            		data: {
	            			username: googleUser.getId(),
	            			token: token,
	            			email: profile.getEmail(),
	            			realname: profile.getName()
	            		},
	            		dataType: 'json',
	            		beforeSend: function () {
	            			$('.error-message-social-login').html('')
	            		},
	            		success: function (response) {
	            			if (response.success) {
	            				xhr = new XMLHttpRequest();
								xhr.onreadystatechange = function () {
									if (xhr.readyState == XMLHttpRequest.DONE) {
										var redirect = xhr.responseURL;

										if (redirect.indexOf('admin_login') != -1) {
											$('.error-message-social-login').text('Login failed!');
											return
										}

										gapi.auth2.getAuthInstance().disconnect()
										window.location.href = '//maram.santhiran.com/'
									}
								}

								$.ajax({
									url: '{{ asset('../processlogin.php') }}',
									type: 'post',
									data: {
										'tngusername': googleUser.getId(),
										'tngpassword': token,
										'admin_login': 1,
										'continue':''
									},
									xhr: function () {
										return xhr;
									},
									dataType: 'json',
									beforeSend: function () {
										$('.error-message').text('')
									}
								})
	            			} else {
	            				var errors = ''
	            				gapi.auth2.getAuthInstance().disconnect()
	            				$.each(response.errors, function (field, error) {
	            					errors += (field + ' : ' + error + '<br/>')
	            				})

	            				$('.error-message-social-login').html(errors)
	            			}
	            		}
	            	})
	            }
	        };

	        gapi.load('auth2', function() {
	            gapi.auth2.init({
	                client_id: "{{ env('GOOGLE_OAUTH_CLIENT_ID') }}",
	                cookiepolicy: 'single_host_origin'
	            }).then(function(auth2) {
	                console.log( "signed in: " + auth2.isSignedIn.get() );  
	                if (auth2.isSignedIn.get()) {
	                	// window.location.href = '//www.maram.santhiran.com/'
	                	var googleUser = gapi.auth2.getAuthInstance().currentUser.get(),
	                		profile = googleUser.getBasicProfile(),
	                		token = googleUser.getAuthResponse().id_token

	                	if (token) {
	                		$.ajax({
		            		url: '{{ asset('index.php/authgoogle') }}',
		            		type: 'post',
		            		data: {
		            			username: googleUser.getId(),
		            			token: token,
		            			email: profile.getEmail(),
		            			realname: profile.getName()
		            		},
		            		dataType: 'json',
		            		beforeSend: function () {
		            			$('.error-message-social-login').html('')
		            		},
		            		success: function (response) {
		            			if (response.success) {
		            				xhr = new XMLHttpRequest();
									xhr.onreadystatechange = function () {
										if (xhr.readyState == XMLHttpRequest.DONE) {
											var redirect = xhr.responseURL;

											if (redirect.indexOf('admin_login') != -1) {
												$('.error-message-social-login').text('Login failed!');
												return
											}

											gapi.auth2.getAuthInstance().disconnect()
											window.location.href = '//maram.santhiran.com/'
										}
									}

									$.ajax({
										url: '{{ asset('../processlogin.php') }}',
										type: 'post',
										data: {
											'tngusername': googleUser.getId(),
											'tngpassword': token,
											'admin_login': 1,
											'continue':''
										},
										xhr: function () {
											return xhr;
										},
										dataType: 'json',
										beforeSend: function () {
											$('.error-message').text('')
										}
									})
		            			} else {
		            				var errors = ''
		            				gapi.auth2.getAuthInstance().disconnect()
		            				$.each(response.errors, function (field, error) {
		            					errors += (field + ' : ' + error + '<br/>')
		            				})

		            				$('.error-message-social-login').html(errors)
		            			}
		            		}
		            	})
	                	}
	                }
	                auth2.isSignedIn.listen(googleOnSignIn);
	                var buttons = document.querySelectorAll('.auth-google');
	                buttons.forEach(function (button) {
	                	button.addEventListener('click', function() {
		                  auth2.signIn();
		                });
	                })
	            });
	        });
		</script>
	</body>
</html>