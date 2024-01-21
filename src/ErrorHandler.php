<?php

declare(strict_types=1);

namespace Inspira\ErrorPage;

use ErrorException;

class ErrorHandler
{
	/**
	 * @throws ErrorException
	 */
	public function __invoke(int $no, string $message, string $file, int $line)
	{
		throw new ErrorException($message, 0, $no, $file, $line);
	}
}
