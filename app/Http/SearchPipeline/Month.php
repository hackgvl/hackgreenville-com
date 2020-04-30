<?php

namespace App\Http\SearchPipeline;


use Carbon\Carbon;

class Month extends BaseSearchPipeline
{
	protected function applyFilter($builder)
	{
		$date = new Carbon(request($this->paramName()));

		return $builder->where('active_at', '>=', $date);
	}
}
