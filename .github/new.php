<?php
session_start();
$errors = array();

if (isset($_SERVER["REQUEST_METHOD"]) && $_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate Full Name
    if (empty($_POST["full_name"])) {
        $errors["full_name"] = "Full Name is required";
    } elseif (!preg_match("/^[a-zA-Z ]*$/", $_POST["full_name"])) {
        $errors["full_name"] = "Only letters and white space allowed in Full Name";
    }
    // Validate Email
    if (empty($_POST["email"])) {
        $errors["email"] = "Email is required";
    } elseif (!filter_var($_POST["email"], FILTER_VALIDATE_EMAIL)) {
        $errors["email"] = "Invalid email format";
    }
    // Validate Phone
    if (empty($_POST["phone"])) {
        $errors["phone"] = "Phone number is required";
    } elseif (!preg_match("/^\d{10}$/", $_POST["phone"])) {
        $errors["phone"] = "Phone number must be 10 digits";
    }
    // Validate Gender
    if (empty($_POST["gender"])) {
        $errors["gender"] = "Gender is required";
    }
    // Validate Date of Joining
    if (empty($_POST["doj"])) {
        $errors["doj"] = "Date of Joining is required";
    }
    // Validate Membership Type
    if (empty($_POST["membership_type"])) {
        $errors["membership_type"] = "Membership Type is required";
    }
    // If there are no errors, proceed with the registration logic
    if (empty($errors)) {
        // Database connection code...
        $servername = "localhost";
        $username = "root";
        $password = "";
        $dbname = "formdb";
        $conn = new mysqli($servername, $username, $password, $dbname, 3306);
        // Check the connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }
        // Escape user inputs for security
        $full_name = $conn->real_escape_string($_POST["full_name"]);
        $email = $conn->real_escape_string($_POST["email"]);
        $address = $conn->real_escape_string($_POST["address"]);
        $age = $conn->real_escape_string($_POST["age"]);
        $phone = $conn->real_escape_string($_POST["phone"]);
        $gender = $conn->real_escape_string($_POST["gender"]);
        $doj = $conn->real_escape_string($_POST["doj"]);
        $membership_type = $conn->real_escape_string($_POST["membership_type"]);
        $duration = $conn->real_escape_string($_POST["duration"]);
        // Insert data into the database
        $sql = "INSERT INTO gym (full_name, email, address, age, phone, gender, doj, membership_type, duration)
        VALUES ('$full_name', '$email', '$address', '$age', '$phone', '$gender', '$doj', '$membership_type', '$duration')";
        if ($conn->query($sql) === true) {
            // Registration successful, set a session variable
            $_SESSION['registration_success'] = true;
        } else {
            // If the insertion fails, display an error
            $errors["database_error"] = "Error: " . $sql . "<br>" . $conn->error;
        }
        // Close the connection
        $conn->close();
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles.css">
    <title>Gym Registration</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            background: url('C:\Users\anshu\Downloads\gympic.jpg');
            background-size: cover;
            background-color: #2c3e50; /* Fallback color */
        }

        .container {
            max-width: 600px;
            margin: 200px auto;
            background-color: rgba(255, 255, 255, 0.8);
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            text-align: center;
            color: #333;
        }

        .form-heading {
            margin-bottom: 20px;
        }

        .registration-form {
            display: grid;
            grid-gap: 10px;
        }

        label {
            font-weight: bold;
            color: #333;
        }

        input,
        select {
            width: 100%;
            padding: 8px;
            box-sizing: border-box;
            margin-top: 6px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        input[type="submit"] {
            background-color: #4CAF50;
            color: azure;
            padding: 10px 15px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
        }

        input[type="submit"]:hover {
            background-color: azure;
        }
    </style>
</head>

<body>
    <div class="container">
        <h2 class="form-heading">Fitness gym</h2>
        <?php
        // Display errors if there are any
        if (!empty($errors)) {
            echo '<div class="error-container">';
            foreach ($errors as $error) {
                echo '<p class="error">' . $error . '</p>';
            }
            echo '</div>';
        }
        ?>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" class="registration-form">
            <label for="full_name">Full Name:</label>
            <input type="text" name="full_name" required>
            <label for="email">Email:</label>
            <input type="email" name="email" required>
            <label for="Address">Address:</label>
            <input type="text" name="address" required>
            <label for="age">Age:</label>
            <input type="number" name="age" required>
            <label for="phone">Phone:</label>
            <input type="tel" name="phone" required>
            <label for="gender">Gender:</label>
            <select name="gender" required>
                <option value="male">Male</option>
                <option value="female">Female</option>
            </select>
            <label for="doj">Date of Joining:</label>
            <input type="date" name="doj" required>
            <label for="membership_type">Membership Type:</label>
            <select name="membership_type" required>
                <option value="basic">Basic</option>
                <option value="premium">Premium</option>
            </select>
            <label for="duration">Membership Duration</label>
            <label for="duration">1 month</label>
            <input type="radio" name="duration" value="1 month" required>
            <label for="duration">3 months</label>
            <input type="radio" name="duration" value="3 months" required>
            <label for="duration">6 months</label>
            <input type="radio" name="duration" value="6 months" required>
            <label for="duration">1 year</label>
            <input type="radio" name="duration" value="1 year" required>
            <input type="submit" value="Register">
        </form>
    </div>
</body>

</html>
