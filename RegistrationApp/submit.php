<?php

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    exit("Please submit the registration form first.");
}

function clean($value) {
    return trim((string)$value);
}

$name = clean($_POST["name"] ?? "");
$dob = clean($_POST["dob"] ?? "");
$gender = clean($_POST["gender"] ?? "");
$email = clean($_POST["email"] ?? "");
$phone = clean($_POST["phone"] ?? "");
$address = clean($_POST["address"] ?? "");
$city = clean($_POST["city"] ?? "");
$pincode = clean($_POST["pincode"] ?? "");
$course = clean($_POST["course"] ?? "");
$year = clean($_POST["year"] ?? "");
$qualification = clean($_POST["qualification"] ?? "");
$hobbies = clean($_POST["hobbies"] ?? "");
$declaration = $_POST["declaration"] ?? "";

$errors = [];

if ($name === "" || strlen($name) > 100) {
    $errors[] = "Please enter a valid name.";
}

if (
    !filter_var($email, FILTER_VALIDATE_EMAIL) ||
    strlen($email) > 150
) {
    $errors[] = "Please enter a valid email address.";
}

if (!preg_match('/^[0-9]{10}$/', $phone)) {
    $errors[] = "Phone number must contain 10 digits.";
}

if (!preg_match('/^[0-9]{6}$/', $pincode)) {
    $errors[] = "PIN code must contain 6 digits.";
}

if (
    $dob === "" ||
    !DateTime::createFromFormat('!Y-m-d', $dob) ||
    DateTime::createFromFormat('!Y-m-d', $dob)->format('Y-m-d') !== $dob ||
    $dob > date('Y-m-d')
) {
    $errors[] = "Please enter a valid date of birth.";
}

if (
    $address === "" ||
    strlen($address) > 500 ||
    $city === "" ||
    strlen($city) > 100 ||
    $hobbies === "" && isset($_POST["hobbies"]) &&
    strlen($hobbies) > 500
) {
    $errors[] = "Please check your address, city, or hobbies.";
}

if (!in_array($gender, [
    "Female", "Male", "Other", "Prefer not to say"
], true)) {
    $errors[] = "Please select a valid gender.";
}

if (!in_array($course, [
    "CSE", "ISE", "ECE", "EEE", "ME", "Other"
], true)) {
    $errors[] = "Please select a valid course.";
}

if (!in_array($year, [
    "1st Year", "2nd Year", "3rd Year", "4th Year"
], true)) {
    $errors[] = "Please select a valid year of study.";
}

if (!in_array($qualification, [
    "SSLC", "PUC", "Diploma", "Other"
], true)) {
    $errors[] = "Please select a valid qualification.";
}

if ($declaration !== "yes") {
    $errors[] = "Please accept the declaration.";
}

if ($errors) {
    http_response_code(400);
    echo "<h2>Please correct the following errors:</h2><ul>";

    foreach ($errors as $error) {
        echo "<li>" .
            htmlspecialchars($error, ENT_QUOTES, 'UTF-8') .
            "</li>";
    }

    echo "</ul><p><a href='index.html'>Back to Form</a></p>";
    exit;
}

function h($value) {
    return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Registration Successful</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<div class="container">

    <h1>Registration Successful!</h1>

    <p>Your submitted information is displayed below.</p>

    <h2>Personal Details</h2>

    <p><strong>Full Name:</strong> <?= h($name) ?></p>
    <p><strong>Date of Birth:</strong> <?= h($dob) ?></p>
    <p><strong>Gender:</strong> <?= h($gender) ?></p>

    <h2>Contact Details</h2>

    <p><strong>Email:</strong> <?= h($email) ?></p>
    <p><strong>Phone:</strong> <?= h($phone) ?></p>
    <p><strong>Address:</strong> <?= nl2br(h($address)) ?></p>
    <p><strong>City / District:</strong> <?= h($city) ?></p>
    <p><strong>PIN Code:</strong> <?= h($pincode) ?></p>

    <h2>Educational Details</h2>

    <p><strong>Course:</strong> <?= h($course) ?></p>
    <p><strong>Year of Study:</strong> <?= h($year) ?></p>
    <p><strong>Previous Qualification:</strong>
        <?= h($qualification) ?></p>
    <p><strong>Hobbies / Interests:</strong>
        <?= h($hobbies) ?></p>

    <p><strong>Declaration:</strong> Accepted</p>

    <a href="index.html" class="button">Back to Registration</a>

</div>

</body>
</html>
