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

namespace PSX\Html;

use PSX\Html\Lexer\Token\Comment;
use PSX\Html\Lexer\Token\Element;
use PSX\Html\Lexer\Token\Text;

/**
 * FilterTest
 *
 * @author  Christoph Kappestein <k42b3.x@gmail.com>
 * @license http://www.gnu.org/licenses/gpl.html GPLv3
 * @link    http://phpsx.org
 */
class FilterTest extends \PHPUnit_Framework_TestCase
{
	public function testFilterNormal()
	{
		$html = <<<HTML
<p>sdfsdfsdf</p>
HTML;

		$expected = <<<HTML
<p>sdfsdfsdf</p>
HTML;

		$filter = new Filter($html);

		$this->assertEquals($expected, $filter->filter());
	}

	public function testFilterFormat()
	{
		$html = <<<HTML
<p>foobar</p>
<ul>
	<li>lorem ipsum</li>
	<li>lorem <h1>ipsum</h1></li>
</ul>
HTML;

		$expected = <<<HTML
<p>foobar</p>
<ul>
	<li>lorem ipsum</li>
	<li>lorem </li>
</ul>
HTML;

		$filter = new Filter($html);

		$this->assertEquals($expected, $filter->filter());
	}

	public function testFilterComments()
	{
		$html = <<<HTML
<p>foobar</p>
<!-- bar -->
HTML;

		$expected = <<<HTML
<p>foobar</p>

HTML;

		$filter = new Filter($html);

		$this->assertEquals($expected, $filter->filter());
	}

	public function testFilterAllowComments()
	{
		$html = <<<HTML
<p>foobar</p>
<!-- bar -->
HTML;

		$expected = <<<HTML
<p>foobar</p>
<!-- bar -->
HTML;

		$filter = new Filter($html);
		$filter->setAllowComments(true);

		$this->assertEquals($expected, $filter->filter());
	}

	public function testFilterListener()
	{
		$elementListener = $this->getMockBuilder('PSX\Html\Filter\ElementListenerInterface')
			->getMock();

		$textListener = $this->getMockBuilder('PSX\Html\Filter\TextListenerInterface')
			->getMock();

		$commentListener = $this->getMockBuilder('PSX\Html\Filter\CommentListenerInterface')
			->getMock();

		$elementListener->expects($this->once())
			->method('onElement')
			->with($this->callback(function($element){
				return $element instanceof Element && $element->getName() == 'p';
			}));

		$textListener->expects($this->once())
			->method('onText')
			->with($this->callback(function($element){
				return $element instanceof Text && $element->getData() == 'foobar';
			}));

		$commentListener->expects($this->once())
			->method('onComment')
			->with($this->callback(function($element){
				return $element instanceof Comment && $element->getData() == '<!-- bar -->';
			}));

		$html = <<<HTML
<p>foobar</p>
<!-- bar -->
HTML;

		$expected = <<<HTML
<p>foobar</p>
<!-- bar -->
HTML;

		$filter = new Filter($html);
		$filter->addElementListener($elementListener);
		$filter->addTextListener($textListener);
		$filter->addCommentListener($commentListener);
		$filter->setAllowComments(true);

		$this->assertEquals($expected, $filter->filter());
	}
}