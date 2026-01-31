<!-- <?php
// test-webhook-simple.php
echo "=== Probando conexión con Laravel ===\n\n";

// Test 1: Verificar que la ruta existe
echo "1. Probando ruta GET (debería dar 405 Method Not Allowed):\n";
$ch = curl_init('http://localhost/stripe/webhook');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
echo "   HTTP Code: $httpCode (esperado: 405)\n";
curl_close($ch);

echo "\n2. Probando ruta POST sin CSRF (debería dar error de firma):\n";
$ch = curl_init('http://localhost/stripe/webhook');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode(['test' => true]));
curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
echo "   HTTP Code: $httpCode\n";
echo "   Response: " . substr($response, 0, 100) . "...\n";
curl_close($ch);

echo "\n=== Si el código HTTP es 419, CSRF aún está activo ===\n";
echo "=== Si es 400, CSRF está desactivado pero la firma es inválida (ESPERADO) ===\n"; -->