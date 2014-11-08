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

namespace PSX\Html\Filter\Collection;

use PSX\Html\Filter;

/**
 * Html5TextTest
 *
 * @author  Christoph Kappestein <k42b3.x@gmail.com>
 * @license http://www.gnu.org/licenses/gpl.html GPLv3
 * @link    http://phpsx.org
 */
class Html5TextTest extends \PHPUnit_Framework_TestCase
{
	public function testNormalHtml()
	{
		$html = <<<HTML
<p>foobar</p>
<ul>
	<li>lorem ipsum</li>
	<li>lorem </li>
</ul>
HTML;

		$expected = <<<HTML
<p>foobar</p>
<ul>
	<li>lorem ipsum</li>
	<li>lorem </li>
</ul>
HTML;

		$filter = new Filter($html, new Html5Text());

		$this->assertEquals($expected, $filter->filter());
	}

	public function testInlineJavascript()
	{
		$html = <<<HTML
<div id="fadeOut" style="display:none;">some content</div>
<script type="text/javascript">
$(document).ready(function(){
	$('#fadeOut').fadeIn();
});
</script>
HTML;

		$expected = <<<HTML


HTML;

		$filter = new Filter($html, new Html5Text());

		$this->assertEquals($expected, $filter->filter());
	}
}
