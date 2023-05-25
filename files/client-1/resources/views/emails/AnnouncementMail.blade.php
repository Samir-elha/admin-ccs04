<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>{{ $subject }}</title>
    <style>
        body {
            font-family: sans-serif;
        }

        .container {
            max-width: 600px;
            margin: 0 auto;
            padding: 1rem;
        }

        .logo {
            font-size: 2rem;
            font-weight: bold;
            color: #4299e1;
        }

        .card {
            background-color: #fff;
            border: 1px solid #e2e8f0;
            border-radius: 0.5rem;
            padding: 2rem;
            margin-top: 2rem;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .card h1 {
            font-size: 1.5rem;
            font-weight: bold;
            color: #2d3748;
            margin-top: 0;
        }

        .card p {
            font-size: 1rem;
            color: #4a5568;
        }

        .button {
            background-color: #4299e1;
            color: #fff;
            border-radius: 0.5rem;
            padding: 0.75rem 1.5rem;
            text-decoration: none;
            font-size: 1rem;
            font-weight: bold;
            display: inline-block;
        }

        .footer {
            margin-top: 2rem;
            font-size: 0.8rem;
            color: #a0aec0;
        }
    </style>
</head>

<body>
<div class="container">
    <div class="logo">Job Application System</div>
    <div class="card">
        <h1>New Announcement</h1>
        <p>Dear {{ $data['student']['firstName'] }} {{ $data['student']['lastName'] }},</p>
        <p>Your coordinator has posted a new announcement.</p>

        <a class="button" href="https://ddpa.jobapplicationtraining.be/">Go to announcements</a>
    </div>
    <div class="footer">Kind regards, The Job Application System</div>
</div>
</body>

</html>
