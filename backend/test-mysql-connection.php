<?php
/**
 * MySQL Connection Diagnostic Script
 * This script tests direct PHP-MySQL connection to isolate authentication issues
 */

echo "=== MySQL Connection Diagnostic Tool ===\n\n";

// Load environment variables from .env file
$envFile = __DIR__ . '/.env';
if (!file_exists($envFile)) {
    die("ERROR: .env file not found at {$envFile}\n");
}

// Parse Laravel .env file manually
$envVars = [];
$lines = file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
foreach ($lines as $line) {
    // Skip comments
    if (strpos(trim($line), '#') === 0) {
        continue;
    }
    // Parse KEY=VALUE
    if (strpos($line, '=') !== false) {
        list($key, $value) = explode('=', $line, 2);
        $key = trim($key);
        $value = trim($value, " \t\n\r\0\x0B\"'");
        $envVars[$key] = $value;
    }
}

$host = $envVars['DB_HOST'] ?? '127.0.0.1';
$port = $envVars['DB_PORT'] ?? '3306';
$database = $envVars['DB_DATABASE'] ?? '';
$username = $envVars['DB_USERNAME'] ?? '';
$password = $envVars['DB_PASSWORD'] ?? '';

echo "Configuration from .env:\n";
echo "  DB_HOST: {$host}\n";
echo "  DB_PORT: {$port}\n";
echo "  DB_DATABASE: {$database}\n";
echo "  DB_USERNAME: {$username}\n";
echo "  DB_PASSWORD: " . (empty($password) ? '(empty)' : str_repeat('*', strlen($password))) . "\n\n";

// Test 1: MySQLi Connection
echo "Test 1: MySQLi Connection\n";
echo str_repeat('-', 50) . "\n";
$mysqli = @new mysqli($host, $username, $password, $database, $port);

if ($mysqli->connect_errno) {
    echo "❌ FAILED: " . $mysqli->connect_error . "\n";
    echo "   Error Code: " . $mysqli->connect_errno . "\n\n";
} else {
    echo "✅ SUCCESS: Connected via MySQLi\n";
    echo "   Server Version: " . $mysqli->server_info . "\n";
    echo "   Protocol Version: " . $mysqli->protocol_version . "\n\n";
    $mysqli->close();
}

// Test 2: PDO Connection (Laravel uses PDO)
echo "Test 2: PDO Connection (Laravel's method)\n";
echo str_repeat('-', 50) . "\n";
try {
    $dsn = "mysql:host={$host};port={$port};dbname={$database};charset=utf8mb4";
    $options = [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ];

    $pdo = new PDO($dsn, $username, $password, $options);
    echo "✅ SUCCESS: Connected via PDO\n";
    echo "   PDO Driver: " . $pdo->getAttribute(PDO::ATTR_DRIVER_NAME) . "\n";
    echo "   Server Version: " . $pdo->getAttribute(PDO::ATTR_SERVER_VERSION) . "\n\n";

    // Test query
    $stmt = $pdo->query("SELECT DATABASE() as current_db");
    $result = $stmt->fetch();
    echo "   Current Database: " . $result['current_db'] . "\n\n";

} catch (PDOException $e) {
    echo "❌ FAILED: " . $e->getMessage() . "\n";
    echo "   Error Code: " . $e->getCode() . "\n\n";
}

// Test 3: Check PHP MySQL extensions
echo "Test 3: PHP MySQL Extensions\n";
echo str_repeat('-', 50) . "\n";
echo "  mysqli extension: " . (extension_loaded('mysqli') ? '✅ Loaded' : '❌ Not loaded') . "\n";
echo "  pdo extension: " . (extension_loaded('pdo') ? '✅ Loaded' : '❌ Not loaded') . "\n";
echo "  pdo_mysql extension: " . (extension_loaded('pdo_mysql') ? '✅ Loaded' : '❌ Not loaded') . "\n\n";

// Test 4: Check special characters in password
echo "Test 4: Password Analysis\n";
echo str_repeat('-', 50) . "\n";
if (preg_match('/[!@#$%^&*(){}[\]:;"\'<>,.?\/\\|`~]/', $password)) {
    echo "⚠️  WARNING: Password contains special characters that may need escaping\n";
    echo "   Consider using a password with only alphanumeric characters\n\n";
} else {
    echo "✅ Password uses safe characters (alphanumeric only)\n\n";
}

echo "=== Diagnostic Complete ===\n";
echo "\nRecommendations:\n";
echo "- If MySQLi works but PDO fails: Check PDO/MySQL driver installation\n";
echo "- If both fail: Issue is with MySQL user credentials or permissions\n";
echo "- If script works but Laravel fails: Check Laravel config cache\n";
echo "  Run: php artisan config:clear && php artisan config:cache\n";
