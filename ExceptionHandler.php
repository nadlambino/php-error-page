<?php

declare(strict_types=1);

namespace Inspira\Logger;

use Throwable;
use Exception;
use Demyanovs\PHPHighlight\Highlighter;
use Demyanovs\PHPHighlight\Themes\ObsidianTheme;
use Inspira\Contracts\ExceptionWithSuggestions;
use Psr\Http\Message\ResponseInterface;

class ExceptionHandler
{
	public function __construct(
		protected ResponseInterface $response,
		protected bool $isEnabled,
		protected bool $isConsole,
		protected int $maxSnapshotLine = 5
	) { }

	/**
	 * @param Throwable $exception
	 * @return void
	 */
	public function __invoke(Throwable $exception): void
	{
		ob_clean();
		http_response_code(500);
		$this->response->getBody()->write($this->getErrorMessage($exception));
	}

	/**
	 * @param array $stacks
	 * @return array
	 */
	private function getSortedSnapshots(array $stacks): array
	{
		$snapshots = [];
		foreach ($stacks as $stack) {
			if (!isset($stack['file']) || !isset($stack['line'])) {
				continue;
			}

			$snapshots[$stack['file']] = $this->createCodeSnapshot($stack['file'], $stack['line']);
		}

		$appSnapshots = [];
		$otherSnapshots = [];
		foreach ($snapshots as $filename => $contents) {
			if (!str_contains($filename, 'public/index') && !str_contains($filename, 'vendor')) {
				$appSnapshots[] = $contents;
			} else {
				$otherSnapshots[] = $contents;
			}
		}

		return array_values([...$appSnapshots, ...$otherSnapshots]);
	}

	/**
	 * @param string $file
	 * @param int $line
	 * @return string|null
	 */
	private function createCodeSnapshot(string $file, int $line): ?string
	{
		if (file_exists($file)) {
			$lines = file($file);
			$start = max(1, $line - $this->maxSnapshotLine);
			$end = min(count($lines), $line + $this->maxSnapshotLine);
			$errorLines = array_slice($lines, $start - 1, $end - $start + 1, true);
			$codeblock = null;

			foreach ($errorLines as $num => $code) {
				$codeblock .= "$num   $code";
			}

			$line -= 1;
			$snapshot = "<pre data-file='$file on line $line' data-lang='php'>";
			$snapshot .= $codeblock;
			$snapshot .= "</pre>";

			$highlighter = new Highlighter($snapshot, ObsidianTheme::TITLE);
			$highlighter->showLineNumbers(false);

			return $highlighter->parse();
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
		$snapshots = $this->getSortedSnapshots([
			['file' => $file, 'line' => $line],
			...$exception->getTrace()
		]);

		$file = dirname(__DIR__) . DIRECTORY_SEPARATOR . 'index';

		return self::render($file, [
			'message'       => $exception->getMessage(),
			'code'          => $exception->getCode(),
			'file'          => $file,
			'line'          => $line - 1,
			'snapshots'     => $snapshots,
			'solutions'     => $exception instanceof ExceptionWithSuggestions ? $exception->getSuggestions() : [],
			'isException'   => $exception instanceof Exception,
			'class'         => get_class($exception)
		]);
	}

	private static function render(string $file, array $data = []): string
	{
		extract($data);
		ob_start();
		require $file;
		return ob_get_clean();
	}
}
