<x-mail::message>
# Requisition Form Approved by Cost & Budgeting

Cost & Budgeting has entered the details regarding the availability of funding for the requisition form with code **{{ $form->form_code }}**.

Please review the form and take the necessary action.

<x-mail::button :url="$url">
View Requisition Form
</x-mail::button>

</x-mail::message>
