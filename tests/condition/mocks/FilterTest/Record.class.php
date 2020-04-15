<?php
/**
 * Svelte - Rapid web application development using best practice.
 *
 * This program is free software; you can redistribute it and/or modify it under the terms of the
 * GNU General Public License as published by the Free Software Foundation; either version 2 of
 * the License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY;
 * without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 * See the GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along with this program; if
 * not, write to the Free Software Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston,
 * MA 02110-1301, USA.
 *
 * @author Matt Renyard (renyard.m@gmail.com)
 * @version 0.0.9;
 */
namespace tests\svelte\condition\mocks\FilterTest;

use svelte\core\SvelteObject;

/**
 * Mock business record with property.
 * .
 */
class Record extends SvelteObject
{
  private $propertyA;
  private $propertyB;
  private $propertyC;
  private $propertyInt;

  /**
   * Test getter for Record::propertyA
   */
  protected function get_propertyA()
  {
    if (!isset($this->propertyA)) {
      $this->propertyA = new Field();
    }
    return $this->propertyA;
  }

  /**
   * Test getter for Record::propertyB
   */
  protected function get_propertyB()
  {
    if (!isset($this->propertyB)) {
      $this->propertyB = new Field();
    }
    return $this->propertyB;
  }

  /**
   * Test getter for Record::propertyC
   */
  protected function get_propertyC()
  {
    if (!isset($this->propertyC)) {
      $this->propertyC = new Field();
    }
    return $this->propertyC;
  }

  /**
   * Test getter for Record::propertyInt
   */
  protected function get_propertyInt()
  {
    if (!isset($this->propertyInt)) {
      $this->propertyInt = new Field();
    }
    return $this->propertyInt;
  }
}
