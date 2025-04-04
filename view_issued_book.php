<?php
session_start();

// Make sure that user is logged in by checking if session ID is set
if (!isset($_SESSION['id'])) {
    // Redirect user to login page if not logged in
    header("Location: login.php");
    exit();
}

$connection = mysqli_connect("localhost", "root", "", "lms1");

// Check connection
if (!$connection) {
    die("Connection failed: " . mysqli_connect_error());
}

$book_name = "";
$author = "";
$book_no = "";

// Using a prepared statement for security
$query = "SELECT book_name, book_author, book_no FROM issued_books WHERE student_id = ? AND status = 1";
$stmt = mysqli_prepare($connection, $query);

// Bind the session ID parameter
mysqli_stmt_bind_param($stmt, "i", $_SESSION['id']);

// Execute the prepared statement
mysqli_stmt_execute($stmt);

// Store the result
$result = mysqli_stmt_get_result($stmt);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Issued Books</title>
    <link rel="stylesheet" type="text/css" href="bootstrap-4.4.1/css/bootstrap.min.css">
    <script type="text/javascript" src="bootstrap-4.4.1/js/jquery_latest.js"></script>
    <script type="text/javascript" src="bootstrap-4.4.1/js/bootstrap.min.js"></script>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="user_dashboard.php">Library Management System (LMS1)</a>
            <span style="color: white"><strong>Welcome: <?php echo $_SESSION['name']; ?></strong></span>
            <span style="color: white"><strong>Email: <?php echo $_SESSION['email']; ?></strong></span>
            <ul class="nav navbar-nav navbar-right">
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" data-toggle="dropdown">My Profile</a>
                    <div class="dropdown-menu">
                        <a class="dropdown-item" href="view_profile.php">View Profile</a>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item" href="edit_profile.php">Edit Profile</a>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item" href="change_password.php">Change Password</a>
                    </div>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="logout.php">Logout</a>
                </li>
            </ul>
        </div>
    </nav><br>

    <span><marquee>This is library management system. Library opens at 8:00 AM and closes at 8:00 PM</marquee></span><br><br>

    <center><h4>Issued Book's Detail</h4><br></center>

    <div class="row">
        <div class="col-md-2"></div>
        <div class="col-md-8">
            <table class="table-bordered" width="900px" style="text-align: center">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Author</th>
                        <th>Number</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // Fetch and display the results
                    while ($row = mysqli_fetch_assoc($result)) {
                        echo "<tr>";
                        echo "<td>" . htmlspecialchars($row['book_name']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['book_author']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['book_no']) . "</td>";
                        echo "</tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
        <div class="col-md-2"></div>
    </div>

</body>
</html>

<?php
// Close the prepared statement and connection
mysqli_stmt_close($stmt);
mysqli_close($connection);
?>
