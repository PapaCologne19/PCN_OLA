<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Offline Form</title>
</head>
<body>

<?php
// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Process the form data here

    // For demonstration purposes, let's just print the submitted data
    echo "<p>Data submitted:</p>";
    echo "<pre>";
    print_r($_POST);
    echo "</pre>";

    // Clear the stored form data
    unset($_SESSION['formData']);
} else {
    // Check for stored form data
    session_start();
    $storedFormData = isset($_SESSION['formData']) ? $_SESSION['formData'] : null;

    if ($storedFormData) {
        // You can pre-fill the form fields with the stored data if needed
        echo "<script>
                document.getElementById('name').value = '" . $storedFormData['name'] . "';
                document.getElementById('email').value = '" . $storedFormData['email'] . "';
              </script>";

        // Optionally, you can notify the user about the unsent data
        echo "<script>alert('You have unsent form data. Click Submit to send it.');</script>";
    }
}
?>

<form id="myForm" method="post">
    <!-- Your form fields go here -->
    <label for="name">Name:</label>
    <input type="text" id="name" name="name" required>
    <br>
    <label for="email">Email:</label>
    <input type="email" id="email" name="email" required>
    <br>
    <button type="button" onclick="submitForm()">Submit</button>
</form>

<script>
function submitForm() {
    // Get form data
    var formData = {
        name: document.getElementById('name').value,
        email: document.getElementById('email').value
    };

    // Store form data in the session (server-side)
    fetch('storeFormData.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify(formData),
    });

    // Simulate form submission
    alert('Form submitted!');

    // Clear form
    document.getElementById('myForm').reset();
}
</script>

</body>
</html>
