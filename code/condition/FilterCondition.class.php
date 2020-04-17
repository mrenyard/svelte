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
namespace svelte\condition;

use svelte\core\Str;

/**
 * Single representation of a verified filter for filtering \svelte\model\business\Records.
 * - restricted and evaluated by the constraints of your business model
 *  - as defined within (SVELTE_BUSINESS_MODEL_NAMESPACE)
 *
 * RESPONSIBILITIES
 * - Enforce 'equal to' operator as the primary operation.
 * - Set defaults target environment as {@link \svelte\condition\SQLEnvironment}
 * - Ensure components are restricted and evaluated by the constraints of local business model
 *    defined within SVELTE_BUSINESS_MODEL_NAMESPACE.
 *
 * COLLABORATORS
 * - {@link \svelte\condition\BusinessCondition}
 * - {@link \svelte\condition\iEnvironment}
 * - {@link \svelte\condition\SQLEnvironment} (Default)
 * - {@link \svelte\condition\Operator} (Operator::EQUAL_TO Enforced)
 */
final class FilterCondition extends BusinessCondition
{
  /**
   * Default constructor for FilterCondition.
   * @param \svelte\core\Str $record Name of business record containing property to evaluate
   * @param \svelte\core\Str $property Name of property to be evaluated
   * @param mixed $comparable Value to be compared
   * @param \svelte\condition\Operator $operator Operation to perform, default EQUAL_TO
   * @throws \DomainException When supplied arguments do NOT meet the restrictions and limits
   * as defined by your locally defined business model (SVELTE_BUSINESS_MODEL_NAMESPACE)
   */
  public function __construct(Str $record, Str $property, $comparable = null, Operator $operator = null)
  {
    parent::__construct(
      $record,
      $property,
      (isset($operator)) ? $operator : Operator::EQUAL_TO(),
      $comparable
    );
  }

  /**
   * Returns string representation of this filter based on target environment.
   * @param \svelte\condition\Environment $targetEnvironment Environment to target, default SQL.
   * @param mixed $comparable Value to be compared with attribute by operation
   * @throws \DomainException when $comparable argument does Not validate against its associated
   *  property's processValidationRules()
   * @return string Representation of *this* filter based on provided target environment
   */
  public function __invoke(iEnvironment $targetEnvironment = null, $comparable = null) : string
  {
    $targetEnvironment = (isset($targetEnvironment)) ?
      $targetEnvironment : SQLEnvironment::getInstance();

    if (isset($comparable)) { $this->set_comparable($comparable); }

    $memberAccessOperator = Operator::MEMBER_ACCESS();
    $primaryOperationOperator = $this->operator;
    $openingParenthesisOperator = Operator::OPENING_PARENTHESIS();
    $closingParenthesisOperator = Operator::CLOSING_PARENTHESIS();

    return $this->record . $memberAccessOperator($targetEnvironment) . $this->property .
      $primaryOperationOperator($targetEnvironment) . $openingParenthesisOperator($targetEnvironment) .
      $this->comparable . $closingParenthesisOperator($targetEnvironment);
  }
}
