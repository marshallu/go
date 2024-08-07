<?php

use Illuminate\Support\Facades\Schedule;

Schedule::command('go:delete-old')->daily();
