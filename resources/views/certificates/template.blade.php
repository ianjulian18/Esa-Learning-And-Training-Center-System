<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Certificate of Completion</title>
    <style>
        body { font-family: sans-serif; text-align: center; border: 10px solid #4f46e5; padding: 50px; margin: 20px; }
        h1 { font-size: 50px; color: #1e1b4b; }
        h2 { font-size: 30px; color: #4338ca; margin-top: 50px; }
        p { font-size: 20px; color: #374151; }
        .name { font-size: 40px; font-weight: bold; text-decoration: underline; margin: 20px 0; }
        .course { font-size: 35px; font-weight: bold; color: #3730a3; margin: 20px 0; }
        .footer { margin-top: 100px; display: flex; justify-content: space-between; font-size: 14px; color: #6b7280; }
    </style>
</head>
<body>
    <h1>Certificate of Completion</h1>
    <p>This is to certify that</p>
    <div class="name">{{ $user }}</div>
    <p>has successfully completed the course</p>
    <div class="course">{{ $course }}</div>
    <p>on {{ $date }}</p>

    <div class="footer">
        <div>Certificate Number: {{ $number }}</div>
    </div>
</body>
</html>
