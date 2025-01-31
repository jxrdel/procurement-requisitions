<x-mail::message>

<p>Good day,</p>

<p>Please be advised that requisition number <strong>{{ $requisition->requisition_no }}</strong> has been sent to Check Staff for your attention on {{ \Carbon\Carbon::parse($requisition->check_room_requisition->date_received)->format('F jS, Y') }}.</p>

<x-mail::button :url="$url">
    View Requisition
</x-mail::button>

</x-mail::message>
