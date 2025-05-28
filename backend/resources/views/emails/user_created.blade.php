<!DOCTYPE html>
<html lang="sk">
<head>
    <meta charset="UTF-8">
    <title>Vytvorenie účtu</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f8f9fa;
            color: #333;
            margin: 0;
            padding: 0;
            text-decoration: none;
        }   
        .message {
            font-size: 1.1em;
            line-height: 1.6;
            margin: 16px 0;
        }
        .container {
            background: #fafafa;
            max-width: 480px;
            margin: 40px auto;
            padding: 32px 24px;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.07);
        }
        .credentials {
            background: #f1f3f6;
            padding: 16px;
            border-radius: 6px;
            margin: 16px 0;
            font-size: 1.05em;
        }
        .credentials strong {
            display: inline-block;
            width: 70px;
        }
        .footer {
            margin-top: 24px;
            font-size: 0.95em;
            color: #888;
        }
    </style>
</head>
<body>
    <div class="container">
        <H2>Dobrý deň,</H2>
        <p class="message">Váš účet bol úspešne vytvorený. Prihláste sa s týmito údajmi:</p>
        <div class="credentials">
            <div><strong>Email:</strong> {{ $user->email }}</div>
            <div><strong>Heslo:</strong> {{ $password }}</div>
        </div>
        <p>Odporúčame si heslo po prihlásení zmeniť.</p>
        <div class="footer">
            S pozdravom,<br>
            Tím podpory
        </div>
    </div>
</body>
</html>