<x-mail::message>

A new requisition form has been sent to Cost & Budgeting to determine the availability of funding.

**Form Code:** {{ $form->form_code }}

Please review the form and take the necessary action.

<x-mail::button :url="$url">
View Requisition Form
</x-mail::button>

</x-mail::message>
