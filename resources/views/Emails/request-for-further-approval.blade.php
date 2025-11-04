<x-mail::message>

A requisition form with code **{{ $requisitionForm->form_code }}** has been forwarded to you by **{{ $forwardedBy->name }}** for your further approval.

**Message:**
{{ $message }}

<x-mail::button :url="$url">
View Requisition Form
</x-mail::button>

</x-mail::message>
