<a href="{{ $project->link }}" rel="noopener" target="_blank"
   class="flex items-center gap-3 px-4 py-3 hover:bg-gray-50 transition-colors no-underline">
	<div class="flex-grow min-w-0">
		<div class="mb-0.5">
			<span class="text-gray-900 font-semibold text-sm">{{ $project->name }}</span>
			<span class="text-gray-400 mx-1">&mdash;</span>
			<span class="text-gray-600 text-sm">{{ $project->description }}</span>
		</div>
		<span class="text-xs text-gray-400">
			@if($project->linkType === 'github')
				<x-lucide-github class="w-3 h-3 inline"/> Github
			@else
				<x-lucide-external-link class="w-3 h-3 inline"/> Website
			@endif
		</span>
	</div>
	@switch($project->status)
		@case('active')
			<span class="shrink-0 text-xs font-semibold px-2 py-0.5 rounded bg-green-50 text-green-700">Active</span>
			@break
		@case('considering')
			<span class="shrink-0 text-xs font-semibold px-2 py-0.5 rounded bg-yellow-50 text-yellow-700">Considering</span>
			@break
		@case('retired')
			<span class="shrink-0 text-xs font-semibold px-2 py-0.5 rounded bg-gray-100 text-gray-500">Retired</span>
			@break
	@endswitch
</a>
