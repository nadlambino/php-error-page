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
            --success: #17b944;
            --success-opacity: #00ff3c30;
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

        .error-badge {
            background-color: var(--error-opacity);
            color: var(--error);
            font-size: 11px;
            padding: 5px 10px;
            border-radius: 2px;
            font-weight: bolder;
        }

        .success-badge {
            background-color: var(--success-opacity);
            color: var(--success);
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

        .suggestion-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .suggestion-actions button {
            background-color: var(--success-opacity);
            color: var(--success);
            border: none;
            padding: 5px 8px;
            cursor: pointer;
            width: 100px;
        }

        .suggestion-actions button:disabled {
            cursor: not-allowed;
            background-color: var(--secondary-light);
            color: var(--muted);
        }

        .suggestion-list {
            color: var(--muted);
            padding: 0;
            list-style: none;
            display: flex;
            font-size: 14px;
            overflow-x: auto;
            white-space: nowrap;
            scroll-snap-type: x mandatory;
            transition: transform 1s ease;
        }

        .suggestion-list::-webkit-scrollbar {
            display: none;
        }

        .suggestion-list li {
            width: 100%;
            min-width: 100%;
            display: block;
            white-space: break-spaces;
            scroll-snap-align: start;
            transition: transform 1s ease;
        }

        .message {
            color: var(--primary-dark);
            font-size: 32px;
            margin-bottom: 0.5rem;
            overflow-wrap: break-word;
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
            margin-top: -30px !important;
        }

        .frame-tabs {
            position: relative;
            height: 410px;
            background: var(--primary-light);
            margin-bottom: 40px;
        }

        .frame-sidebar {
            height: 100%;
            width: 320px;
            overflow-y: scroll;
            overflow-x: hidden;
        }

        .frame-sidebar::-webkit-scrollbar {
            width: 3px;
            background-color: var(--primary-light);
        }

        .frame-sidebar::-webkit-scrollbar-thumb {
            background-color: var(--muted);
            border-radius: 20px;
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
            width: 120px;
            cursor: pointer;
            color: var(--muted);
            background: var(--primary-light);
            overflow-wrap: break-word;
            display: block;
            border-bottom: 1px solid var(--secondary-light);
        }

        @media screen and (min-width: 768px) {
            .frame-btn {
                width: 95%;
            }
        }

        .frame-group-label {
            font-size: 9px;
            text-transform: uppercase;
            color: var(--muted);
            padding: 5px 10px;
            background-color: var(--secondary-light);
            display: block;
        }

        .frame-content {
            position: absolute;
            top: 0;
            bottom: 0;
            left: 140px;
            width: calc(100% - 140px);
            background-color: var(--primary-dark);
        }

        @media screen and (min-width: 768px) {
            .frame-content {
                left: 320px;
                width: calc(100% - 320px);
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
            color: var(--error);
        }
    </style>
</head>
<body>
<header class="container">
    <div class="header shadow-md">
        <div class="minor-details">
			<?php /** @var string $class */ ?>
            <label class="error-badge"><?= $class ?></label>
            <div class="versions">
				<?php /** @var string $phpVersion */ ?>
                <label class="success-badge">PHP <?= $phpVersion ?></label>
				<?php /** @var string $appVersion */ ?>
                <label class="success-badge">APP <?= $appVersion ?></label>
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
			<?php if (!empty($suggestions)): ?>
                <div class="major-details-item suggestions-container">
                    <div class="suggestion-header">
                        <label>Suggestions</label>
						<?php if (count($suggestions) > 1): ?>
                            <div class="suggestion-actions">
                                <button onclick="slideList(-400)" id="prev">Previous</button>
                                <button onclick="slideList(400)" id="next">Next</button>
                            </div>
						<?php endif ?>
                    </div>
                    <ul class="suggestion-list" id="suggestions-slider">
						<?php foreach ($suggestions as $suggestion): ?>
                            <li><?= $suggestion ?></li>
						<?php endforeach; ?>
                    </ul>
                </div>
			<?php endif ?>
        </div>
    </div>
</header>

<?php
/**
 * @var array $appFrames
 * @var array $vendorFrames
 */
?>
<div class="container">
    <div class="frame-tabs shadow-md">
        <div class="frame-sidebar">
            <label class="frame-group-label">App Frames</label>
			<?php foreach ($appFrames as $index => $frame): ?>
                <input type="radio" id="app-<?= $index ?>" onchange="showFrame()" name="frame" <?= $index === 0 ? 'checked' : '' ?>>
                <label role="button" class="frame-btn" for="app-<?= $index ?>"><?= $frame['location'] ?></label>
			<?php endforeach; ?>
            <label class="frame-group-label">Vendor Frames</label>
	        <?php foreach ($vendorFrames as $index => $frame): ?>
                <input type="radio" id="vendor-<?= $index ?>" onchange="showFrame()" name="frame">
                <label role="button" class="frame-btn" for="vendor-<?= $index ?>"><?= $frame['location'] ?></label>
	        <?php endforeach; ?>
        </div>
		<?php foreach ($appFrames as $index => $frame): ?>
            <div class="frame-tab" data-id="app-<?= $index ?>">
                <div class="frame-content">
					<?= $frame['frame'] ?>
                </div>
            </div>
		<?php endforeach; ?>
	    <?php foreach ($vendorFrames as $index => $frame): ?>
            <div class="frame-tab" data-id="vendor-<?= $index ?>">
                <div class="frame-content">
				    <?= $frame['frame'] ?>
                </div>
            </div>
	    <?php endforeach; ?>
    </div>
</div>
<script>
    showFrame();
    slideList(0);

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

    function slideList(scrollAmount) {
        const listContainer = document.getElementById('suggestions-slider');
        listContainer.scrollLeft += scrollAmount;

        updatePreviousState(listContainer);
        updateNextState(listContainer);
    }

    function updatePreviousState(listContainer) {
        const hasPrevious = listContainer.scrollLeft > 0;
        const previousBtn = document.getElementById('prev');

        if (!hasPrevious) {
            previousBtn.setAttribute('disabled', 'disabled');
        } else {
            previousBtn.removeAttribute('disabled');
        }
    }

    function updateNextState(listContainer) {
        const hasNext = listContainer.scrollLeft < (listContainer.scrollWidth - listContainer.clientWidth);
        const nextBtn = document.getElementById('next');

        if (!hasNext) {
            nextBtn.setAttribute('disabled', 'disabled');
        } else {
            nextBtn.removeAttribute('disabled');
        }
    }
</script>
</body>
</html>
