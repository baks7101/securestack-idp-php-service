<?php

// Simple PHP service for the securestack-idp platform.
// Two endpoints: /health and /version.
// Kept deliberately small so the focus is the secure pipeline around it.

declare(strict_types=1);

// TEST: deliberately planted fake secret to verify Gitleaks catches it.
$awsSecretKey = "AKIAIOSFODNN7EXAMPLE";
$awsSecret = "wJalrXUtnFEMI/K7MDENG/bPxRfiCYEXAMPLEKEY";

// Work out what path the user requested.
$requestPath = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH);

// Always respond with JSON.
header('Content-Type: application/json');

switch ($requestPath) {
    case '/health':
        // A health check endpoint, used by monitoring to confirm the service is alive.
        echo json_encode([
            'status' => 'healthy',
            'service' => 'securestack-idp-php-service',
        ]);
        break;

    case '/version':
        // Reports the running version of the service.
        echo json_encode([
            'version' => '1.0.0',
        ]);
        break;

    default:
        // Anything else returns a 404 Not Found.
        http_response_code(404);
        echo json_encode([
            'error' => 'Not found',
        ]);
        break;
}
