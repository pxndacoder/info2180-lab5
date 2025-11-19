<?php
$host = 'localhost';
$username = 'lab5_user';
$password = 'password123';
$dbname = 'world';

$dsn = "mysql:host=$host;port=3306;dbname=$dbname;charset=utf8mb4";

try {
    $conn = new PDO($dsn, $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $country = $_GET['country'] ?? '';
    $lookup = $_GET['lookup'] ?? 'country';

    if ($lookup === 'cities') {
        // display city info for given country
        if ($country !== '') {
            $stmt = $conn->prepare(
                "SELECT cities.name, cities.district, cities.population
                 FROM cities
                 JOIN countries ON cities.country_code = countries.code
                 WHERE countries.name LIKE :country"
            );
            $stmt->execute(['country' => "%$country%"]);
        } else {
            $stmt = $conn->query(
                "SELECT cities.name, cities.district, cities.population
                 FROM cities"
            );
        }

        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        if (!empty($results)) {
            echo "<table>
                  <tr>
                    <th>City Name</th>
                    <th>District</th>
                    <th>Population</th>
                  </tr>";
            foreach ($results as $row) {
                echo "<tr>
                        <td>" . htmlspecialchars($row['name']) . "</td>
                        <td>" . htmlspecialchars($row['district']) . "</td>
                        <td>" . htmlspecialchars($row['population']) . "</td>
                      </tr>";
            }
            echo "</table>";
        } else {
            echo "<p>No cities found for that country.</p>";
        }

    } else {
        // normal country lookup
        if ($country !== '') {
            $stmt = $conn->prepare("SELECT * FROM countries WHERE name LIKE :country");
            $stmt->execute(['country' => "%$country%"]);
        } else {
            $stmt = $conn->query("SELECT * FROM countries");
        }

        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if (!empty($results)) {
            echo "<table>
                    <tr>
                      <th>Country Name</th>
                      <th>Continent</th>
                      <th>Independence Year</th>
                      <th>Head of State</th>
                    </tr>";
            foreach ($results as $row) {
                echo "<tr>
                        <td>" . htmlspecialchars($row['name']) . "</td>
                        <td>" . htmlspecialchars($row['continent']) . "</td>
                        <td>" . htmlspecialchars($row['independence_year'] ?? 'N/A') . "</td>
                        <td>" . htmlspecialchars($row['head_of_state']) . "</td>
                      </tr>";
            }
            echo "</table>";
        } else {
            echo "<p>No countries found.</p>";
        }
    }
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}
?>