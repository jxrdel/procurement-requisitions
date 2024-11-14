<x-mail::message>

<p>Good day,</p>

<p>Please be advised that requisition number <strong>{{$requisition->requisition_no}}</strong> has been successfully completed on {{ \Carbon\Carbon::parse($requisition->date_completed)->format('F jS, Y') }}.</p>

<x-mail::button :url="$url">
View Requisition
</x-mail::button>

</x-mail::message>