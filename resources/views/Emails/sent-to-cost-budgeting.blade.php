<x-mail::message>

<p>Good day,</p>

<p>Please be advised that requisition number <strong>{{$requisition->requisition_no}}</strong> has been sent to Cost & Budgeting for your attention on {{ \Carbon\Carbon::parse($requisition->date_sent_cb)->format('F jS, Y') }}.</p>

<x-mail::button :url="$url">
View Requisition
</x-mail::button>

</x-mail::message>