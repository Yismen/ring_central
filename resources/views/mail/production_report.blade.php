@component('mail::message')
# {{ $title }}

Please see attached the {{ $title }} updated!

Thanks,<br>
{{ config('app.name') }}
@endcomponent