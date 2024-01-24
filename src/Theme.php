<?php

declare(strict_types=1);

namespace Inspira\ErrorPage;

use Demyanovs\PHPHighlight\Themes\Dto\DefaultColorSchemaDto;
use Demyanovs\PHPHighlight\Themes\Dto\PHPColorSchemaDto;
use Demyanovs\PHPHighlight\Themes\Dto\XMLColorSchemaDto;
use Demyanovs\PHPHighlight\Themes\Theme as HighlighterTheme;

/**
 * Class CustomTheme
 *
 * @package Inspira\ErrorPage
 */
class Theme
{
	public const TITLE = 'theme';

	public const RED = '#7D131350';

	/**
	 * Load the custom theme.
	 *
	 * @return array An array containing the custom theme.
	 */
	public static function load(): array
	{
		$defaultColorSchemaDto = new DefaultColorSchemaDto(
			'#e0e2e4;',
			'#282b2e',
			'#818e96;',
			'#93c763; font-weight: bold;',
			'#e6e1dc',
			'#e0e2e4;',
			'#ec7600;',
		);

		$PHPColorSchemaDto = new PHPColorSchemaDto(
			'#268bd2;', // blue
			'#4f5a68;', // gray
			'#cb4b16;', // orange
			'#6c71c4;', // violet
			'#b4d273;', // green
		);

		$XMLColorSchemaDto = new XMLColorSchemaDto(
			'#8cbbad; font-weight: bold;',
			'#6d9cbe;',
			'#ec7600;',
			'#557182;',
		);

		return [new HighlighterTheme(self::TITLE, $defaultColorSchemaDto, $PHPColorSchemaDto, $XMLColorSchemaDto)];
	}
}
