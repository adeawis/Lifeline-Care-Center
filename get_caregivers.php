<?php
header('Content-Type: application/json');
include 'db.php';

try {
    // Get search parameters
    $searchTerm = isset($_GET['search']) ? '%' . $_GET['search'] . '%' : '%';
    $location = isset($_GET['location']) ? $_GET['location'] : '';

    // Query to get caregivers with their locations
    $query = "SELECT 
                full_name,
                position,
                location,
                experience,
                availability,
                ( 6371 * acos( cos( radians(?) ) * 
                    cos( radians( latitude ) ) * 
                    cos( radians( longitude ) - radians(?) ) + 
                    sin( radians(?) ) * 
                    sin( radians( latitude ) ) 
                ) ) AS distance 
            FROM staffreg 
            WHERE (position = 'nurses' OR position = 'caregivers' OR position = 'daily')
            AND (full_name LIKE ? OR position LIKE ?)
            HAVING distance < 50 
            ORDER BY distance";

    // Get coordinates for the entered location using external API
    $locationData = getCoordinates($location);
    $lat = $locationData['lat'];
    $lng = $locationData['lng'];

    $stmt = $conn->prepare($query);
    $stmt->bind_param("dddss", $lat, $lng, $lat, $searchTerm, $searchTerm);
    $stmt->execute();
    $result = $stmt->get_result();

    $caregivers = [];
    while ($row = $result->fetch_assoc()) {
        $caregivers[] = $row;
    }

    echo json_encode([
        'status' => 'success',
        'data' => $caregivers
    ]);

} catch (Exception $e) {
    echo json_encode([
        'status' => 'error',
        'message' => $e->getMessage()
    ]);
}

// Function to get coordinates from location string using external API
function getCoordinates($location) {
    // Replace with your preferred geocoding service API key
    $apiKey = 'YOUR_API_KEY';
    $url = "https://maps.googleapis.com/maps/api/geocode/json?address=" . urlencode($location) . "&key=" . $apiKey;
    
    $response = file_get_contents($url);
    $data = json_decode($response, true);
    
    if ($data['status'] === 'OK') {
        return [
            'lat' => $data['results'][0]['geometry']['location']['lat'],
            'lng' => $data['results'][0]['geometry']['location']['lng']
        ];
    }
    
    throw new Exception('Unable to geocode location');
}
?>