@component('mail::message')

# Dear, {{$offer['name']}}
You are receiving this email because to activate your account in our website.

UserName is: {{$offer['username']}} ,<br>
Password: {{$offer['password']}}

@component('mail::button', ['url' => 'http://localhost:3000/login'])
Go to site

@endcomponent

Thanks,<br>
management
@endcomponent
