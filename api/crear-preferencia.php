<?php
header("Content-Type: application/json");

function fujeira_read_env($name) {
  $candidates = [];

  $fromGetenv = getenv($name);
  if ($fromGetenv !== false) {
    $candidates[] = $fromGetenv;
  }
  if (isset($_ENV[$name])) {
    $candidates[] = $_ENV[$name];
  }
  if (isset($_SERVER[$name])) {
    $candidates[] = $_SERVER[$name];
  }

  foreach ($candidates as $value) {
    if (is_string($value) && trim($value) !== "") {
      return trim($value);
    }
  }

  return "";
}

function fujeira_load_dotenv($path) {
  if (!is_readable($path)) {
    return;
  }
  $size = filesize($path);
  if ($size === false || $size > 65536) {
    return;
  }

  $lines = file($path, FILE_IGNORE_NEW_LINES);
  if ($lines === false) {
    return;
  }

  foreach ($lines as $line) {
    $line = trim($line);
    if ($line === "" || $line[0] === "#") {
      continue;
    }
    if (strncmp($line, "export ", 7) === 0) {
      $line = trim(substr($line, 7));
    }

    $eq = strpos($line, "=");
    if ($eq === false) {
      continue;
    }

    $name = trim(substr($line, 0, $eq));
    $value = trim(substr($line, $eq + 1));
    if ($name === "" || !preg_match('/^[A-Za-z_][A-Za-z0-9_]*$/', $name)) {
      continue;
    }
    if (strpos($value, "\0") !== false) {
      continue;
    }

    $len = strlen($value);
    if ($len >= 2) {
      $quote = $value[0];
      if (($quote === '"' || $quote === "'") && $value[$len - 1] === $quote) {
        $value = substr($value, 1, -1);
      }
    }

    if (fujeira_read_env($name) !== "") {
      continue;
    }

    if (function_exists("putenv")) {
      putenv($name . "=" . $value);
    }
    $_ENV[$name] = $value;
    $_SERVER[$name] = $value;
  }
}

function fujeira_mp_token_missing($token) {
  $token = trim((string) $token);
  if ($token === "") {
    return true;
  }
  if (stripos($token, "XXXX") !== false) {
    return true;
  }

  $placeholders = [
    "your_mercado_pago_access_token",
    "tu_access_token",
    "changeme",
  ];

  return in_array(strtolower($token), $placeholders, true);
}

$access_token = fujeira_read_env("MP_ACCESS_TOKEN");

if ($access_token === "") {
  $envCandidates = [
    dirname(__DIR__, 2) . "/.env",
    dirname(__DIR__) . "/.env",
    __DIR__ . "/.env",
  ];
  foreach ($envCandidates as $envPath) {
    fujeira_load_dotenv($envPath);
  }
  $access_token = fujeira_read_env("MP_ACCESS_TOKEN");
}

if (fujeira_mp_token_missing($access_token)) {
  http_response_code(503);
  echo json_encode([
    "error" => "Mercado Pago no configurado",
    "hint" => "Definí MP_ACCESS_TOKEN en el entorno del servidor o en un archivo .env no versionado. Ver README.",
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
