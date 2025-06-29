<?php

// 1. Load Environment Variables FIRST
require_once __DIR__ . '/../app/Config/env.php';
use App\Config\Env;
Env::load();

// 2. Set Application Timezone from .env
$appTimeZone = Env::get('APP_TIMEZONE', 'UTC');
date_default_timezone_set($appTimeZone);

// 3. Load the Autoloader (so classes can be used)
require_once __DIR__ . '/../bootstrap/autoload.php';

// 4. (Optional) Debug - Confirm Timezone
// echo 'Current Timezone: ' . date_default_timezone_get(); // optional

// 5. Load Routes or Run Application
require_once __DIR__ . '/../routes/web.php';
