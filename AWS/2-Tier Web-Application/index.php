<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Database Connection Status</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 50px;
        }
        .status {
            padding: 20px;
            border-radius: 5px;
            color: white;
            font-weight: bold;
        }
        .success {
            background-color: green;
        }
        .failure {
            background-color: red;
        }
    </style>
</head>
<body>

<?php
$servername = "demo-project-db-instance-1.cvpc1scbkvq8.ap-south-1.rds.amazonaws.com";
$username = "admin"; // Your database username
$password = "paassword123"; // Your database password
$dbname = "sample_db";

try {
    // Create connection
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    // Set the PDO error mode to exception
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "<div class='status success'>Connection to database was successful!</div>";

    // Fetch some data as a proof of connection
    $stmt = $conn->prepare("SELECT * FROM users");
    $stmt->execute();

    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if ($result) {
        echo "<h3>Sample Data:</h3>";
        echo "<ul>";
        foreach ($result as $row) {
            echo "<li>" . htmlspecialchars($row['name']) . "</li>";
        }
        echo "</ul>";
    } else {
        echo "<p>No data found in the table.</p>";
    }
} catch(PDOException $e) {
    echo "<div class='status failure'>Connection failed: " . htmlspecialchars($e->getMessage()) . "</div>";
}

// Close connection
$conn = null;
?>

</body>
</html>
