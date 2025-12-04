<?php
// seeder 100 posts dengan random category, status, dan date range
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

// Get all categories first
function getCategories() {
  $curl = curl_init();
  
  curl_setopt_array($curl, array(
    CURLOPT_URL => 'http://127.0.0.1:8090/api/collections/post_categories/records?perPage=100',
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => '',
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 0,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => 'GET',
    CURLOPT_HTTPHEADER => array(
      'Authorization: Bearer ' . $GLOBALS['token']
    ),
  ));
  
  $response = curl_exec($curl);
  curl_close($curl);
  return json_decode($response, true);
}

$categories = getCategories();
if (empty($categories['items'])) {
  die("Error: No categories found. Please create categories first!\n");
}

$categoryIds = array_column($categories['items'], 'id');

function create($title, $category_id, $start_time, $end_time, $status, $description) {
  $curl = curl_init();

  curl_setopt_array($curl, array(
    CURLOPT_URL => 'http://127.0.0.1:8090/api/collections/posts/records',
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => '',
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 0,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => 'POST',
    CURLOPT_POSTFIELDS => http_build_query(array(
      'title' => $title,
      'category_id' => $category_id,
      'start_time' => $start_time,
      'end_time' => $end_time,
      'status' => $status,
      'description' => $description
    )),
    CURLOPT_HTTPHEADER => array(
      'Authorization: Bearer ' . $GLOBALS['token']
    ),
  ));

  $response = curl_exec($curl);

  curl_close($curl);
  return json_decode($response, true);
}

$statuses = ['draft', 'publish'];

for ($i = 1; $i <= 100; $i++) {
  $title = "Post Title " . $i;
  $category_id = $categoryIds[array_rand($categoryIds)];
  $status = $statuses[array_rand($statuses)];
  
  // Random datetime dalam 2024-2025
  $startYear = rand(2024, 2025);
  $startMonth = rand(1, 12);
  $startDay = rand(1, 28);
  $startHour = rand(0, 23);
  $startMinute = rand(0, 59);
  $start_time = sprintf("%04d-%02d-%02d %02d:%02d:00", $startYear, $startMonth, $startDay, $startHour, $startMinute);
  
  // End time 1-8 jam setelah start time
  $endTimestamp = strtotime($start_time) + (rand(1, 8) * 60 * 60);
  $end_time = date("Y-m-d H:i:s", $endTimestamp);
  
  $description = "This is a description for Post " . $i . ". Lorem ipsum dolor sit amet, consectetur adipiscing elit.";
  
  $result = create($title, $category_id, $start_time, $end_time, $status, $description);
  
  if (isset($result['id'])) {
    echo "Created: " . $result['id'] . " - " . $result['title'] . " (" . $status . ")\n";
  } else {
    echo "Error creating post " . $i . ": " . json_encode($result) . "\n";
  }
}

echo "\nSeeding completed!\n";
