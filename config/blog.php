<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Daily Email Notification Limit
    |--------------------------------------------------------------------------
    |
    | The maximum number of post-published notification emails that can be
    | sent per calendar day. This respects your SMTP provider's daily quota.
    | When the limit is hit, remaining subscribers are notified the next day.
    |
    */

    'daily_email_limit' => (int) env('DAILY_EMAIL_LIMIT', 300),

];
