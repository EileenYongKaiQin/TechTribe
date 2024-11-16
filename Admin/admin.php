<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FlexMatch</title>
    <style>
        /* General styles */
        @import url('https://fonts.googleapis.com/css2?family=Geist+Mono:wght@100..900&family=Host+Grotesk:ital,wght@0,300..800;1,300..800&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Reem+Kufi:wght@400..700&display=swap');
        * {
            font-family: "Poppins", sans-serif;
            font-weight: 500;
            font-style: normal;
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            background-image: url('../images/background.jpg');
        }

        /* Header */
        header {
            background-color: black;
            color: #fff;
            padding: 1rem;
            text-align: center;
            display: flex;
            justify-content: center; /* Center title */
            align-items: center;
            position: relative;
        }

        /* Sidebar toggle button */
        .toggle-btn {
            background: linear-gradient(135deg, #555555, #333333, #212121);
            border: none;
            color: #fff;
            padding: 0.4rem 0.8rem;
            cursor: pointer;
            position: absolute;
            left: 10px; /* Align toggle button to the left */
            top: 40%; /* Center vertically if needed */
            transform: translateY(-50%);
            font-size: 1rem;
            max-width: 40px; /* Set a max-width to prevent stretching */
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }

        /* Sidebar */
        .sidebar {
            width: 250px;
            background-color: black;
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
            border-bottom: 1px solid #e5e8eb;
        }

        /* Close button in sidebar */
        .close-btn {
            background: linear-gradient(135deg, #555555, #333333, #212121);
            border: none;
            color: #fff;
            padding: 0.4rem 0.8rem;
            cursor: pointer;
            position: absolute;
            top: 10px;
            right: 10px;
            font-size: 1rem;
            max-width: 40px; /* Set a max-width */
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }

        /* Main content */
        .content {
            flex: 1;
            padding: 2rem;
            margin-left: 0;
            transition: margin-left 0.3s ease;
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
        <h1>FlexMatch</h1>
    </header>

    <!-- Sidebar -->
    <nav class="sidebar" id="sidebar">
        <button class="close-btn" onclick="toggleSidebar()">×</button>
        <ul>
            <li>Home</li>
            <li>About</li>
            <li>Services</li>
            <li>Contact</li>
        </ul>
    </nav>

    <!-- JavaScript for Sidebar Toggle -->
    <script>
        function toggleSidebar() {
            document.body.classList.toggle('sidebar-visible');
        }
    </script>
</body>
</html>
