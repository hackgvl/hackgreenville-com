<?php /** @var \Galahad\Aire\Elements\Attributes\Collection $attributes */ ?>

<div {{ $attributes }}>
	{{ $label }}
	
	<div class="{{ $prepend || $append ? 'flex' : '' }}">
		@if($prepend)
			<div {{ $attributes->prepend }}>
				{{ $prepend }}
			</div>
		@endif
		
		{{ $element }}
			
		@if($append)
			<div {{ $attributes->append }}>
				{{ $append }}
			</div>
		@endif
	</div>
	
	<ul class="mt-2 mb-3" 
	    data-aire-component="errors" 
	    data-aire-validation-key="group_errors" 
	    data-aire-for="{{ $attributes->get('data-aire-for') }}"
	    @if(!count($errors)) style="display: none;" @endif>
		@each($error_view, $errors, 'error')
	</ul>
	
	@isset($help_text)
		<small {{ $attributes->help_text }}>
			{{ $help_text }}
		</small>
	@endisset
	
</div>
