<?php


namespace App\Http\SearchPipeline;


use Closure;
use Illuminate\Support\Str;

abstract class BaseSearchPipeline
{
	protected $param_name = null;
	protected $force      = false;

	public function handle($request, Closure $next)
	{
		// Bypass filter if no param for it or if the value of the param is null
		if (!$this->force && (request($this->paramName()) === null && !request()->has($this->filterName()))) {
			return $next($request);
		}

		$builder = $next($request);

		return $this->applyFilter($builder);
	}

	protected function paramName()
	{
		if ($this->param_name) {
			return $this->param_name;
		}

		return Str::kebab(class_basename($this));
	}

	/**
	 * get snake case of the class name
	 * @return string
	 */
	protected function filterName()
	{
		return Str::snake(class_basename($this));
	}

	abstract protected function applyFilter($builder);
}
