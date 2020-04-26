<?php
/**
 * Testing - Svelte - Rapid web application development using best practice.
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
namespace svelte\model\business;

use svelte\core\Str;
use svelte\model\business\BusinessModel;

/**
 * Mock Concreate implementation of \svelte\model\business\BusinessModel for testing against.
 */
class Record extends BusinessModel
{
  public static $setPropertyValueCount = 0;
  public static $getPropertyValueCount = 0;

  public static function reset()
  {
    self::$setPropertyValueCount = 0;
    self::$getPropertyValueCount = 0;
  }

  public function get_id() : Str
  {
    return Str::set('record:new');
  }

  protected function get_aProperty()
  {
  }

  public function getPropertyValue(string $propertyName)
  {
    self::$getPropertyValueCount++;
    return 'VALUE';
  }

  public function setPropertyValue(string $propertyName, $value)
  {
    self::$setPropertyValueCount++;
  }
}
