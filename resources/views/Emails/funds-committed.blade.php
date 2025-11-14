@component('mail::message')

Please be advised that funds have been committed by Vote Control for requisition <strong>#{{$requisition->requisition_no}}</strong> and sent for your attention on {{ $date_sent }}.

@component('mail::button', ['url' => $url])
View Requisition
@endcomponent

@endcomponent
