<?php
// Replace with your connection details
$dbHost = 'elvis.rowan.edu';
$dbUsername = 'Chiluk58';
$dbPassword = '1Pink3car!';
$dbName = 'Chiluk58';

// Create a connection
$conn = new mysqli($dbHost, $dbUsername, $dbPassword, $dbName);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}



// Check if the 'state' parameter is passed
if(isset($_GET['state'])) {
    $state = $_GET['state'];

    // SQL query to select customers from the specified state
    $sql = "SELECT customerName, contactLastName, contactFirstName, addressLine1, city, state, postalCode, country FROM customers WHERE state = ?";

    // Prepare the statement to prevent SQL injection
    $stmt = $conn->prepare($sql);

    // Bind the 'state' parameter to the statement
    $stmt->bind_param("s", $state);

    // Execute the query
    $stmt->execute();

    // Bind the results to variables
    $stmt->bind_result($customerName, $contactLastName, $contactFirstName, $addressLine1, $city, $state, $postalCode, $country);

    // Fetch all rows and print them in an HTML table
    echo "<table border='1'>";
    echo "<tr><th>CustomerName</th><th>Contact</th><th>AddressLine1</th><th>City</th><th>State</th><th>PostalCode</th><th>Country</th></tr>";
    while ($stmt->fetch()) {
        echo "<tr>";
        echo "<td>" . $customerName . "</td>";
        echo "<td>" . $contactLastName . " " . $contactFirstName . "</td>";
        echo "<td>" . $addressLine1 . "</td>";
        echo "<td>" . $city . "</td>";
        echo "<td>" . $state . "</td>";
        echo "<td>" . $postalCode . "</td>";
        echo "<td>" . $country . "</td>";
        echo "</tr>";
    }
    echo "</table>";

    // Close the statement
    $stmt->close();
} else {
    // SQL query to count the number of customers by state
    $sql = "SELECT state, COUNT(*) as totalCustomers FROM customers GROUP BY state";

    // Execute the query
    $result = $conn->query($sql);

    // Check if the query was successful
    if ($result === false) {
        die("Error: " . $conn->error);
    }

    // Start the HTML table
    echo "<table border='1'>";
    echo "<tr><th>State</th><th>Total Customers</th></tr>";

    // Fetch the rows from the result
    while ($row = $result->fetch_assoc()) {
        $state = htmlspecialchars($row['state']);
        $totalCustomers = htmlspecialchars($row['totalCustomers']);
        // Create the link to the detailed view for each state
        $link = htmlspecialchars($_SERVER['PHP_SELF'])."?state=$state";
        echo "<tr>";
        echo "<td><a href='$link'>$state</a></td>";
        echo "<td>$totalCustomers</td>";
        echo "</tr>";
    }

    // End the HTML table
    echo "</table>";

    // Free the result set
    $result->free();
}

// Close the connection
$conn->close();
?>