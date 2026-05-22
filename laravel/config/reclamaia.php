<?php

return [
    'document_price_cents' => (int) env('DOCUMENT_PRICE_CENTS', 999),
    'subscription_price_cents' => (int) env('SUBSCRIPTION_PRICE_CENTS', 2999),
    'python_service_url' => env('PYTHON_SERVICE_URL', 'http://localhost:8001'),
    'python_timeout_seconds' => (int) env('PYTHON_TIMEOUT_SECONDS', 35),
    'internal_api_secret' => env('INTERNAL_API_SECRET', ''),
];
