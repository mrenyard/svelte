<?php
/**
 * Svelte - Rapid web application development enviroment for building
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
 * @package svelte
 * @version 0.0.9;
 */
namespace svelte\core;

/**
 * Strongly typed Object used universally within Svelte.
 *
 * RESPONSIBILITIES
 *
 * - Act as superclass for all *Object*s (everything can cast to *SvelteObject*).
 *
 * - Define C# type *get* access to properties by passing
 * `$object->aProperty;` to `$object->get_aProperty();`
 *
 * - Define C# type *set* access to properties by passing
 * `$object->aProperty = $value;` to `$object->set_aProperty($value);`
 *
 * - Define default `_toString()` method.
 */
abstract class SvelteObject {

  /**
   * Allows C# type access to properties.
   * **DO NOT CALL THIS METHOD DIRECTLY, TO BE HANDLED INTERNALLY!**
   *
   * **Passes:** `$object->aProperty;` **to:** `$object->get_aProperty();`
   *
   * Implementation in concrete Object
   * ```php
   * private aProperty;
   *
   * protected function get_aProperty()
   * {
   *   return $this->aProperty;
   * }
   * ```
   *
   * Called externally (C# style)
   * ```php
   * $object->aProperty; // Get value 'aProperty'
   * ```
   *
   * @param string $propertyName Name of property (handled internally)
   * @return mixed|void The value of requested property
   * @throws \svelte\core\BadPropertyCallException Undefined or inaccessible property called
   */
  public function __get($propertyName)
  {
    if (!method_exists($this, ($method = 'get_'.$propertyName))) {
      throw new BadPropertyCallException(
        'Unable to locate '.$propertyName.' of \''.get_class($this).'\''
      );
    }
    return $this->$method();
  }

  /**
   * Allows C# type access to properties.
   * **DO NOT CALL THIS METHOD DIRECTLY, TO BE HANDLED INTERNALLY!**
   *
   * **Passes:** `$object->aProperty = $value;` **to:** `$object->set_aProperty($value);`
   *
   * Implementation in concrete Object
   * ```php
   * private aProperty;
   *
   * protected function set_aProperty($value)
   * {
   *   $this->aProperty = $value;
   * }
   * ```
   *
   * Called externally (C# style)
   * ```php
   * $object->aProperty = $value; //Set value of 'aProperty'
   * ```
   *
   * @param string $propertyName Name of property (handled internally)
   * @param mixed $propertyValue The value to set on requested property (handled internally)
   * @throws \svelte\core\PropertyNotSetException Unable to set property when undefined or inaccessible
   */
  public function __set($propertyName, $propertyValue)
  {
    if (!method_exists($this, ($method = 'set_'.$propertyName))) {
      throw new PropertyNotSetException(
        get_class($this).'->'.$propertyName.' is NOT settable'
      );
    }
    $this->$method($propertyValue);
  }

  /**
   * Returns this 'class name' for screen output.
   * @return string name of class.
   */
  public function __toString()
  {
    return get_class($this);
  }
}
