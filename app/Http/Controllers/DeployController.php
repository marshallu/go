<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;

class DeployController extends Controller
{
    public function deploy(Request $request)
    {
        // Optional: Check for a secret token in the request for extra security
        // if ($request->input('token') !== config('app.deploy_token')) {
        //     abort(403, 'Unauthorized');
        // }

        try {
            // Run the deployment script in the background
            $process = new Process(['/bin/bash', '/home/dks/deploy-go.marshall.edu.sh']);
            $process->setTimeout(0); // Set no timeout
            $process->disableOutput(); // Don't hold up PHP
            $process->start(); // Run in background

            return response()->json([
                'status' => 'success',
                'message' => 'Deployment started.',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
            ], 500);
        }
    }
}
