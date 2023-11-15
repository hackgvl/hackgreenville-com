<?php

use App\Data\EventDataTransformer;

function getTestEvent(): array
{
    return json_decode(
        file_get_contents(dirname(__FILE__) . '/../Data/event.json'),
        true
    );
}

it('Event time is converted correctly while for EST ', function () {
    // Default time of event is on a date that is in EST period.
    $result = EventDataTransformer::from(getTestEvent());

    expect($result->time->format('Y-m-d H:i:s'))->toBe('2023-11-13 19:00:00');
    // Expect a -5 hour offset
    expect($result->time->format('Z'))->toBe("-" . 5 * 60 * 60);
});

it('Event time is converted correctly while for EDT', function () {
    $test_event = getTestEvent();

    // Set event time to a date in EDT period.
    $test_event['time'] = '2023-07-04T00:00:00Z';

    $result = EventDataTransformer::from($test_event);

    expect($result->time->format('Y-m-d H:i:s'))->toBe('2023-07-03 20:00:00');
    // Expect a -4 hour offset
    expect($result->time->format('Z'))->toBe("-" . 4 * 60 * 60);
});
