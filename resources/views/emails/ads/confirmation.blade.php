@component('mail::message')
# your Ad has been received

Thank you for posting an advertisement. It will be reviewed by the administration as soon as possible.

@component('mail::button', ['url' => config('app.url') . '/ads/' . $ad->id])
Button Text
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
