<x-mail::message>

<p>Good day {{ $recipient }},</p>

<p>Please be advised that a requisition form from your department has been approved by procurement</p>

<p><strong>Form Details:</strong></p>

<ul>
    <li><strong>Code:</strong> {{ $form->form_code }}</li>
    <li><strong>Date Approved:</strong> {{ $form->procurement_approval_date->format('F jS, Y') }}</li>
</ul>

<x-mail::button :url="$url">
    View Form
</x-mail::button>

</x-mail::message>