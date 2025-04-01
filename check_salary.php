<?php
header('Content-Type: application/json');

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Database configuration
$host = 'localhost';
$dbname = 'lifeline_carecenter';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Handle GET request for caretaker list
    if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['action']) && $_GET['action'] === 'get_caretakers') {
        $stmt = $pdo->query("SELECT full_name, position FROM staffreg ORDER BY full_name");
        echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
        exit;
    }

    // Handle POST request for salary check
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (!isset($_POST['caretakerName']) || !isset($_POST['month']) || !isset($_POST['year'])) {
            throw new Exception('Missing required fields');
        }

        $caretakerName = $_POST['caretakerName'];
        $month = $_POST['month'];
        $year = $_POST['year'];

        // Get caretaker details
        $stmt = $pdo->prepare("
            SELECT * FROM staffreg 
            WHERE full_name = :name
        ");
        $stmt->execute([':name' => $caretakerName]);
        $caretaker = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$caretaker) {
            throw new Exception('Caretaker not found');
        }

        // Get assignments for the specified month
        $stmt = $pdo->prepare("
        SELECT COUNT(*) as active_assignments
        FROM schedule_admin 
        WHERE staff_name = :name
        AND status = 'scheduled'
        AND (
            (start_date <= LAST_DAY(STR_TO_DATE(CONCAT(:year, '-', :month, '-01'), '%Y-%m-%d')) 
        AND 
        end_date >= STR_TO_DATE(CONCAT(:year, '-', :month, '-01'), '%Y-%m-%d'))
         )
        ");
        $stmt->execute([
        ':name' => $caretakerName,
        ':month' => $month,
        ':year' => $year
        ]);
        $assignments = $stmt->fetch(PDO::FETCH_ASSOC);

        // Calculate days worked based on schedule_admin entries
        $stmt = $pdo->prepare("
        SELECT start_date, end_date 
        FROM schedule_admin 
        WHERE staff_name = :name
        AND status = 'scheduled'
        AND (
        (start_date <= LAST_DAY(STR_TO_DATE(CONCAT(:year, '-', :month, '-01'), '%Y-%m-%d')) 
        AND 
        end_date >= STR_TO_DATE(CONCAT(:year, '-', :month, '-01'), '%Y-%m-%d'))
        )
        ");
        $stmt->execute([
        ':name' => $caretakerName,
        ':month' => $month,
        ':year' => $year
        ]);
        $schedules = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Calculate actual days worked in the month
$daysWorked = 0;
$firstDayOfMonth = new DateTime("$year-$month-01");
$lastDayOfMonth = new DateTime($firstDayOfMonth->format('Y-m-t'));

foreach ($schedules as $schedule) {
$startDate = new DateTime($schedule['start_date']);
$endDate = new DateTime($schedule['end_date']);

// Adjust dates to be within the month
if ($startDate < $firstDayOfMonth) {
    $startDate = clone $firstDayOfMonth;
}
if ($endDate > $lastDayOfMonth) {
    $endDate = clone $lastDayOfMonth;
}

// Calculate days in this assignment (inclusive of start and end dates)
$interval = $startDate->diff($endDate);
$daysInThisAssignment = $interval->days + 1; // +1 to include both start and end dates

$daysWorked += $daysInThisAssignment;
}

// Ensure we don't count days multiple times (in case of overlapping assignments)
$daysWorked = min($daysWorked, cal_days_in_month(CAL_GREGORIAN, $month, $year));

        // Calculate base rate based on position and experience
        $baseRate = calculateBaseRate($caretaker['position'], $caretaker['experience']);
        
        // Calculate days worked (simplified calculation)
        $daysInMonth = cal_days_in_month(CAL_GREGORIAN, $month, $year);
        // Calculate actual days worked in the month

        // Calculate total earnings
        $totalEarnings = $baseRate * $daysWorked;

        // Format month name
        $monthName = date('F', mktime(0, 0, 0, $month, 1));

        // Prepare response
        $response = [
            'success' => true,
            'name' => $caretaker['full_name'],
            'position' => $caretaker['position'],
            'experience' => $caretaker['experience'],
            'month' => $monthName,
            'year' => $year,
            'active_assignments' => $assignments['active_assignments'] ?? 0,
            'days_worked' => $daysWorked,
            'base_rate' => number_format($baseRate, 2),
            'total_earnings' => number_format($totalEarnings, 2)
        ];
    }

} catch (Exception $e) {
    $response = [
        'success' => false,
        'message' => $e->getMessage()
    ];
}

// Function to calculate base rate
function calculateBaseRate($position, $experience) {
    // Base rates per position (daily rate)
    $baseRates = [
        'nurses' => 2500,
        'caregivers' => 2000,
        'daily' => 1500
    ];

    // Experience multiplier (5% increase per year of experience)
    $experienceYears = intval($experience);
    $experienceMultiplier = 1 + ($experienceYears * 0.05);

    // Get base rate for position
    $baseRate = $baseRates[strtolower($position)] ?? 1500;

    // Calculate final rate
    return $baseRate * $experienceMultiplier;
}

// Send response
echo json_encode($response);
?>