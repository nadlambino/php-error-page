<?php

declare(strict_types=1);

namespace Inspira\ErrorPage;

use ErrorException;

/**
 * Class ErrorHandler
 *
 * @package Inspira\ErrorPage
 */
class ErrorHandler
{
	/**
	 * Handle PHP errors by throwing an ErrorException.
	 *
	 * @param int $no The error number.
	 * @param string $message The error message.
	 * @param string $file The file where the error occurred.
	 * @param int $line The line number where the error occurred.
	 *
	 * @throws ErrorException
	 */
	public function __invoke(int $no, string $message, string $file, int $line)
	{
		throw new ErrorException($message, 0, $no, $file, $line);
	}
}
