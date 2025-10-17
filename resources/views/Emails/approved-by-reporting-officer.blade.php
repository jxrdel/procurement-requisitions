<x-mail::message>

<p>Good day {{ $recipient }},</p>

<p>Please be advised that a requisition form has been approved by {{ $form->reportingOfficer->reporting_officer_role }} {{ $form->reportingOfficer->name }}</p>

<p><strong>Details:</strong></p>

<ul>
    <li><strong>Code:</strong> {{ $form->form_code }}</li>
    <li><strong>Requesting Unit:</strong> {{ $form->requestingUnit->name }}</li>
    <li><strong>Date Approved:</strong> {{ $form->reporting_officer_approval_date->format('F jS, Y') }}</li>
</ul>

<x-mail::button :url="$url">
    View Form
</x-mail::button>

</x-mail::message>