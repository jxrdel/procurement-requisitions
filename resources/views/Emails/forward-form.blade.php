<x-mail::message>
# Requisition Form Forwarded

A requisition form with code **{{ $requisitionForm->form_code }}** has been forwarded to you by **{{ $forwardedBy->name }}**.

**Forwarding Minute:**

{{ $forwarding_minute }}

<x-mail::button :url="$url">
View Requisition Form
</x-mail::button>

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
