<?php
/**
 * Testing - Svelte - Rapid web application development enviroment for building
 *  flexible, customisable web systems.
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
namespace tests\svelte\model\business\validation;

use svelte\core\SvelteObject;
use svelte\model\business\validation\dbtype\DbTypeValidation;

/**
 * Concreate implementation of \svelte\model\business\validation\DbTypeValidation for testing against.
 * .
 */
class MockDbTypeValidation extends DbTypeValidation
{
  public static $testCallCount = 0;

  public static function reset()
  {
    self::$testCallCount = 0;
  }

  /**
   * {@inheritdoc }
   */
  protected function test($value)
  {
    self::$testCallCount++;
  }
}
