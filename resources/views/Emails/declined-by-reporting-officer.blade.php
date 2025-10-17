<x-mail::message>

<p>Good day {{ $recipient }},</p>

<p>Please be advised that a requisition form has been declined by {{ $form->reportingOfficer->reporting_officer_role }} {{ $form->reportingOfficer->name }}</p>

<p><strong>Details:</strong></p>

<ul>
    <li><strong>Form Code:</strong> {{ $form->form_code }}</li>
    <li><strong>Reason:</strong> {{ $form->reporting_officer_reason_for_denial }}</li>
</ul>

<x-mail::button :url="$url">
    View Form
</x-mail::button>

</x-mail::message>