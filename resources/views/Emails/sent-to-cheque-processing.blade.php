<x-mail::message>

<p>Good day,</p>

<p>Please be advised that invoices for <strong>{{$vendor->vendor_name}}</strong> under requisition number <strong>{{$requisition->requisition_no}}</strong> have been sent to Cheque Processing for your attention on {{ $date_sent }}.</p>

<x-mail::button :url="$url">
    View Requisition
</x-mail::button>

</x-mail::message>
