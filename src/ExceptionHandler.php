<?php

declare(strict_types=1);

namespace Inspira\ErrorPage;

use Throwable;
use Demyanovs\PHPHighlight\Highlighter;
use Inspira\Contracts\ExceptionWithSuggestions;

class ExceptionHandler
{
	public function __construct(
		protected bool $isEnabled,
		protected bool $isConsole,
		protected int $maxFrameLines = 15,
		protected string $appVersion = '0.0.0',
		protected string $version = '0.0.0'
	) { }

	/**
	 * @param Throwable $exception
	 * @return void
	 */
	public function __invoke(Throwable $exception): void
	{
		ob_clean();
		http_response_code(500);
		$stream = fopen('php://output', 'w');
		fwrite($stream, $this->getErrorMessage($exception));
		fclose($stream);
	}

	/**
	 * @param array $stacks
	 * @return array
	 */
	private function getSortedFrames(array $stacks): array
	{
		$frames = [];
		foreach ($stacks as $stack) {
			if (!isset($stack['file']) || !isset($stack['line'])) {
				continue;
			}

			$frames[$file = $stack['file']] = [
				'frame' => $this->createCodeFrame($file, $line = $stack['line']),
				'location' => $file . ':' . $line
			];
		}

		$appFrames = [];
		$otherFrames = [];
		foreach ($frames as $filename => $contents) {
			if (!str_contains($filename, 'public/index') && !str_contains($filename, 'vendor')) {
				$appFrames[$filename] = $contents;
			} else {
				$otherFrames[$filename] = $contents;
			}
		}

		return [...$appFrames, ...$otherFrames];
	}

	/**
	 * @param string $file
	 * @param int $line
	 * @return string|null
	 */
	private function createCodeFrame(string $file, int $line): ?string
	{
		if (file_exists($file)) {
			$lines = file($file);
			$start = max(1, $line - $this->maxFrameLines);
			$end = min(count($lines), $line + $this->maxFrameLines);
			$errorLines = array_slice($lines, $start - 1, $end - $start + 1, true);
			$codeblock = null;

			foreach ($errorLines as $num => $code) {
				$lineNumber = $num + 1;
				$codeblock .= "$lineNumber   $code";
			}

			$frame = "<pre data-file='$file:$line' data-lang='php'>";
			$frame .= $codeblock;
			$frame .= "</pre>";

			$highlighter = new Highlighter($frame, CustomTheme::TITLE, CustomTheme::load());
			$highlighter->showLineNumbers(false);
			$frame = str_replace("\r\r", "\r", $highlighter->parse());

			return $this->highlightErrorLine($frame, $line);
		}

		return null;
	}

	/**
	 * Highlight the error line where the error occurred.
	 *
	 * @param string $frame The codeblock frame.
	 * @param int $line The error line.
	 * @return string
	 */
	private function highlightErrorLine(string $frame, int $line): string
	{
		$codePerLine = explode("\n", $frame);
		$red = CustomTheme::RED;
		$code = array_map(function ($code) use ($line, $red) {
			$lineNumberOnFirstLinePattern = '/<span (.*?)>' . $line . '/';
			$lineNumberOnNewLinePattern = '/' . $line . '(.*?)&nbsp;/';
			if (preg_match($lineNumberOnFirstLinePattern, $code) || preg_match($lineNumberOnNewLinePattern, $code)) {
				$code = "<div style='display: inline-table; width: 100%; background-color: $red'>$code</div>";
			}

			return $code;
		}, $codePerLine);

		return implode("\n", $code);
	}

	private function getErrorMessage(Throwable $exception): string
	{
		if (!$this->isEnabled) {
			return $this->isConsole
				? 'Something went wrong with the application. Enable APP_DEBUG in .env file to see error message.' . PHP_EOL
				: '';
		}

		if ($this->isConsole) {
			return $exception->getMessage() . PHP_EOL;
		}

		$file = $exception->getFile();
		$line = $exception->getLine();
		$frames = $this->getSortedFrames([
			['file' => $file, 'line' => $line],
			...$exception->getTrace()
		]);

		return self::render(__DIR__ . DIRECTORY_SEPARATOR . 'index.php', [
			'message'        => $exception->getMessage(),
			'code'           => $exception->getCode(),
			'file'           => $file,
			'line'           => $line,
			'frames'         => $frames,
			'solutions'      => $exception instanceof ExceptionWithSuggestions ? $exception->getSuggestions() : [],
			'class'          => get_class($exception),
			'phpVersion'     => phpversion(),
			'appVersion'     => $this->appVersion,
			'packageVersion' => $this->version
		]);
	}

	private static function render(string $view, array $data = []): string
	{
		extract($data);
		ob_start();
		require $view;
		return ob_get_clean();
	}
}
