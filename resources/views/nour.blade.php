<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>عرض صورة نور</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        html, body {
            height: 100%;
            width: 100%;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #000;
            overflow-y: auto;
        }

        .container {
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            padding: 20px;
        }

        h1 {
            color: #fff;
            font-size: 2.2rem;
            margin-bottom: 20px;
            text-align: center;
        }

        .image-wrapper {
            width: 100%;
            max-width: 1000px;
        }

        img {
            width: 100%;
            height: auto;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(255, 255, 255, 0.2);
        }

        footer {
            margin-top: 30px;
            text-align: center;
            color: #ccc;
            font-size: 1rem;
        }

        @media (max-width: 600px) {
            h1 {
                font-size: 1.6rem;
            }

            footer {
                font-size: 0.9rem;
            }
        }

        @media (min-width: 1024px) {
            h1 {
                font-size: 2.5rem;
            }

            footer {
                font-size: 1.1rem;
            }
        }
    </style>
</head>
<body>

    <div class="container">
        

        <div class="image-wrapper">
            <img src="{{ asset('nour-logo.jpg') }}" alt="Nour's Image">
        </div>

        <footer>
            BY NOUR SHAQRAWY
        </footer>
    </div>

</body>
</html>
