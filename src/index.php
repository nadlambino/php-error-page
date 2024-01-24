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
            --secondary-light: #fafafa;
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
            gap: 2rem;
            background-size: 10px 10px;
            background-image:
                    linear-gradient(to right, #f3f3f3 1px, transparent 1px),
                    linear-gradient(to bottom, #f3f3f3 1px, transparent 1px);
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
            background-color: var(--error-opacity);
            color: var(--error);
            font-size: 11px;
            padding: 5px 10px;
            border-radius: 2px;
            font-weight: bolder;
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
            flex-direction: column;
            justify-content: space-between;
            gap: 0.5rem;
        }

        @media screen and (min-width: 768px) {
            .minor-details {
                flex-direction: row;
            }
        }

        .versions {
            display: flex;
            justify-content: flex-end;
            gap: 0.5rem;
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

        .frame-sidebar {
            height: 100%;
            width: 300px;
            overflow-y: auto;
            overflow-x: hidden;
        }

        .frame-sidebar [type=radio] {
            display: none;
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
            overflow-wrap: break-word;
            display: block;
            border-bottom: 1px solid var(--secondary-light);
        }

        @media screen and (min-width: 768px) {
            .frame-btn {
                width: 280px;
            }
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

        .active-frame {
            z-index: 2;
            position: absolute;
            top: 0;
            width: 100%;
        }

        .active-label {
            background: var(--error-opacity);
            border-bottom: 2px solid var(--error);
        }
    </style>
</head>
<body>
    <header class="container">
        <div class="header shadow-md">
            <div class="minor-details">
			    <?php /** @var string $class */ ?>
                <label class="badge"><?= $class ?></label>
                <div class="versions">
	                <?php /** @var string $phpVersion */ ?>
                    <label class="badge">PHP <?= $phpVersion ?></label>
	                <?php /** @var string $appVersion */ ?>
                    <label class="badge">APP <?= $appVersion ?></label>
	                <?php /** @var string $packageVersion */ ?>
                    <label class="badge">PACKAGE <?= $packageVersion ?></label>
                </div>
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
            <div class="frame-sidebar">
	            <?php foreach ($frames as $filename => $frame): ?>
                    <?php
                        $id = str_replace("\\", '-', $filename);
                    ?>
                    <input type="radio" id="<?= $id ?>" onchange="showFrame()" name="frame" <?= $filename === $file ? 'checked' : '' ?>>
                    <label role="button" class="frame-btn" for="<?= $id ?>"><?= $frame['location'] ?></label>
	            <?php endforeach; ?>
            </div>
            <?php foreach ($frames as $filename => $frame): ?>
	            <?php
	            $id = str_replace("\\", '-', $filename);
	            ?>
                <div class="frame-tab" data-id="<?= $id ?>">
                    <div class="frame-content">
                        <?= $frame['frame'] ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
<script>
    showFrame();

    function showFrame() {
        const checkedRadioButton = document.querySelector('input[name="frame"]:checked');
        const frames = document.querySelectorAll('.active-frame');
        const labels = document.querySelectorAll(`.active-label`);

        for (let frame of frames) {
            frame.classList.remove('active-frame');
        }

        for (let label of labels) {
            label.classList.remove('active-label');
        }

        if (checkedRadioButton) {
            const frame = document.querySelector(`[data-id="${checkedRadioButton.id}"]`);
            const label = document.querySelector(`label[for="${checkedRadioButton.id}"]`);

            frame.classList.toggle('active-frame');
            label.classList.toggle('active-label');
        }
    }
</script>
</body>
</html>
