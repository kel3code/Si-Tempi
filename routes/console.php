<?php
use Illuminate\Support\Facades\Schedule;
Schedule::command('tempi:catat-harian')->dailyAt('00:05');
