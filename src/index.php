<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Error</title>
    <link rel="icon" type="image/png" sizes="16x16" href="favicon.ico">
    <style>
        :root {
            --primary-dark: #0f1829;
            --secondary-dark: #404243;
            --primary-light: #fff;
            --secondary-light: #e8e8e8;
            --muted: #94a4b7;
            --accent: #06b539;
        }

        body, div, h1, h2, h3, h4, h5, p, label, small {
            appearance: none;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: Arial, sans-serif;
            font-size: 14px;
            color: var(--secondary-dark);
            background-color: var(--secondary-light);
        }

        main {
            display: flex;
            flex-direction: column;
        }

        .text-muted {
            color: var(--muted);
        }

        .container {
            width: 100%;
            max-width: 1280px;
            padding: 1rem;
        }

        .main-error {
            background-color: var(--primary-dark);
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 1rem 0;
        }

        .main-error .container {
            display: flex;
            gap: 2rem;
            justify-content: space-between;
            align-items: start;
        }

        .suggestions-container {
            width: 35%;
            color: var(--accent);
        }

        .suggestions-container ul {
            display: flex;
            flex-direction: column;
            gap: 0.3rem;
            padding: 0 0 0 20px;
            font-style: italic;
        }

        .main-error label {
            color: var(--muted);
        }

        .main-error h1 {
            color: var(--primary-light);
            margin: 1rem 0;
        }

        .snapshots-wrapper {
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            padding: 2rem 0;
        }

        .snapshots-wrapper .container {
            display: flex;
            flex-direction: column;
            gap: 2rem;
        }

        .snapshot-container {
            width: 100%;
            box-shadow: 0 3px 6px rgba(0, 0, 0, 0.5);
        }

        .snapshots-wrapper .stack-trace-label {
            margin-bottom: -1rem;
            text-transform: uppercase;
            font-weight: bolder
        }

        .code-block-wrapper {
            width: 100%;
        }

        .meta {
            background-color: var(--primary-light) !important;
            color: #58606b;
        }

        .code-highlighter {
            background-color: var(--primary-dark) !important;
        }

        .badge {
            background-color: var(--accent);
            color: var(--primary-light) !important;
            font-size: 11px;
            padding: 5px 10px;
            border-radius: 5px;
        }
    </style>
</head>
<body>
    <main>
        <div class="main-error">
            <div class="container">
                <div class="message-container">
                    <?php /** @var string $class */ ?>
                    <label class="badge"><?= $class ?></label>
                    <?php /** @var string $message */ ?>
                    <h1><?= $message ?></h1>
                    <?php
                    /**
                     * @var string $file
                     * @var string $line
                     */ ?>
                    <label class="text-muted">Found at <?= $file ?> on line <?= $line ?></label>
                </div>
                <?php if (!empty($solutions)): ?>
                    <div class="suggestions-container">
                        <label class="text-muted">Suggestions</label>
                        <ul>
                            <?php foreach ($solutions as $solution): ?>
                                <li><?= $solution ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endif ?>
            </div>
        </div>
        <div class="snapshots-wrapper">
            <div class="container">
                <h5 class="stack-trace-label">Stack Trace</h5>
                <?php /** @var array $snapshots */ ?>
                <?php foreach ($snapshots as $snapshot): ?>
                    <div class="snapshot-container">
                        <?= $snapshot ?>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </main>
</body>
</html>
