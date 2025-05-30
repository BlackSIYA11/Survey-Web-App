<?php
// Enable error reporting
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Start output buffering
ob_start();

// Database configuration
$host = "localhost";
$user = "root";
$password = "";
$database = "survey_responses";

// Create connection with error handling
try {
    $conn = new mysqli($host, $user, $password, $database);
    
    if ($conn->connect_error) {
        throw new Exception("Connection failed: " . $conn->connect_error);
    }
    
    // Set charset to ensure proper encoding
    $conn->set_charset("utf8mb4");

    // Function to safely execute queries
    function executeQuery($conn, $query, $default = 'N/A') {
        $result = $conn->query($query);
        if (!$result) {
            error_log("Query failed: " . $conn->error);
            return $default;
        }
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            return reset($row); // Get first column value
        }
        return $default;
    }

    
    // Get all statistics
    $stats = [
        'total' => executeQuery($conn, "SELECT COUNT(*) AS total FROM survey_responses", 0),
        'average_age' => executeQuery($conn, "SELECT ROUND(AVG(TIMESTAMPDIFF(YEAR, dob, CURDATE())), 1) AS average_age FROM survey_responses"),
        'oldest' => executeQuery($conn, "SELECT MAX(TIMESTAMPDIFF(YEAR, dob, CURDATE())) AS oldest FROM survey_responses"),
        'youngest' => executeQuery($conn, "SELECT MIN(TIMESTAMPDIFF(YEAR, dob, CURDATE())) AS youngest FROM survey_responses"),
        'pizza_lovers' => executeQuery($conn, "SELECT COUNT(*) AS pizza_lovers FROM survey_responses WHERE food LIKE '%Pizza%'", 0),
        'pasta_lovers' => executeQuery($conn, "SELECT COUNT(*) AS pasta_lovers FROM survey_responses WHERE food LIKE '%Pasta%'", 0),
        'pap_wors_lovers' => executeQuery($conn, "SELECT COUNT(*) AS pap_wors_lovers FROM survey_responses WHERE food LIKE '%Pap and Wors%'", 0),
        'avg_movies' => executeQuery($conn, "SELECT ROUND(AVG(rate_movies), 1) AS avg_movies FROM survey_responses"),
        'avg_radio' => executeQuery($conn, "SELECT ROUND(AVG(rate_radio), 1) AS avg_radio FROM survey_responses"),
        'avg_eatout' => executeQuery($conn, "SELECT ROUND(AVG(rate_eatout), 1) AS avg_eatout FROM survey_responses"),
        'avg_tv' => executeQuery($conn, "SELECT ROUND(AVG(rate_tv), 1) AS avg_tv FROM survey_responses")
    ];
    

    // Calculate percentages
    $stats['pizza_percentage'] = $stats['total'] > 0 ? round(($stats['pizza_lovers'] / $stats['total']) * 100, 1) : 0;
    $stats['pasta_percentage'] = $stats['total'] > 0 ? round(($stats['pasta_lovers'] / $stats['total']) * 100, 1) : 0;
    $stats['pap_wors_percentage'] = $stats['total'] > 0 ? round(($stats['pap_wors_lovers'] / $stats['total']) * 100, 1) : 0;

    // Close connection
    $conn->close();

} catch (Exception $e) {
    // Log error and display user-friendly message
    error_log($e->getMessage());
    $error = "We're experiencing technical difficulties. Please try again later.";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Survey Results</title>
    <link rel="stylesheet" href="styles.css">
    <script src="scripts.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <nav>
        <ul>
            <li><a href="index.html" class="nav-link"><i class="fas fa-home"></i> Home</a></li>
            <li><a href="results.php" class="nav-link active"><i class="fas fa-chart-bar"></i> Results</a></li>
        </ul>
    </nav>

    <main>
        <h1>Survey Results</h1>
        
        <?php if (isset($error)): ?>
            <div class="error"><?= htmlspecialchars($error) ?></div>
        <?php else: ?>
            <ul>
                <li><strong>Total Surveys Completed:</strong> <?= htmlspecialchars($stats['total']) ?></li>
                <li><strong>Average Age:</strong> <?= htmlspecialchars($stats['average_age']) ?></li>
                <li><strong>Oldest Person:</strong> <?= htmlspecialchars($stats['oldest']) ?> years old</li>
                <li><strong>Youngest Person:</strong> <?= htmlspecialchars($stats['youngest']) ?> years old</li>

                <br><br>

                <li><strong>Percentage of People Who Like Pizza:</strong> <?= htmlspecialchars($stats['pizza_percentage']) ?>%</li>
                <li><strong>Percentage of People Who Like Pasta:</strong> <?= htmlspecialchars($stats['pasta_percentage']) ?>%</li>
                <li><strong>Percentage of People Who Like Pap and Wors:</strong> <?= htmlspecialchars($stats['pap_wors_percentage']) ?>%</li>

                <br><br>

                <li><strong>Average rating for watching movies:</strong> <?= htmlspecialchars($stats['avg_movies']) ?></li>
                <li><strong>Average rating for listening to radio:</strong> <?= htmlspecialchars($stats['avg_radio']) ?></li>
                <li><strong>Average rating for eating out:</strong> <?= htmlspecialchars($stats['avg_eatout']) ?></li>
                <li><strong>Average rating for watching TV:</strong> <?= htmlspecialchars($stats['avg_tv']) ?></li>
            </ul>
            
           
        <?php endif; ?>
    </main>
</body>
</html>
<?php
// Flush output buffer
ob_end_flush();
?>