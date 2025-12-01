<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($title ?? 'Scoreboard') ?></title>
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
        table {
            width: 100%;
            background: white;
            border-radius: 5px;
            overflow: hidden;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        thead {
            background: #667eea;
            color: white;
        }
        th, td {
            padding: 1rem;
            text-align: left;
        }
        tbody tr:nth-child(even) {
            background: #f9f9f9;
        }
        .rank {
            font-weight: bold;
            color: #667eea;
        }
    </style>
</head>
<body>
    <div class="container">
        <header>
            <h1>Scoreboard</h1>
        </header>
        
        <table>
            <thead>
                <tr>
                    <th>Rank</th>
                    <th>Team/User</th>
                    <th>Score</th>
                    <th>Solves</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($standings)): ?>
                    <tr>
                        <td colspan="4" style="text-align: center; padding: 2rem;">
                            No standings available yet.
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($standings as $index => $standing): ?>
                        <tr>
                            <td class="rank">#<?= $index + 1 ?></td>
                            <td><?= htmlspecialchars($standing['name'] ?? 'Unknown') ?></td>
                            <td><?= htmlspecialchars($standing['score'] ?? 0) ?></td>
                            <td><?= htmlspecialchars($standing['solves'] ?? 0) ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</body>
</html>

