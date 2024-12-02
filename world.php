<?php
header("Content-Type: text/html; charset=UTF-8");

$host = 'localhost';
$username = 'lab5_user';
$password = 'password123';
$dbname = 'world';

try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    if (isset($_GET['country']) && $_GET['country'] != '') {
        $search = $_GET['country'];
        $lookup = isset($_GET['lookup']) ? $_GET['lookup'] : 'country';

        if ($lookup === 'country') {
            $stmt = $conn->prepare("
                SELECT DISTINCT countries.name, countries.continent, countries.independence_year, countries.head_of_state 
                FROM countries 
                LEFT JOIN cities ON countries.code = cities.country_code 
                WHERE countries.name LIKE :search OR cities.name LIKE :search
            ");
            $stmt->bindValue(':search', '%' . $search . '%', PDO::PARAM_STR);
            $stmt->execute();

            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

            if (count($results) > 0) {
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
                echo '<p>No results found for "' . htmlspecialchars($search) . '".</p>';
            }

        } elseif ($lookup === 'cities') {
            $stmt = $conn->prepare("
                SELECT cities.name, cities.district, cities.population 
                FROM cities 
                JOIN countries ON cities.country_code = countries.code 
                WHERE countries.name LIKE :search OR cities.name LIKE :search
            ");
            $stmt->bindValue(':search', '%' . $search . '%', PDO::PARAM_STR);
            $stmt->execute();

            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

            if (count($results) > 0) {
                echo '<table border="1">';
                echo '<thead>';
                echo '<tr>';
                echo '<th>Name</th>';
                echo '<th>District</th>';
                echo '<th>Population</th>';
                echo '</tr>';
                echo '</thead>';
                echo '<tbody>';

                foreach ($results as $row) {
                    echo '<tr>';
                    echo '<td>' . htmlspecialchars($row['name']) . '</td>';
                    echo '<td>' . htmlspecialchars($row['district']) . '</td>';
                    echo '<td>' . htmlspecialchars($row['population']) . '</td>';
                    echo '</tr>';
                }

                echo '</tbody>';
                echo '</table>';
            } else {
                echo '<p>No cities found for "' . htmlspecialchars($search) . '".</p>';
            }
        }

    } else {
        echo '<p>Please enter a country or city name.</p>';
    }
} catch (PDOException $e) {
    echo '<p>Error: ' . htmlspecialchars($e->getMessage()) . '</p>';
    exit();
}
?>
