<?php

$apiUrl = "http://localhost:8000/api/v1/generate-image"; // SSE endpoint
$prompt = "Generate a 3D flying pig in a futuristic city";

$ch = curl_init($apiUrl);

// Headers and POST body
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    "Content-Type: application/json",
]);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode(['prompt' => $prompt]));

// Do not wait for the full response
curl_setopt($ch, CURLOPT_RETURNTRANSFER, false);
curl_setopt($ch, CURLOPT_WRITEFUNCTION, function ($ch, $data) {
    // Split received chunk into lines
    $lines = explode("\n", $data);

    foreach ($lines as $line) {
        $line = trim($line);
        if (empty($line)) continue;

        // SSE sends lines starting with "data:"
        if (strpos($line, "data:") === 0) {
            $json = substr($line, 5); // remove "data:"
            $event = json_decode($json, true);

            if ($event) {
                if ($event['type'] === 'text') {
                    echo "[AI Text] " . $event['content'] . PHP_EOL;
                }

                if ($event['type'] === 'image') {
                    echo "[AI Image] URL: " . $event['content'] . PHP_EOL;
                }
            }
        }

        // Optional: handle "event: done"
        if (strpos($line, "event: done") === 0) {
            echo "[Stream ended]" . PHP_EOL;
        }
    }

    // Return number of bytes handled
    return strlen($data);
});

echo "Listening to SSE stream...\n";
curl_exec($ch);
curl_close($ch);
