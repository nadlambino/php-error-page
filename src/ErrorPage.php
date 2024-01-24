<?php

declare(strict_types=1);

namespace Inspira\ErrorPage;

use Exception;

/**
 * Class ErrorPage
 *
 * @package Inspira\ErrorPage
 */
class ErrorPage
{
	/**
	 * ErrorPage constructor.
	 *
	 * @param bool $isEnabled Whether the error page is enabled.
	 * @param bool $isConsole Whether the application is running in a console environment.
	 * @param int $maxSnapShotLine Maximum number of lines to display in a code snapshot.
	 * @param string|null $version The application version.
	 */
	public function __construct(
		private bool    $isEnabled = true,
		private bool    $isConsole = false,
		private int     $maxSnapShotLine = 15,
		private ?string $version = null
	)
	{
		try {
			$composer = json_decode(file_get_contents(dirname(__DIR__) . '/composer.json'), true);
			$this->version ??= $composer['version'] ?? '0.0.0';
		} catch (Exception) {
		}
	}

	/**
	 * Enable or disable the error page.
	 *
	 * @param bool $isEnabled Whether the error page is enabled.
	 *
	 * @return $this
	 */
	public function isEnabled(bool $isEnabled): self
	{
		$this->isEnabled = $isEnabled;

		return $this;
	}

	/**
	 * Set whether the application is running in a console environment.
	 *
	 * @param bool $isConsole Whether the application is running in a console environment.
	 *
	 * @return $this
	 */
	public function isRunningOnConsole(bool $isConsole): self
	{
		$this->isConsole = $isConsole;

		return $this;
	}

	/**
	 * Register the error and exception handlers.
	 *
	 * @return void
	 */
	public function register(): void
	{
		set_exception_handler(new ExceptionHandler($this->isEnabled, $this->isConsole, $this->maxSnapShotLine, $this->version));
		set_error_handler(new ErrorHandler(), E_ALL);
	}
}
