<x-mail::message>

<p>Good day,</p>

<p>Please be advised that Cost & Budgeting have completed their processing of requisition number <strong>{{$requisition->requisition_no}}</strong> on {{ \Carbon\Carbon::parse($requisition->cost_budgeting_requisition->date_completed)->format('F jS, Y') }}.</p>

<x-mail::button :url="$url">
View Requisition
</x-mail::button>

</x-mail::message>