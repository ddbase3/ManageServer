<?php
// Authentifizierung (z. B. per API-Key)
$apiKey = 'MY_API_KEY';
if (!isset($_GET['key']) || $_GET['key'] !== $apiKey) {
    http_response_code(403);
    echo json_encode(['error' => 'Unauthorized']);
    exit;
}

// Systeminformationen sammeln
$status = [
    'time' => date('c'),
    'hostname' => gethostname(),
    'uptime' => @trim(shell_exec('uptime -p')),
    'load_average' => sys_getloadavg(),
    'disk' => getDiskInfoShell('/'),
    'memory' => getMemoryInfoShell(),
    'php_version' => phpversion()
];

// Festplattennutzung via df
function getDiskInfoShell(string $mount): array {
    $output = @shell_exec("df -k --output=size,avail,target | grep ' $mount'");
    if (!$output) {
        return ['error' => 'df command failed or mount point not found'];
    }

    [$size, $avail, ] = preg_split('/\s+/', trim($output));
    return [
        'total_kb' => (int) $size,
        'free_kb' => (int) $avail
    ];
}

function getMemoryInfoShell(): array {
    $output = @shell_exec('free -k');
    if (!$output) {
        return ['error' => 'could not execute free command'];
    }

    $lines = explode("\n", trim($output));
    foreach ($lines as $line) {
        if (strpos($line, 'Mem:') === 0) {
            $parts = preg_split('/\s+/', $line);
            return [
                'total_kb' => (int) $parts[1],
                'used_kb' => (int) $parts[2],
                'free_kb' => (int) $parts[3],
                'available_kb' => isset($parts[6]) ? (int) $parts[6] : null
            ];
        }
    }
    return ['error' => 'unexpected format from free command'];
}

// Ausgabe als JSON
header('Content-Type: application/json');
echo json_encode($status, JSON_PRETTY_PRINT);

