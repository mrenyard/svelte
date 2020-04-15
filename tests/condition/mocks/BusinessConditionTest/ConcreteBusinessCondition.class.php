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
namespace tests\svelte\condition\mocks\BusinessConditionTest;

use svelte\condition\BusinessCondition;
use svelte\condition\iEnvironment;

/**
 * Concreate implementation of \svelte\condition\BusinessCondition for testing against.
 * .
 */
class ConcreteBusinessCondition extends BusinessCondition
{
  /**
   * Stub method returns empty string ('').
   * @param \svelte\condition\iEnvironment $targetEnvironment Environment to target.
   * @param mixed $comparable Value to be compared with attribute by operation.
   * @return string returns empty string ('')
   */
  public function __invoke(iEnvironment $targetEnvironment = null, $comparable = null) : string
  {
    return '';
  }
}
