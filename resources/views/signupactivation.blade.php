@component('mail::message')

    Thank you for register twitter feeder app.

    Activation code: {{ $activation_code }}

    @component('mail::button', ['url' => $url])
        Activation link
    @endcomponent

@endcomponent
