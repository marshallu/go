<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Symfony\Component\Process\Process;
use Illuminate\Support\Facades\Route;

Route::post('/deploy', function () {
    // if (!request()->hasHeader('X-Deploy-Token') || request()->header('X-Deploy-Token') !== env('DEPLOY_SECRET')) {
    //     abort(403, 'Unauthorized');
    // }

    try {
        // Run the script as `dks`
        $process = new Process(['sudo', '-u', 'dks', '/home/dks/deploy-go.marshall.edu.sh']);
        $process->setTimeout(0); // No timeout
        $process->disableOutput(); // Don't hold up PHP
        $process->start(); // Run in background

        return response()->json([
            'status' => 'success',
            'message' => 'Deployment started as dks.',
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'status' => 'error',
            'message' => $e->getMessage(),
        ], 500);
    }
});
