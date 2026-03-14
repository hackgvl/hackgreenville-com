<?php

namespace HackGreenville\SlackEventsBot\Exceptions;

use Exception;

class UnsafeMessageSpilloverException extends Exception
{
    protected $message = 'Cannot update messages as it would cause message spillover';
}
