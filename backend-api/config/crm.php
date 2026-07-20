<?php

return [
    /*
    |--------------------------------------------------------------------------
    | CRM Configuration
    |--------------------------------------------------------------------------
    |
    | Here you can configure the behavior of the CRM module.
    |
    | lost_customer_days: Configures the period of inactivity (in days)
    | before a customer is flagged as "lost" or "inactive".
    |
    */
    'lost_customer_days' => (int) env('LOST_CUSTOMER_DAYS', 90),
];
