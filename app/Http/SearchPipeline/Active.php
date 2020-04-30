<?php

namespace App\Http\SearchPipeline;


use DB;

class Active extends BaseSearchPipeline
{
	protected $force = true;

	protected function applyFilter($builder)
	{
		return $builder->where('active_at', '>=', DB::raw('NOW()'))->orderBy('active_at', 'asc');
	}
}
