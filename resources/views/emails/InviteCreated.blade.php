@component('mail::message')
# Hello!
You are receiving this email because you are invited to join the team<br>

This registration link will expire in 5 days<br>

If you did not request a registration request, no further action is required

@component('mail::button', ['url' => $url ])
Register Now
@endcomponent
Thanks,<br>
{{ config('app.name') }}
@endcomponent
