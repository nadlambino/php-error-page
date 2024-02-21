<?php

declare(strict_types=1);

namespace Inspira\ErrorPage;

use Throwable;
use Demyanovs\PHPHighlight\Highlighter;
use Inspira\Contracts\ExceptionWithSuggestions;

/**
 * Class ExceptionHandler
 *
 * @package Inspira\ErrorPage
 */
class ExceptionHandler
{
	/**
	 * ExceptionHandler constructor.
	 *
	 * @param bool $isEnabled Whether the exception handler is enabled.
	 * @param bool $isConsole Whether the application is running in a console environment.
	 * @param int $maxFrameLines Maximum number of lines to display around the error line in a code frame.
	 * @param string $appVersion The application version.
	 */
	public function __construct(
		protected bool   $isEnabled,
		protected bool   $isConsole,
		protected int    $maxFrameLines = 15,
		protected string $appVersion = '0.0.0'
	)
	{
	}

	/**
	 * Handle the exception.
	 *
	 * @param Throwable $exception The exception to handle.
	 *
	 * @return void
	 */
	public function __invoke(Throwable $exception): void
	{
		if (ob_get_level() > 0) {
			ob_clean();
		}

		http_response_code(500);
		$stream = fopen('php://output', 'w');
		fwrite($stream, $this->getErrorMessage($exception));
		fclose($stream);
	}

	/**
	 * Get the error message to be displayed.
	 *
	 * @param Throwable $exception The exception.
	 *
	 * @return string
	 */
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
		[$app, $vendor] = $this->getSortedFrames([
			['file' => $file, 'line' => $line],
			...$exception->getTrace()
		]);

		return self::render(__DIR__ . DIRECTORY_SEPARATOR . 'index.php', [
			'message' => $exception->getMessage(),
			'code' => $exception->getCode(),
			'file' => $file,
			'line' => $line,
			'appFrames' => $app,
			'vendorFrames' => $vendor,
			'suggestions' => $exception instanceof ExceptionWithSuggestions ? $exception->getSuggestions() : [],
			'class' => get_class($exception),
			'phpVersion' => phpversion(),
			'appVersion' => $this->appVersion,
		]);
	}

	/**
	 * Get sorted frames based on application and other frames.
	 *
	 * @param array $stacks The stack frames.
	 *
	 * @return array
	 */
	private function getSortedFrames(array $stacks): array
	{
		$frames = [];
		foreach ($stacks as $stack) {
			if (!isset($stack['file']) || !isset($stack['line'])) {
				continue;
			}

			$frames[] = [
				'filename' => $file = $stack['file'],
				'frame' => $this->createCodeFrame($file, $line = $stack['line']),
				'location' => $file . ':' . $line
			];
		}

		$appFrames = [];
		$vendorFrames = [];
		foreach ($frames as $frame) {
			if (!str_contains($filename = $frame['filename'], 'public/index') && !str_contains($filename, DIRECTORY_SEPARATOR . 'vendor' . DIRECTORY_SEPARATOR)) {
				$appFrames[] = $frame;
			} else {
				$vendorFrames[] = $frame;
			}
		}

		return [$appFrames, $vendorFrames];
	}

	/**
	 * Create a code frame for the specified file and line.
	 *
	 * @param string $file The file path.
	 * @param int $line The line number.
	 *
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

			$highlighter = new Highlighter($frame, Theme::TITLE, Theme::load());
			$highlighter->showLineNumbers(false);
			$frame = str_replace("\r\r", "\r", $highlighter->parse());

			return $this->highlightErrorLine($frame, $line);
		}

		return null;
	}

	/**
	 * Highlight the error line in the code frame.
	 *
	 * @param string $frame The code frame.
	 * @param int $line The error line number.
	 *
	 * @return string
	 */
	private function highlightErrorLine(string $frame, int $line): string
	{
		$codePerLine = explode("\n", $frame);
		$red = Theme::RED;
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

	/**
	 * Render a view with given data.
	 *
	 * @param string $view The view file path.
	 * @param array $data The data to be passed to the view.
	 *
	 * @return string The rendered view content.
	 */
	private static function render(string $view, array $data = []): string
	{
		ob_start();
		extract($data);
		require $view;
		return ob_get_clean();
	}
}
