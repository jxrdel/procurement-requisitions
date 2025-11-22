<x-mail::message>

<p>Good day,</p>

@if ($requisition->is_first_pass)
<p>Please be advised that a request for commitment of funds has been sent to Vote Control for requisition number <strong>{{$requisition->requisition_no}}</strong> on {{ $date_sent }}.</p>
@else
<p>Please be advised that invoice(s) for <strong>{{$vendor->vendor_name}}</strong> under requisition number <strong>{{$requisition->requisition_no}}</strong> have been sent to Vote Control for your attention on {{ $date_sent }}.</p>
@endif

<x-mail::button :url="$url">
    View Requisition
</x-mail::button>

</x-mail::message>
