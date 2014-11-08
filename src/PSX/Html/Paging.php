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

use Countable;
use Iterator;
use PSX\Html\Paging\Page;

/**
 * Paging
 *
 * @author  Christoph Kappestein <k42b3.x@gmail.com>
 * @license http://www.gnu.org/licenses/gpl.html GPLv3
 * @link    http://phpsx.org
 */
class Paging implements Countable, Iterator
{
	protected $totalResults;
	protected $itemsPerPage;
	protected $startIndex;
	protected $range;

	protected $page;
	protected $pages;
	protected $first;
	protected $prev;
	protected $min;
	protected $max;
	protected $next;
	protected $last;

	private $nav   = array();
	private $itPos = -1;

	public function __construct($totalResults, $itemsPerPage, $startIndex, $range = 2)
	{
		$this->totalResults = $totalResults;
		$this->itemsPerPage = $itemsPerPage;
		$this->startIndex   = $startIndex;
		$this->range        = $range;

		// do the math
		$this->calculatePages();
	}

	public function getRange()
	{
		return $this->range;
	}

	public function getPage()
	{
		return $this->page + 1;
	}

	public function getPages()
	{
		return $this->pages + 1;
	}

	public function getFirst()
	{
		return $this->first;
	}

	public function getPrev()
	{
		return $this->prev;
	}

	public function getMin()
	{
		return $this->min;
	}

	public function getMax()
	{
		return $this->max;
	}

	public function getNext()
	{
		return $this->next;
	}

	public function getLast()
	{
		return $this->last;
	}

	// Countable
	public function count()
	{
		return $this->getPages();
	}

	// Iterator
	public function current()
	{
		return new Page($this->itPos + 1, $this->itPos == $this->page);
	}

	public function key()
	{
		return $this->itPos;
	}

	public function next()
	{
		$this->itPos++;
	}

	public function rewind()
	{
		$this->itPos = $this->min;
	}

	public function valid()
	{
		return $this->pages > 0 && $this->itPos >= $this->min && $this->itPos <= $this->max;
	}

	protected function calculatePages()
	{
		$this->pages = $this->totalResults > 0 && $this->totalResults > $this->itemsPerPage ? ceil($this->totalResults / $this->itemsPerPage) - 1 : 0;
		$this->page  = $this->startIndex >= 0 ? intval($this->startIndex / $this->itemsPerPage) : 0;
		$this->page  = $this->page < 0 ? 0 : $this->page; # lower limit
		$this->page  = $this->page > $this->pages ? $this->pages : $this->page; # upper limit

		$this->first = 0;
		$this->prev  = $this->page > 1 ? $this->page - 1 : 0;
		$this->next  = $this->page < $this->pages ? $this->page + 1 : $this->pages;
		$this->last  = $this->pages;

		$this->min   = $this->page - $this->range < 0 ? 0 : $this->page - $this->range;
		$this->max   = $this->page + $this->range > $this->pages ? $this->pages : $this->page + $this->range;
	}
}


