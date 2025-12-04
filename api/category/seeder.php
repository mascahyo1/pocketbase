<?php
// seeder 100 kategori 1 - 100 looping saja
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
$token = login()['token'];

function create($name, $description) {
  $curl = curl_init();

  curl_setopt_array($curl, array(
    CURLOPT_URL => 'http://127.0.0.1:8090/api/collections/post_categories/records',
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => '',
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 0,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => 'POST',
    CURLOPT_POSTFIELDS => http_build_query(array('name' => $name, 'description' => $description)),
    CURLOPT_HTTPHEADER => array(
      'Authorization: Bearer ' . $GLOBALS['token']
    ),
  ));

  $response = curl_exec($curl);

  curl_close($curl);
  return json_decode($response, true);
}

for ($i = 1; $i <= 100; $i++) {
  $name = "Category " . $i;
  $description = "Description for Category " . $i;
  $result = create($name, $description);
  echo "Created: " . $result['id'] . " - " . $result['name'] . "\n";
}