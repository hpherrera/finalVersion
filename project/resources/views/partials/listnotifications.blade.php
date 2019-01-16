@foreach($Notifications as $notification)
<li>
	<a onclick="viewNotification('{{ $notification->id }}')">{{ $notification->texto }}</a>
</li>
@endforeach