<x-mail::message>
You have been assigned a new requisition.

**Requisition No:** {{ $requisition->requisition_no }}
**Date Assigned:** {{ \Carbon\Carbon::parse($requisition->date_assigned)->format('d/m/Y') }}

<x-mail::button :url="route('requisitions.view', $requisition->id)">
View Requisition
</x-mail::button>

</x-mail::message>
