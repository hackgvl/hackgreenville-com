<?php ?>

<a
	href="{{ $project->link }}"
	target="_blank"
	@class([
		'block m-0 p-4 project flex border border-gray-300 shadow-sm hover:shadow-md transition-shadow bg-white no-underline',
		'rounded-t-lg' => $index == 0,
		'rounded-b-lg' => $index == ($total - 1),
		'border-t-0' => $index > 0
	])
>
	<div class="flex flex-col flex-grow">
		<div class="mb-2">
			<span class="inline text-black font-bold">{{ $project->name }}</span>
			<span class="inline mx-1">—</span>
			<span class="inline text-gray-700">{{ $project->description }}</span>
		</div>
		<div class="text-sm text-gray-600">
			@switch($project->linkType)
				@case('github')
					<i class="fa fa-github mx-1"></i>
					{{ __('Github') }}
					@break

				@default
					<i class="fa fa-external-link mx-1"></i>
					{{ __('Website') }}
					@break
			@endswitch
		</div>
	</div>
	@switch($project->status)
		@case('active')
			<span
				class="flex justify-center items-center px-2 py-1 rounded text-sm font-bold ml-auto self-start"
				style="color: green; background-color: rgb(220, 255, 220)"
			>
				{{ __('Active') }}
			</span>
			@break

		@case('considering')
			<span
				class="flex justify-center items-center px-2 py-1 rounded text-sm font-bold ml-auto self-start"
				style="color: rgb(100, 99, 33); background-color: rgb(247, 245, 163)"
			>
				{{ __('Considering') }}
			</span>
			@break

		@case('retired')
			<span
				class="flex justify-center items-center px-2 py-1 rounded text-sm font-bold ml-auto self-start"
				style="color: rgb(116, 116, 116); background-color: rgb(226, 226, 226)"
			>
				{{ __('Retired') }}
			</span>
			@break
	@endswitch
</a>
