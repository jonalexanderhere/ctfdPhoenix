<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($title ?? 'CTFd') ?></title>
    <link rel="stylesheet" href="/themes/core/static/css/main.css">
</head>
<body>
    <div class="container">
        <header>
            <h1><?= htmlspecialchars($title ?? 'CTFd') ?></h1>
        </header>
        
        <main>
            <div class="welcome">
                <h2>Welcome to CTFd</h2>
                <p>CTFd is running on Vercel with PHP!</p>
                
                <div class="actions">
                    <a href="/challenges" class="btn">View Challenges</a>
                    <a href="/scoreboard" class="btn">Scoreboard</a>
                    <a href="/login" class="btn">Login</a>
                    <a href="/register" class="btn">Register</a>
                </div>
            </div>
        </main>
    </div>
</body>
</html>

