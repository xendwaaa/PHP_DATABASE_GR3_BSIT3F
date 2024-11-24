<?php 
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>GROUP 3 WEBSITE</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link href="css/style.css" rel="stylesheet">
    <style>

        /* Styling for the user dropdown */
        .user {
            position: relative;
            display: inline-block;
        }

        .user span {
            color: white;
            cursor: pointer;
            font-size: 16px;
            padding: 8px;
            display: inline-block;
        }

        /* Dropdown menu styling */
        .dropdown {
            display: none;
            position: absolute;
            background-color: #f9f9f9;
            min-width: 160px;
            box-shadow: 0px 8px 16px rgba(0,0,0,0.2);
            z-index: 1;
            border-radius: 5px;
            margin-top: 5px;
        }

        .dropdown a {
            color: black;
            padding: 12px 16px;
            text-decoration: none;
            display: block;
            font-size: 14px;
            transition: background-color 0.3s ease;
        }

        .dropdown a:hover {
            background-color: #ddd;
        }

        /* Show the dropdown when user clicks */
        .user:hover .dropdown {
            display: block;
        }
    </style>
</head>
<body>
    <div class="topmenu">
        <div class="menubar">
            <?php if (isset($_SESSION['name']) && !empty($_SESSION['name'])): ?>
                <!-- Dropdown for logged-in users -->
                <div class="user">
                    <a href="index.php">Home</a>
                    <span>Welcome, <?= htmlspecialchars($_SESSION['name'], ENT_QUOTES, 'UTF-8') ?></span>
                    <div class="dropdown">
                        <a href="logout.php">Logout</a>
                    </div>
                </div>
            <?php else: ?>
                <!-- Login and Register links for guests -->
                <a href="index.php">Home</a>
                <a href="register.php">Register</a>
                <a href="login.php">Login</a>
            <?php endif; ?>
        </div>
    </div>

    <script>
        // Toggle dropdown visibility
        const userElement = document.querySelector('.user span');
        const dropdown = document.querySelector('.dropdown');

        userElement.addEventListener('click', () => {
            dropdown.style.display = dropdown.style.display === 'block' ? 'none' : 'block';
        });

        // Close dropdown when clicking outside
        document.addEventListener('click', (event) => {
            if (!event.target.closest('.user')) {
                dropdown.style.display = 'none';
            }
        });
    </script>
</body>
</html>


