<?php
/*
 * psx
 * A object oriented and modular based PHP framework for developing
 * dynamic web applications. For the current version and informations
 * visit <http://phpsx.org>
 *
 * Copyright (c) 2010-2014 Christoph Kappestein <k42b3.x@gmail.com>
 *
 * This file is part of psx. psx is free software: you can
 * redistribute it and/or modify it under the terms of the
 * GNU General Public License as published by the Free Software
 * Foundation, either version 3 of the License, or any later version.
 *
 * psx is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with psx. If not, see <http://www.gnu.org/licenses/>.
 */

namespace PSX\Html\Lexer\Token;

use PSX\Html\Lexer\TokenAbstract;

/**
 * TextTest
 *
 * @author  Christoph Kappestein <k42b3.x@gmail.com>
 * @license http://www.gnu.org/licenses/gpl.html GPLv3
 * @link    http://phpsx.org
 */
class TextTest extends \PHPUnit_Framework_TestCase
{
	public function testParse()
	{
		$text = Text::parse('foo');

		$this->assertInstanceOf('PSX\Html\Lexer\Token\Text', $text);
		$this->assertEquals(TokenAbstract::TYPE_TEXT, $text->getType());
		$this->assertEquals('#PCDATA', $text->getName());
		$this->assertEquals('foo', $text->getData());
	}
}
