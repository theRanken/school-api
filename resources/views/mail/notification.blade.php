<x-mail::message>
# {{$subject}}

Hello {{$user}}

<x-mail::panel>
{{ $body }}
</x-mail::panel>

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
