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
            --secondary-light: #f3f3f3;
            --muted: #666d76;
            --error: #fa1313;
            --error-opacity: #fa131330;
        }

        body, div, h1, h2, h3, h4, h5, p, label, small {
            appearance: none;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: "Trebuchet MS", Helvetica, Verdana, sans-serif;
            font-size: 16px;
            background-color: var(--secondary-light);
            display: flex;
            flex-direction: column;
            gap: 1rem;
            background-image: radial-gradient(#ccc 1px, transparent 0);
            background-size: 30px 30px;
        }

        @media screen and (min-width: 768px) {
            body {
                gap: 0;
            }
        }

        .container {
            width: 100%;
            max-width: 1280px;
            margin: auto;
        }

        @media screen and (min-width: 768px) {
            .container {
                padding: 1rem;
            }
        }

        .badge {
            background-color: var(--error);
            color: var(--primary-light) !important;
            font-size: 11px;
            padding: 5px 10px;
            border-radius: 2px;
        }

        .shadow-md {
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        }

        .header {
            background-color: var(--primary-light);
            padding: 1rem;
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }

        @media screen and (min-width: 768px) {
            .header {
                margin-top: 0.5rem;
            }
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
            font-size: 12px;
        }

        .suggestion-list {
            color: var(--muted);
            padding: 0 0 0 20px;
            list-style-position: outside;
            display: flex;
            flex-direction: column;
            gap: 5px;
            font-size: 14px;
        }

        .message {
            color: var(--primary-dark);
            font-size: 32px;
            margin-bottom: 0.5rem;
        }

        .location {
            color: var(--muted);
        }

        .code-block-wrapper {
            width: 100%;
        }

        .meta {
            background-color: var(--primary-light) !important;
            border-radius: 0 !important;
            color: var(--muted);
            display: none !important;
        }

        .code-highlighter {
            background-color: var(--primary-dark) !important;
            border: none !important;
            border-radius: 0 !important;
            height: 400px !important;
        }

        .code-block {
            margin-top: -15px !important;
        }

        .frame-tabs {
            position: relative;
            height: 410px;
            background: var(--primary-light);
        }

        .frame-tab {
            display: flex;
        }

        .frame-btn {
            padding: 10px;
            font-size: 12px;
            width: 100px;
            cursor: pointer;
            color: var(--error);
            background: var(--primary-light);
            overflow: hidden;
        }

        @media screen and (min-width: 768px) {
            .frame-btn {
                width: 280px;
            }
        }

        .frame-tab [type=radio] {
            display: none;
        }

        .frame-content {
            position: absolute;
            top: 0;
            bottom: 0;
            left: 120px;
            width: calc(100% - 120px);
            background-color: var(--primary-dark);
        }

        @media screen and (min-width: 768px) {
            .frame-content {
                left: 300px;
                width: calc(100% - 300px);
            }
        }

        [type=radio]:checked ~ .frame-btn {
            background: var(--error-opacity);
            border-bottom: 2px solid var(--error);
            z-index: 2;
        }

        [type=radio]:checked ~ .frame-btn ~ .frame-content {
            z-index: 1;
        }
    </style>
</head>
<body>
    <header class="container">
        <div class="header shadow-md">
            <div class="minor-details">
			    <?php /** @var string $class */ ?>
                <label class="badge"><?= $class ?></label>
			    <?php /** @var string $phpVersion */ ?>
                <label class="badge">PHP <?= $phpVersion ?></label>
            </div>
            <div class="major-details">
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
    </header>

    <?php /** @var array $frames */ ?>
    <div class="container">
        <div class="frame-tabs shadow-md">
            <?php foreach ($frames as $filename => $frame): ?>
                <div class="frame-tab">
                    <input type="radio" id="<?= $filename ?>" name="frame" <?= $filename === $file ? 'checked' : '' ?> >
                    <label class="frame-btn" for="<?= $filename ?>"><?= $filename ?></label>
                    <div class="frame-content">
                        <?= $frame ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</body>
</html>
