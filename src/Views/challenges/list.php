<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($title ?? 'Challenges') ?></title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background: #f5f5f5;
        }
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 2rem;
        }
        header {
            background: white;
            padding: 1rem;
            border-radius: 5px;
            margin-bottom: 2rem;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        h1 {
            color: #333;
        }
        .challenges-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 1rem;
        }
        .challenge-card {
            background: white;
            padding: 1.5rem;
            border-radius: 5px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        .challenge-card h3 {
            margin-bottom: 0.5rem;
            color: #333;
        }
        .challenge-card p {
            color: #666;
            margin-bottom: 1rem;
        }
        .btn {
            display: inline-block;
            padding: 0.5rem 1rem;
            background: #667eea;
            color: white;
            text-decoration: none;
            border-radius: 5px;
        }
        .btn:hover {
            background: #5568d3;
        }
    </style>
</head>
<body>
    <div class="container">
        <header>
            <h1>Challenges</h1>
        </header>
        
        <div class="challenges-grid">
            <?php if (empty($challenges)): ?>
                <div class="challenge-card">
                    <p>No challenges available yet.</p>
                </div>
            <?php else: ?>
                <?php foreach ($challenges as $challenge): ?>
                    <div class="challenge-card">
                        <h3><?= htmlspecialchars($challenge['name'] ?? 'Challenge') ?></h3>
                        <p><?= htmlspecialchars($challenge['description'] ?? '') ?></p>
                        <a href="/challenge/<?= $challenge['id'] ?>" class="btn">View Challenge</a>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>

