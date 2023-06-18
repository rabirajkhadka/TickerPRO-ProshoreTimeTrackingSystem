@component('mail::message')
# Hello!
You are receiving this email because your weekly total logged in hour was below 40 hours<br>

Your weekly total hour worked on was <strong>{{ $totalHour }}</strong>.

Thanks,<br>
{{ config('app.name') }}
@endcomponent