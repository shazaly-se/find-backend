@component('mail::message')
# Dear, {{$user['name']}}
You are receiving this email because to activate your dashboard account .

UserName is: {{$user['username']}} ,<br>
Password: {{$user['password']}}

@component('mail::button', ['url' => 'http://localhost:3000/login'])
Go to site
@endcomponent

Thanks,<br>
management
@endcomponent
