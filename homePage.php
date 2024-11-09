<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200&icon_names=close" />
    <title>FlexMatch</title>
    <style>
        /* General styles */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: Arial, sans-serif;
        }

        body {
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        /* Header */
        header {
            background-color: #000000;
            color: #fff;
            padding: 1rem;
            text-align: center;
            display: flex;
            justify-content: center; /* Center title */
            align-items: center;
            position: relative;
        }

        .logo{
            margin: 0 10px;
        }

        /* Sidebar toggle button */
        .toggle-btn {
            background-color: rgb(117, 116, 116);
            border: none;
            color: #fff;
            padding: 0.5rem 1rem;
            margin: 20px;
            cursor: pointer;
            position: absolute;
            left: 10px; /* Align toggle button to the left */
        }

        /* Sidebar */
        .sidebar {
            width: 250px;
            background-color: rgb(117, 116, 116);
            color: #fff;
            position: fixed;
            top: 0;
            left: -250px; /* Start hidden */
            height: 100%;
            overflow-y: auto;
            transition: left 0.3s ease;
            padding-top: 60px;
        }

        .sidebar ul {
            list-style-type: none;
            padding: 0;
        }

        .sidebar ul li {
            padding: 1rem;
            text-align: center;
            /* border-bottom: 1px solid white; */
            color: white;
            letter-spacing: 2px;
        }

        .sidebar ul li a{
            text-decoration: none;
            color: white;
        }

        .sidebar li:hover{
            background: rgb(99, 97, 97);
        }

        /* Close button in sidebar */
        .close-btn {
            background-color: rgb(117, 116, 116);
            border: none;
            color: #fff;
            padding: 0.5rem 1rem;
            cursor: pointer;
            position: absolute;
            top: 10px;
            right: 10px;
        }

        /* Main content */
        .content {
            flex: 1;
            padding: 2rem;
            margin-left: 0;
            transition: margin-left 0.3s ease;
        }

        /* Footer */
        footer {
            background-color: #000000;
            color: #fff;
            padding: 1rem;
            text-align: center;
        }

        /* Active sidebar styles */
        .sidebar-visible .sidebar {
            left: 0; /* Show sidebar */
        }

        .sidebar-visible .content {
            margin-left: 250px; /* Shift content */
        }

        /* Responsive design */
        @media (max-width: 768px) {
            .sidebar {
                width: 200px;
                left: -200px;
            }

            .sidebar-visible .content {
                margin-left: 200px;
            }
        }
    </style>
</head>
<body>

    <!-- Header -->
    <header>
        <button class="toggle-btn" onclick="toggleSidebar()">☰</button>
        <img class="logo" src="logo.jpg" alt="Bootstrap" width="50" height="44">
        <h1>FlexMatch</h1>
    </header>

    <!-- Sidebar -->
    <nav class="sidebar" id="sidebar">
        <button class="close-btn" onclick="toggleSidebar()"><span class="material-symbols-outlined">
            close
            </span></button>
        <ul>
            <li><a href="#">Home</a></li>
            <li><a href="#">About</a></li>
            <li><a href="#">Services</a></li>
            <li><a href="#">Contact</a></li>
        </ul>
    </nav>

    <!-- Main content -->
    <div class="content" id="content">
        <h2>APPLY JOB!</h2>
    </div>

    <!-- Footer -->
    <footer>
        <p>&copy; 2024 TEAM TECHTRIBE</p>
    </footer>

    <!-- JavaScript for Sidebar Toggle -->
    <script>
        function toggleSidebar() {
            document.body.classList.toggle('sidebar-visible');
        }
    </script>
</body>
</html>