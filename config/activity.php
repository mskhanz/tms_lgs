<?php

return [

    /*
    | Users are treated as online when their current session was active
    | within this many minutes.
    */
    'online_minutes' => (int) env('ACTIVITY_ONLINE_MINUTES', 5),

    /*
    | Minimum seconds between last_activity_at writes for the same session.
    */
    'touch_interval_seconds' => (int) env('ACTIVITY_TOUCH_INTERVAL', 30),

];
