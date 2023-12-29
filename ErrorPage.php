<?php

declare(strict_types=1);

namespace Inspira\ErrorPage;

use Psr\Http\Message\ResponseInterface;

class ErrorPage
{
	private bool $isEnabled = true;

	private bool $isConsole = false;

	public function __construct(protected ResponseInterface $response) { }
	
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
		set_exception_handler(new ExceptionHandler($this->response, $this->isEnabled, $this->isConsole));
		set_error_handler(new ErrorHandler(), E_ALL);
	}
}
