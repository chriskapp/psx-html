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

namespace PSX\Html\Lexer;

use PSX\Html\Lexer;
use PSX\Html\Lexer\TokenAbstract;
use PSX\Html\Lexer\Token\Element;

/**
 * Dom
 *
 * @author  Christoph Kappestein <k42b3.x@gmail.com>
 * @license http://www.gnu.org/licenses/gpl.html GPLv3
 * @link    http://phpsx.org
 */
class Dom
{
	private $root;
	private $stack = array();

	public function push(TokenAbstract $token)
	{
		$topToken = $this->getTopToken();

		if($topToken !== false)
		{
			if($token instanceof Element)
			{
				if($token->getType() == TokenAbstract::TYPE_ELEMENT_START)
				{
					if(!$token->isShort() && !in_array($token->getName(), Lexer::$voidTags))
					{
						array_push($this->stack, $token);
					}

					$topToken->appendChild($token);
				}
				else if($token->getType() == TokenAbstract::TYPE_ELEMENT_END)
				{
					if(in_array($token->getName(), Lexer::$voidTags))
					{
						// if we have an close tag from an void element ignore 
						// it
					}
					else if($topToken->getName() == $token->getName())
					{
						array_pop($this->stack);
					}
				}
			}
			else
			{
				$topToken->appendChild($token);
			}
		}
		else
		{
			// if the stack is empty add element as root
			if($token instanceof Element)
			{
				array_push($this->stack, $this->root = $token);
			}
		}
	}

	public function getStack()
	{
		return $this->stack;
	}

	public function getTopToken()
	{
		return end($this->stack);
	}

	public function getRootElement()
	{
		return $this->root;
	}
}

