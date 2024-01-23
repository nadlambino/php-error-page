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
            --muted: #7a828d;
            --accent: #06b539;
        }

        body, div, h1, h2, h3, h4, h5, p, label, small {
            appearance: none;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: Arial, sans-serif;
            font-size: 16px;
            background-color: var(--primary-light);
        }

        .container {
            width: auto;
            max-width: 1280px;
            padding: 1rem;
            margin: auto;
        }

        .badge {
            background-color: var(--accent);
            color: var(--primary-light) !important;
            font-size: 11px;
            padding: 5px 10px;
            border-radius: 5px;
        }

        .header {
            background-color: var(--primary-dark);
        }

        .minor-details {
            display: flex;
            justify-content: space-between;
        }

        .major-details {
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }

        @media screen and (min-width: 1024px) {
            .major-details {
                flex-direction: row;
            }
        }

        .major-details-item {
            width: 100%;
        }

        .suggestions-container {
            width: 100%;
        }

        @media screen and (min-width: 1024px) {
            .suggestions-container {
                width: 50%;
            }
        }

        .suggestions-container label {
            color: var(--muted);
        }

        .suggestion-list {
            color: #00ff51;
            font-style: italic;
            padding: 0 0 0 20px;
            list-style-position: outside;
            display: flex;
            flex-direction: column;
            gap: 5px;
        }

        .message {
            color: var(--primary-light);
            font-size: 32px;
            margin-bottom: 0.5rem;
        }

        .location {
            color: var(--muted);
        }

        .frames-container {
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }

        .frames-label {
            color: var(--muted);
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
    </style>
</head>
<body>
    <div class="header">
        <div class="container minor-details">
            <?php /** @var string $class */ ?>
            <label class="badge"><?= $class ?></label>
            <?php /** @var string $phpVersion */ ?>
            <label class="badge">PHP <?= $phpVersion ?></label>
        </div>
        <div class="container major-details">
            <div class="major-details-item">
                <?php /** @var string $message */ ?>
                <h1 class="message"><?= $message ?></h1>
                <?php
                /**
                 * @var string $file
                 * @var string $line
                 */ ?>
                <label class="text-muted location"><?= $file ?> on line <?= $line ?></label>
            </div>
            <?php if (!empty($solutions)): ?>
                <div class="major-details-item suggestions-container">
                    <label>Suggestions</label>
                    <ul class="suggestion-list">
                        <?php foreach ($solutions as $solution): ?>
                            <li><?= $solution ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif ?>
        </div>
    </div>
    <div class="frames-container container">
        <h5 class="frames-label">Frames</h5>
        <?php /** @var array $frames */ ?>
        <?php foreach ($frames as $frame): ?>
            <div class="frame-container">
                <?= $frame ?>
            </div>
        <?php endforeach; ?>
    </div>
</body>
</html>
