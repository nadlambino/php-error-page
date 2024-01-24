<?php

declare(strict_types=1);

namespace Inspira\ErrorPage;

class ErrorPage
{
	private string $version;

	public function __construct(private bool $isEnabled = true, private bool $isConsole = false, private int $maxSnapShotLine = 15, private string $appVersion = '0.0.0')
	{
		$composer = json_decode(file_get_contents('./../composer.json'), true);
		$this->version = $composer['version'] ?? '0.0.0';
	}
	
	public function isEnabled(bool $isEnabled): self
	{
		$this->isEnabled = $isEnabled;

		return $this;
	}

	public function isRunningOnConsole(bool $isConsole): self
	{
		$this->isConsole = $isConsole;

		return $this;
	}

	public function register(): void
	{
		set_exception_handler(new ExceptionHandler($this->isEnabled, $this->isConsole, $this->maxSnapShotLine, $this->appVersion, $this->version));
		set_error_handler(new ErrorHandler(), E_ALL);
	}
}
