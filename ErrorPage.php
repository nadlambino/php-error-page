<?php

declare(strict_types=1);

namespace Inspira\ErrorPage;

class ErrorPage
{
	public function __construct(private bool $isEnabled = true, private bool $isConsole = false, private int $maxSnapShotLine = 5) { }
	
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
		set_exception_handler(new ExceptionHandler($this->isEnabled, $this->isConsole, $this->maxSnapShotLine));
		set_error_handler(new ErrorHandler(), E_ALL);
	}
}
