<?php

namespace App\Models;

use Carbon\Carbon;
use DB;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property string event_name
 * @property string group_name
 * @property string description
 * @property string uri
 * @property int    rsvp_count
 * @property int    venue_id
 * @property Venue  venue
 * @property Carbon active_at
 * @property array  cache
 */
class Event extends Model
{
	use SoftDeletes;

	protected $table = 'events';

	protected $fillable
		= [
            'event_name',
            'group_name',
            'description',
            'rsvp_count',
            'active_at',
            'expire_at',
            'uri',
            'venue_id',
            'cache',
            'event_uuid',
        ];

	protected $casts
		= [
			'cache' => 'json',
		];

	protected $dates
		= [
            'created_at',
            'updated_at',
            'deleted_at',
            'active_at',
            'expire_at',
		];

	protected $appends
		= [
			'short_description',
			'title',
			'active_at_ftm',
		];

    public function venue()
    {
        return $this->belongsTo(Venue::class);
    }

    public function scopeGetActive($query)
    {
        return $query->where('active_at', '>=', DB::raw('NOW()'))->orderBy('active_at', 'asc');
    }

    public function scopeStartAndEndDatesAreLike(Builder $query, $start, $end)
    {
        return $query
            ->whereBetween(
                DB::raw('DATE(`active_at`)'),
                [
                    date('Y-m-d', strtotime($start)),
                    date('Y-m-d', strtotime($end)),
                ]
            );
    }

    /**
     * accessor url to uri
	 * @return string
	 */
	public function getUrlAttribute()
	{
		return $this->uri;
	}

	public function getStateAttribute()
	{
		if ($this->active_at->isPast()) {
			return 'passed';
		}

		//TODO :: create the condition that returns the status of live
		if (false) {
			return 'live';
		}

		return 'upcoming';
	}

	/**
	 * build out the link that adds this event to the users personal calendar
	 * @return string
	 */
	public function getGCalUrlAttribute()
	{
		$event_time = $this->active_at->format('Y-m-d\TH:i:s\Z');

		$start_time = $this->active_at->format('Ymd\THis\Z');

		// Assume event is two hours long...
		$end_time = $this->active_at->addHours(2)->format('Ymd\THis\Z');

		$location = '';

		if (property_exists($this, 'venue') && $this->venue != null):
			$location .= $this->venue->name . ', ';
			$location .= $this->venue->address . ', ';
			$location .= $this->venue->city . ', ';
			$location .= $this->venue->state;
		endif;

		$calendar_url = "http://www.google.com/calendar/event?action=TEMPLATE&";
		$calendar_url .= 'text=' . urlencode($this->event_name) . '&';
		$calendar_url .= "dates=$start_time/$end_time&";
		$calendar_url .= 'details=' . urlencode(strip_tags($this->description)) . '&';
		$calendar_url .= 'location=' . urlencode($location) . '&';
		$calendar_url .= "trp=false&";

		return $calendar_url;
	}

	public function getLocalActiveAtAttribute()
	{
		return $this->active_at->tz(config('app.timezone'));
	}

	public function getDescriptionAttribute()
	{
		return str_replace('<a', '<a target="_blank"', $this->attributes['description']);
	}

	public function getShortDescriptionAttribute()
	{
		return str_limit($this->description);
	}

	public function getActiveAtFtmAttribute()
	{
		return $this->active_at->diffForHumans();
	}

	public function getTitleAttribute()
	{
		return $this->event_name;
	}
}
