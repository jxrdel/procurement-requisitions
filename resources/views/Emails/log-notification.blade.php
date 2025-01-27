<x-mail::message>

<p>Good day,</p>

<p>Please be advised that the following status log has been entered by {{ $log->user->name }} from {{ $log->user->department }}:</p>

<p><strong>{{ $log->details }}</strong></p>

<x-mail::button :url="$url">
    View Requisition
</x-mail::button>

</x-mail::message>
