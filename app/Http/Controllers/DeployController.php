<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\Process\Process;

class DeployController extends Controller
{
    public function deploy(Request $request)
    {
        $githubPayload = $request->getContent();
        $githubHash = $request->header('X-Hub-Signature-256');
        
        $localToken = config('app.deploy_secret');
        $localHash = 'sha256=' . hash_hmac('sha256', $githubPayload, $localToken, false);
        
        if (!hash_equals($githubHash ?? '', $localHash)) {
            Log::error('Invalid webhook signature');
            return response()->json(['error' => 'Invalid signature'], 403);
        }
        
        Log::info('Deployment webhook received');
        
        $rootPath = base_path();
        $process = Process::fromShellCommandline("cd {$rootPath} && ./deploy.sh");
        $process->setTimeout(300);
        
        $process->run(function ($type, $buffer) {
            Log::info($buffer);
        });
        
        if ($process->isSuccessful()) {
            return response()->json(['status' => 'Deployment successful'], 200);
        }
        
        Log::error('Deployment failed', ['output' => $process->getErrorOutput()]);
        return response()->json(['error' => 'Deployment failed'], 500);
    }
}
