<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
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
            background-color: #333;
            color: #fff;
            padding: 1rem;
            text-align: center;
            display: flex;
            justify-content: center; /* Center title */
            align-items: center;
            position: relative;
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
        <h1>FlexMatch</h1>
    </header>

</body>
</html>
