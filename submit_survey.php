<?php
// Include the database connection
include 'db_connection.php';

// __define-ocg__ Sanitize input
$full_name = trim($conn->real_escape_string($_POST['full_name'] ?? ''));
$email = trim($conn->real_escape_string($_POST['email'] ?? ''));
$dob = trim($conn->real_escape_string($_POST['dob'] ?? ''));
$phone = trim($conn->real_escape_string($_POST['phone'] ?? ''));

// Combine selected food choices (assume food is a checkbox array)
$selected_food = isset($_POST['food']) && is_array($_POST['food']) ? implode(", ", $_POST['food']) : '';

// Ratings
$rate_movies = $_POST['movies'] ?? null;
$rate_radio = $_POST['radio'] ?? null;
$rate_eatout = $_POST['eatout'] ?? null;
$rate_tv = $_POST['tv'] ?? null;

// Validation errors array
$errors = [];

// 1. Check required text fields are not empty
if (empty($full_name)) $errors[] = "Full Name is required.";
if (empty($email)) $errors[] = "Email is required.";
if (empty($dob)) $errors[] = "Date of Birth is required.";
if (empty($phone)) $errors[] = "Phone number is required.";

// 2. Validate date of birth and age between 5 and 120
$dobDate = DateTime::createFromFormat('Y-m-d', $dob);
$now = new DateTime();
if (!$dobDate) {
    $errors[] = "Invalid date format for Date of Birth.";
} else {
    $age = $now->diff($dobDate)->y;
    if ($age < 5 || $age > 120) {
        $errors[] = "Age must be between 5 and 120 years.";
    }
}

// 3. Validate all ratings are selected and valid (1 to 5)
$rating_fields = ['movies' => $rate_movies, 'radio' => $rate_radio, 'eatout' => $rate_eatout, 'tv' => $rate_tv];
foreach ($rating_fields as $key => $val) {
    if ($val === null || !in_array($val, ['1', '2', '3', '4', '5'])) {
        $errors[] = "Please select a valid rating for " . ucfirst($key) . ".";
    }
}

// 4. Validate at least one food choice selected (mandatory)
if (empty($selected_food)) {
    $errors[] = "Please select at least one favourite food.";
}

// If there are errors, show them and exit
if (count($errors) > 0) {
    echo "<h3>There were errors with your submission:</h3><ul>";
    foreach ($errors as $error) {
        echo "<li style='color:red;'>$error</li>";
    }
    echo "</ul><a href='javascript:history.back()'>Go Back</a>";
    exit;
}

// If validation passed, insert into database safely
$sql = "INSERT INTO survey_responses (full_name, email, dob, phone, food, rate_movies, rate_radio, rate_eatout, rate_tv)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";

$stmt = $conn->prepare($sql);
$stmt->bind_param("ssssssiii", $full_name, $email, $dob, $phone, $selected_food, $rate_movies, $rate_radio, $rate_eatout, $rate_tv);

if ($stmt->execute()) {
    echo "<h2>Thank you! Your response has been recorded.</h2>";
    echo "<script>
        setTimeout(function() {
            window.location.href = 'index.html';
        }, 3000); // Redirect after 3 seconds
    </script>";
} else {
    echo "Error: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>
