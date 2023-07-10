<?php ?>

<a
	href="{{ $project->link }}"
	target="_blank"
	style="text-decoration: none"
	@class([
		'm-0 list-group-item project d-flex border-1 shadow-sm',
		'rounded-top' => $index == 0,
		'rounded-bottom' => $index == ($total - 1)
	])
>
	<div class="d-flex flex-column flex-grow-1">
		<div class="mb-1">
			<p class="d-inline text-black font-weight-bold">{{ $project->name }}</p>
			<p class="d-inline mx-1">—</p>
			<p class="d-inline">{{ $project->description }}</p>
		</div>
		<div>
			@switch($project->linkType)
				@case('github')
					<i class="fa fas fa-github mx-1"></i>
					{{ __('Github') }}
					@break

				@default
					<i class="fa fas fa-external-link mx-1"></i>
					{{ __('Website') }}
					@break
			@endswitch
		</div>
	</div>
	@switch($project->status)
		@case('active')
			<p
				class="d-flex justify-content-center px-1 rounded mb-auto ml-auto font-weight-bold"
				style="color: green; background-color: rgb(220, 255, 220)"
			>
				{{ __('Active') }}
			</p>
			@break

		@case('considering')
			<p
				class="d-flex justify-content-center px-1 rounded mb-auto ml-auto font-weight-bold"
				style="color: rgb(100, 99, 33); background-color: rgb(247, 245, 163)"
			>
				{{ __('Considering') }}
			</p>
			@break

		@case('retired')
			<p
				class="d-flex justify-content-center px-1 rounded mb-auto ml-auto font-weight-bold"
				style="color: rgb(116, 116, 116); background-color: rgb(226, 226, 226)"
			>
				{{ __('Retired') }}
			</p>
			@break
	@endswitch
</a>
