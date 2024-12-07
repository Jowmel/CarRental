<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CTU AC Accredited Boarding House</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            display: flex;
            flex-direction: column;
            align-items: center;
            background-color: white;
            height: 100vh;
			overflow: hidden;
        }

        .container {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100%;
            width: 100%;
        }

        .card {
            background-color: #91b783;
            border-radius: 8px;
            padding: 20px;
            text-align: center;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            width: 80%;
            max-width: 600px;
        }

        header {
            display: flex;
            align-items: center;
            padding: 20px;
            width: 100%;
            justify-content: center;
            background-color: #f5f5f5;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        header h1 {
            font-size: 24px;
            margin: 0 10px;
            flex-grow: 1;
            text-align: center;
        }

        .logo {
            height: 50px;
        }

        .main-content {
            display: flex;
            justify-content: space-around;
            padding: 20px;
        }

        .button {
            background-color: white;
            border-radius: 8px;
            padding: 20px;
            text-align: center;
            width: 40%;
            cursor: pointer;
            transition: transform 0.3s;
        }

        .button:hover {
            transform: scale(1.05);
        }

        .button img {
            width: 50px;
            height: 50px;
        }

        .button h2 {
            margin-top: 10px;
            font-size: 16px;
            color: #333;
        }

        /* Media Queries */
        @media (max-width: 768px) {
            .main-content {
                flex-direction: column;
                align-items: center;
            }

            .button {
                width: 80%;
                margin-bottom: 20px;
            }
        }

        @media (max-width: 480px) {
            header {
                flex-direction: column;
                text-align: center;
            }

            header h1 {
                font-size: 16px;
            }

            .logo {
                height: 40px;
            }

            .button {
                width: 90%;
            }

            .button h2 {
                font-size: 14px;
            }
        }
    </style>
</head>
<body>
    <header>
        <img src="static/default-img.png" alt="CTU AC Logo 1" class="logo">
        <h1>CTU AC Accredited Boarding House</h1>
        <img src="static/default-img.png" alt="CTU AC Logo 2" class="logo">
    </header>
    <main class="container">
        <div class="card">
            <div class="main-content">
                <div class="button" id="admin">
                    <img src="static/default-img.png" alt="Admin Icon">
                    <h2>Admin</h2>
                </div>
                <div class="button" id="student">
                    <img src="static/default-img.png" alt="Student Icon">
                    <h2>Student</h2>
                </div>
            </div>
        </div>
    </main>
    <script>
        document.getElementById('admin').addEventListener('click', function() {
            alert('Admin section clicked!');
        });

        document.getElementById('student').addEventListener('click', function() {
            alert('Student section clicked!');
        });
    </script>
</body>
</html>
