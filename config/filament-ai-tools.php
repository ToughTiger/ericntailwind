<?php

return [
    'openai_api_key' => env('OPENAI_API_KEY', ''),
     'default_model' => 'gpt-4',
    'default_temperature' => 0.7,
    'max_tokens' => 2000,
    'timeout' => 30,
];
