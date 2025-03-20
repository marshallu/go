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
            // We use sudo to run the script as the "dks" user.
            // Ensure that the web server user (e.g., nginx) is allowed via sudoers
            $process = new Process(['sudo', '-u', 'dks', '/home/dks/deploy-go.marshall.edu.sh']);
            $process->setTimeout(300); // 5 minutes
            $process->run();

            if (!$process->isSuccessful()) {
                throw new ProcessFailedException($process);
            }

            $output = $process->getOutput();

            return response()->json([
                'status' => 'success',
                'output' => $output,
            ]);

        } catch (ProcessFailedException $e) {
            return response()->json([
                'status' => 'failed',
                'error'  => $e->getMessage(),
            ], 500);
        }
    }
}
