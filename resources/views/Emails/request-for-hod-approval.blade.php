<x-mail::message>

<p>Good day {{ $recipient }},</p>

<p>Please be advised that a new requisition form has been sent for your approval.</p>

<p><strong>Minute:</strong></p>

<p>{{ $form->contact_person_note }}</p>

<p><strong>Form Details:</strong></p>

<ul>
    <li><strong>Code:</strong> {{ $form->form_code }}</li>
    <li><strong>Submitted By:</strong> {{ $form->contactPerson->name }}</li>
    <li><strong>Date Sent:</strong> {{ $form->date_sent_to_hod->format('F jS, Y') }}</li>
</ul>

<x-mail::button :url="$url">
    View Form
</x-mail::button>

</x-mail::message>