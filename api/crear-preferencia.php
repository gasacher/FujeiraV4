<?php
header("Content-Type: application/json");

$access_token = "";
$mp_token_en_este_archivo = "APP_USR-8387705200207844-122521-3df9677dd8a8dc1c5baf15da9466750d-3050315862"; 

if (is_readable(__DIR__ . "/config-mp.local.php")) {
  require __DIR__ . "/config-mp.local.php";
}

if (trim((string) $access_token) === "") {
  $access_token = getenv("MP_ACCESS_TOKEN") ?: "";
}
if (trim((string) $access_token) === "" && $mp_token_en_este_archivo !== "") {
  $access_token = $mp_token_en_este_archivo;
}

if ($access_token === "" || strpos($access_token, "XXXX") !== false) {
  http_response_code(503);
  echo json_encode([
    "error" => "Mercado Pago no configurado",
    "hint" => "Pegá el Access Token en mp_token_en_este_archivo o en config-mp.local.php.",
  ]);
  exit;
}

// Leer JSON enviado desde el frontend
$raw = file_get_contents("php://input");
$data = json_decode($raw, true);

// Validación
if (!$data || empty($data["items"])) {
  http_response_code(400);
  echo json_encode([
    "error" => "Datos inválidos",
    "recibido" => $data
  ]);
  exit;
}

// Armar items para Mercado Pago
$items = [];

foreach ($data["items"] as $item) {

  if (!empty($item["marco"])) {
    $variante = "Marco: " . $item["marco"];
  } elseif (!empty($item["talle"])) {
    $variante = "Talle: " . $item["talle"];
  } else {
    $variante = "";
  }

  $items[] = [
    "title" => trim($item["nombre"] . " - " . $variante),
    "quantity" => (int) $item["cantidad"],
    "unit_price" => (float) $item["precio"],
    "currency_id" => "ARS"
  ];
}

// Crear preferencia
$preference = [
  "items" => $items,
  "back_urls" => [
    "success" => "https://fujeiraretrostore.com/pago-exitoso.html",
    "failure" => "https://fujeiraretrostore.com/pago-fallido.html",
    "pending" => "https://fujeiraretrostore.com/pago-pendiente.html"
  ],
  "auto_return" => "approved"
];

// Llamada a Mercado Pago
$ch = curl_init("https://api.mercadopago.com/checkout/preferences");
curl_setopt_array($ch, [
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_POST => true,
  CURLOPT_HTTPHEADER => [
    "Authorization: Bearer $access_token",
    "Content-Type: application/json"
  ],
  CURLOPT_POSTFIELDS => json_encode($preference)
]);

$response = curl_exec($ch);
$curl_err = curl_error($ch);
$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

if ($response === false) {
  http_response_code(500);
  echo json_encode([
    "error" => "Error de conexión (cURL)",
    "message" => $curl_err ?: "curl_exec falló"
  ]);
  exit;
}

// Error MP
if ($http_code !== 201) {
  http_response_code(500);
  echo json_encode([
    "error" => "Error Mercado Pago",
    "response" => json_decode($response, true)
  ]);
  exit;
}

// OK
echo $response;
