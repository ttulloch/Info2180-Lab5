<?php
// Set the content type to HTML with UTF-8 encoding
header("Content-Type: text/html; charset=UTF-8");

$host = 'localhost';
$username = 'lab5_user';
$password = 'password123';
$dbname = 'world';

try {
    // Create a new PDO instance with error mode set to Exception
    $conn = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Check if 'country' parameter is set and not empty
    if (isset($_GET['country']) && $_GET['country'] != '') {
        // Retrieve and sanitize the 'country' parameter
        $country = $_GET['country'];

        // Prepare the SQL statement with a placeholder
        $stmt = $conn->prepare("SELECT * FROM countries WHERE name LIKE :country");
        $stmt->bindValue(':country', '%' . $country . '%', PDO::PARAM_STR);

        // Execute the prepared statement
        $stmt->execute();

        // Fetch all matching records
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if (count($results) > 0) {
            // Output results as HTML list items
            foreach ($results as $row) {
                echo '<li>' . htmlspecialchars($row['name']) . ' is ruled by ' . htmlspecialchars($row['head_of_state']) . '</li>';
            }
        } else {
            // No results found
            echo '<li>No results found for "' . htmlspecialchars($country) . '".</li>';
        }
    } else {
        // 'country' parameter is not provided or empty
        echo '<li>Please enter a country name.</li>';
    }
} catch (PDOException $e) {
    // Handle any database connection or query errors
    echo '<li>Error: ' . htmlspecialchars($e->getMessage()) . '</li>';
    exit();
}
?>
