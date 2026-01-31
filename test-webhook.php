<!-- <?php
// test-webhook.php
require __DIR__ . '/vendor/autoload.php';

use Stripe\Stripe;
use Stripe\Webhook;

// Tu clave secreta de Stripe
Stripe::setApiKey('sk_test_xxxxxxxxxxxxxx');

// Datos de ejemplo para simular un webhook
$payload = [
    'id' => 'evt_test_' . uniqid(),
    'object' => 'event',
    'api_version' => '2023-10-16',
    'created' => time(),
    'data' => [
        'object' => [
            'id' => 'sub_test_' . uniqid(),
            'object' => 'subscription',
            'status' => 'active',
            'customer' => 'cus_test_' . uniqid(),
            'current_period_end' => time() + (30 * 24 * 60 * 60),
        ]
    ],
    'type' => 'customer.subscription.created'
];

// Convertir a JSON
$payloadJson = json_encode($payload);

// Crear firma (necesitarás tu webhook secret)
$secret = 'whsec_xxxxxxxxxxxxxx'; // Tu webhook secret
$timestamp = time();
$signedPayload = $timestamp . '.' . $payloadJson;
$signature = hash_hmac('sha256', $signedPayload, $secret);

// Configurar las cabeceras
$headers = [
    'Stripe-Signature: t=' . $timestamp . ',v1=' . $signature
];

// Configurar opciones de cURL
$ch = curl_init('http://localhost:8000/stripe/webhook'); // Cambia el puerto si usas otro
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, $payloadJson);
curl_setopt($ch, CURLOPT_HTTPHEADER, array_merge(['Content-Type: application/json'], $headers));

// Ejecutar
$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

echo "HTTP Code: $httpCode\n";
echo "Response: $response\n";

curl_close($ch); -->