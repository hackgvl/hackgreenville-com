<x-mail::message>
Hello {{ $first_name }},

## Welcome to [HackGreenville.com]({{ route('home') }})!

Your website login is '{{ $email }}'. Please click below to reset your password and then you will be able to sign in.

<x-mail::button :url="$password_reset_url">
Reset Password
</x-mail::button>

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
