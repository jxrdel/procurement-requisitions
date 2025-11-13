<x-mail::message>

<p>Good day,</p>

<p>Please be advised that requisition number <strong>{{$requisition->requisition_no}}</strong> has been sent to Accounts Payable for your attention on {{ $date_sent }}.</p>

<x-mail::button :url="$url">
View Requisition
</x-mail::button>

</x-mail::message>