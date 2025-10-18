<x-mail::message>

<p>Good day {{ $recipient }},</p>

<p>Please be advised that a requisition form has been updated as requested and has been resubmitted for your review and approval.</p>

<p><strong>Form Details:</strong></p>

<ul>
    <li><strong>Code:</strong> {{ $form->form_code }}</li>
    <li><strong>Submitted By:</strong> {{ $form->contactPerson->name }}</li>
</ul>

<x-mail::button :url="$url">
    View Form
</x-mail::button>

</x-mail::message>