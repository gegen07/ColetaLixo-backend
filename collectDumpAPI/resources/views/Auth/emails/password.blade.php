<h2>Password Reset</h2>

	<div>
		To reset your password, complete this form: <a href="{{ url('192.168.99.100:8080/api/v1/password/reset, [token => $token]') }}">
			{{ url('password.reset, [token => $token]') }}</a> <br />
    This link will expire in {{
		config('auth.reminder.expire', 60) }} minutes.
	</div>
