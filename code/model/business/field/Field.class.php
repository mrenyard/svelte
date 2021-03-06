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
use svelte\core\iCollection;
use svelte\core\Collection;
use svelte\condition\PostData;
use svelte\model\business\BusinessModel;
use svelte\model\business\Record;
use svelte\model\business\FailedValidationException;

/**
 * Abstract field related to a single property of its containing \svelte\model\business\Record.
 *
 * RESPONSIBILITIES
 * - Implement property specific methods for iteration, validity checking & error reporting
 * - Define template method, processValidationRule
 * - Hold referance back to its contining Record
 *
 * COLLABORATORS
 * - {@link \svelte\model\business\Record}
 *
 * @property-read \svelte\core\Str $id Returns unique identifier (id) for *this* (URN).
 * @property-read mixed $value Returns value held by relevant property of containing record.
 * @property-read \svelte\model\business\Record $containingRecord Record containing property related to *this*.
 */
abstract class Field extends BusinessModel
{
  protected $dataObjectPropertyName;
  protected $containingRecord;

  /**
   * Base constructor for Field related to a single property of containing record.
   * @param \svelte\core\Str $dataObjectPropertyName Related dataObject property name of containing record
   * @param \svelte\model\business\Record $containingRecord Record parent of *this* property
   * @param \svelte\core\iCollection $children Collection of child business models.
   */
  public function __construct(Str $dataObjectPropertyName, Record $containingRecord, iCollection $children = null)
  {
    $this->containingRecord = $containingRecord;
    $this->dataObjectPropertyName = $dataObjectPropertyName;
    parent::__construct($children);
  }

  /**
   * Get ID (URN)
   * **DO NOT CALL DIRECTLY, USE this->id;**
   * @return \svelte\core\Str Unique identifier for *this*
   */
  final public function get_id() : Str
  {
    return Str::COLON()->prepend(
      $this->containingRecord->id
    )->append(
      Str::hyphenate($this->dataObjectPropertyName)
    );
  }

  /**
   * Returns value held by relevant property of containing record.
   * @return mixed Value held by relevant property of containing record
   */
  abstract protected function get_value();

  /**
   * Get containing record
   * **DO NOT CALL DIRECTLY, USE this->containingRecord;**
   * @return \svelte\model\business\Record Containing record of *this*
   */
  final public function get_containingRecord() : Record
  {
    return $this->containingRecord;
  }

  /**
   * Validate postdata against this and update accordingly.
   * @param \svelte\condition\PostData $postdata Collection of InputDataCondition\s
   *  to be assessed for validity and imposed on *this* business model.
   */
  public function validate(PostData $postdata)
  {
    $this->errorCollection = new Collection(Str::set('\svelte\core\Str'));
    foreach ($postdata as $inputdata)
    {
      if ($inputdata->attributeURN == $this->id)
      {
        try {
          $this->processValidationRule($inputdata->value);
        } catch (FailedValidationException $e) {
          $this->errorCollection->add(Str::set($e->getMessage()));
          return;
        }
        $this->containingRecord->setPropertyValue(
          (string)$this->dataObjectPropertyName, $inputdata->value
        );
      }
    }
  }

  /**
   * Checks if any errors have been recorded following validate().
   * **DO NOT CALL DIRECTLY, USE this->hasErrors;**
   * @return bool True if an error has been recorded
   */
  final protected function get_hasErrors() : bool
  {
    if (isset($this->errorCollection) && $this->errorCollection->count > 0) { return TRUE; }
    return FALSE;
  }

  /**
   * Gets collection of recorded errors.
   * **DO NOT CALL DIRECTLY, USE this->errors;**
   * @return iCollection List of recorded errors.
   */
  final public function get_errors() : iCollection
  {
    return (isset($this->errorCollection)) ? $this->errorCollection :
      new Collection(Str::set('\svelte\core\Str'));
  }

  /**
   * Template method for use in validation.
   * @param mixed $value Value to be processed
   * @throws \svelte\validation\FailedValidationException When test fails.
   */
  abstract protected function processValidationRule($value);
}
