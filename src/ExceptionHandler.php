<?php

declare(strict_types=1);

namespace Inspira\ErrorPage;

use Throwable;
use Demyanovs\PHPHighlight\Highlighter;
use Demyanovs\PHPHighlight\Themes\ObsidianTheme;
use Inspira\Contracts\ExceptionWithSuggestions;

class ExceptionHandler
{
	public function __construct(
		protected bool $isEnabled,
		protected bool $isConsole,
		protected int $maxFrameLines = 5
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

			$frames[$stack['file']] = $this->createCodeFrame($stack['file'], $stack['line']);
		}

		$appFrames = [];
		$otherFrames = [];
		foreach ($frames as $filename => $contents) {
			if (!str_contains($filename, 'public/index') && !str_contains($filename, 'vendor')) {
				$appFrames[] = $contents;
			} else {
				$otherFrames[] = $contents;
			}
		}

		return array_values([...$appFrames, ...$otherFrames]);
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

			$frame = "<pre data-file='$file on line $line' data-lang='php'>";
			$frame .= $codeblock;
			$frame .= "</pre>";

			$highlighter = new Highlighter($frame, ObsidianTheme::TITLE);
			$highlighter->showLineNumbers(false);
			$parsed = $highlighter->parse();

			return str_replace("\r\r", "\r", $parsed);
		}

		return null;
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
			'message'       => $exception->getMessage(),
			'code'          => $exception->getCode(),
			'file'          => $file,
			'line'          => $line,
			'frames'        => $frames,
			'solutions'     => $exception instanceof ExceptionWithSuggestions ? $exception->getSuggestions() : [],
			'class'         => get_class($exception),
			'phpVersion'    => phpversion()
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
