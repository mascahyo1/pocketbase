<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: PUT, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

function login() {
  $curl = curl_init();
  
  curl_setopt_array($curl, array(
    CURLOPT_URL => 'http://127.0.0.1:8090/api/collections/_superusers/auth-with-password',
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => '',
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 0,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => 'POST',
    CURLOPT_POSTFIELDS => array('identity' => 'mascahyo15@gmail.com','password' => 'Password123'),
  ));
  
  $response = curl_exec($curl);
  
  curl_close($curl);
  return json_decode($response, true);
}

function update() {
  $token = login()['token'];
  
  $id = $_POST['id'] ?? '';
  
  if (empty($id)) {
    http_response_code(400);
    return ['success' => false, 'message' => 'ID is required'];
  }
  
  $curl = curl_init();

  curl_setopt_array($curl, array(
    CURLOPT_URL => 'http://127.0.0.1:8090/api/collections/post_categories/records/' . $id,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => '',
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 0,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => 'PATCH',
    CURLOPT_POSTFIELDS => http_build_query($_POST),
    CURLOPT_HTTPHEADER => array(
      'Authorization: Bearer ' . $token
    ),
  ));

  $response = curl_exec($curl);

  curl_close($curl);
  return json_decode($response, true);
}

try {
  $result = update();
  echo json_encode([
    'success' => true,
    'message' => 'Kategori berhasil diupdate',
    'data' => $result
  ]);
  
} catch (Exception $e) {
  http_response_code(500);
  echo json_encode([
    'success' => false,
    'message' => $e->getMessage()
  ]);
}
