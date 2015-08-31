@if(Session::has('flash_notification.message'))
	@if(Session::has('flash_notification.overlay'))
		@include('metrique-plonk::modal', [
			'modalId' => 'flash-modal',
			'title' => Session::get('flash_notification.title'),
			'body' => Session::get('flash_notification.message'),
		])
	@else
		<div class="alert-box {{ Session::get('flash_notificaiton.level') }}" data-alert>
			{{ Session::get('flash_notification.message') }}
			<a href="#" class="close">&times;</a>
		</div>
	@endif
@endif