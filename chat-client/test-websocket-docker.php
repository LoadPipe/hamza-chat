<?php
// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

require 'vendor/autoload.php';

use WebSocket\Client;

// Create WebSocket connection
$ws = new Client("ws://172.24.0.1:4444");

try {
    // Send a test message
    $ws->send(json_encode([
        'type' => 'test',
        'message' => 'Hello Server!'
    ]));
    
    // Receive response
    $response = $ws->receive();
    echo "Received: " . $response . "\n";
    
} catch (WebSocket\ConnectionException $e) {
    echo "Connection Error: " . $e->getMessage() . "\n";
    echo "Make sure the WebSocket server is running and accessible\n";
    // Add more detailed error information
    echo "Trying to connect to: ws://172.24.0.1:4444\n";
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
} finally {
    // Close the connection
    if (isset($ws)) {
        $ws->close();
    }
}
?> 