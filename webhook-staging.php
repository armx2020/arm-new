<?php
/**
 * GitHub Webhook Handler for STAGING Environment
 * 
 * This script handles GitHub webhook events and triggers deployment
 * for the staging server (VPS "Копия arm")
 * 
 * Setup:
 * 1. Place this file in /var/www/staging.vsearmyne.ru/webhook-staging.php
 * 2. Set WEBHOOK_SECRET in your environment or directly in this file
 * 3. Make sure deploy-staging.sh is executable: chmod +x deploy-staging.sh
 * 4. Configure GitHub webhook: https://github.com/armx2020/arm-new/settings/hooks
 *    - Payload URL: http://78.40.219.141/webhook-staging.php
 *    - Content type: application/json
 *    - Secret: [your webhook secret]
 *    - Events: Just the push event
 */

// Webhook secret - change this to a secure random string
$webhookSecret = getenv('WEBHOOK_SECRET_STAGING') ?: 'your-staging-webhook-secret-here';

// Log file for debugging
$logFile = __DIR__ . '/webhook-staging.log';

// Function to log messages
function logMessage($message) {
    global $logFile;
    $timestamp = date('Y-m-d H:i:s');
    file_put_contents($logFile, "[$timestamp] $message\n", FILE_APPEND);
}

// Get the payload and signature
$payload = file_get_contents('php://input');
$signature = $_SERVER['HTTP_X_HUB_SIGNATURE_256'] ?? '';

// Verify the signature
$expectedSignature = 'sha256=' . hash_hmac('sha256', $payload, $webhookSecret);

if (!hash_equals($expectedSignature, $signature)) {
    logMessage('ERROR: Invalid signature');
    http_response_code(403);
    die('Invalid signature');
}

// Decode the payload
$data = json_decode($payload, true);

// Check if this is a push event to main branch
if (!isset($data['ref']) || $data['ref'] !== 'refs/heads/main') {
    logMessage('INFO: Not a push to main branch, ignoring');
    http_response_code(200);
    die('Not a push to main branch');
}

// Log the push event
$pusher = $data['pusher']['name'] ?? 'unknown';
$commit = substr($data['after'] ?? '', 0, 7);
logMessage("INFO: Push event from $pusher (commit: $commit)");

// Execute deployment script
$deployScript = __DIR__ . '/deploy-staging.sh';

if (!file_exists($deployScript)) {
    logMessage('ERROR: Deploy script not found: ' . $deployScript);
    http_response_code(500);
    die('Deploy script not found');
}

// Run deployment in background
$output = shell_exec("bash $deployScript 2>&1 &");
logMessage("INFO: Deployment started");
logMessage("OUTPUT: $output");

// Return success
http_response_code(200);
echo json_encode([
    'status' => 'success',
    'message' => 'Deployment started',
    'commit' => $commit
]);
