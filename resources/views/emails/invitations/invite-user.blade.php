@component('mail::message')
# Hi,

You have benn invited to join the team
**{{$invitation->team->name}}**.
Because you are already registered to the platform, you just need to accept or reject the invitation in your
[team management console]({{$url}})

@component('mail::button', ['url' => $url])
Register for free
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
