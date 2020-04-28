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
namespace svelte\model\business\field;

use svelte\core\Str;
use svelte\core\OptionList;
use svelte\model\business\Record;
use svelte\validation\FailedValidationException;

/**
 * Specilised field for selecting one from a collection of iOptions tied to a single property of
 * its containing \svelte\model\business\Record.
 *
 * RESPONSIBILITIES
 * - Implement property specific methods for iteration, validity checking & error reporting
 * - Implement template method, processValidationRule to validate against avalible iOptions.
 * - Hold referance back to its contining Record
 *
 * COLLABORATORS
 * - {@link \svelte\model\business\Record}
 * - {@link \svelte\core\OptionList}
 */
final class SelectMany extends Field
{
  /**
   * Creates select one field type, tied to a single property of containing record.
   * @param \svelte\core\Str $propertyName Property name of related property of containing record
   * @param \svelte\model\business\Record $containingRecord Record parent of *this* property
   * @param \svelte\core\OptionList $options Collection of avalible iOptions
   * proir to allowing property value change
   */
  public function __construct(Str $propertyName, Record $containingRecord, OptionList $options)
  {
    parent::__construct($propertyName, $containingRecord, $options);
  }

  /**
   * Validate that value is one of avalible options.
   * @param mixed $value Value to be processed
   * @throws \svelte\validation\FailedValidationException When test fails.
   */
  protected function processValidationRule($value)
  {
    if (!is_array($value)) {
      throw new \BadMethodCallException('$value parameter must be an array');
    }
    foreach ($value as $selected) {
      $valid = FALSE;
      foreach ($this as $option) {
        if ($selected == $option->id) {
          $valid = TRUE;
          break;
        }
      }
      if (!$valid) {
        throw new FailedValidationException(
          'At least one selected value is NOT an available option!'
        );
      }
    }
  }
}
