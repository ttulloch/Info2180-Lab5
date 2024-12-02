<?php
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
        $stmt = $conn->prepare("SELECT name, continent, independence_year, head_of_state FROM countries WHERE name LIKE :country");
        $stmt->bindValue(':country', '%' . $country . '%', PDO::PARAM_STR);

        // Execute the prepared statement
        $stmt->execute();

        // Fetch all matching records
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if (count($results) > 0) {
            // Output results as an HTML table
            echo '<table border="1">';
            echo '<thead>';
            echo '<tr>';
            echo '<th>Name</th>';
            echo '<th>Continent</th>';
            echo '<th>Independence Year</th>';
            echo '<th>Head of State</th>';
            echo '</tr>';
            echo '</thead>';
            echo '<tbody>';

            foreach ($results as $row) {
                echo '<tr>';
                echo '<td>' . htmlspecialchars($row['name']) . '</td>';
                echo '<td>' . htmlspecialchars($row['continent']) . '</td>';
                echo '<td>' . htmlspecialchars($row['independence_year'] ?? 'N/A') . '</td>';
                echo '<td>' . htmlspecialchars($row['head_of_state'] ?? 'N/A') . '</td>';
                echo '</tr>';
            }

            echo '</tbody>';
            echo '</table>';
        } else {
            // No results found
            echo '<p>No results found for "' . htmlspecialchars($country) . '".</p>';
        }
    } else {
        // 'country' parameter is not provided or empty
        echo '<p>Please enter a country name.</p>';
    }
} catch (PDOException $e) {
    // Handle any database connection or query errors
    echo '<p>Error: ' . htmlspecialchars($e->getMessage()) . '</p>';
    exit();
}
?>
