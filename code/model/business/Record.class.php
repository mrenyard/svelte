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
 * @version 0.0.9;
 *
 * @property-read bool $isModified Returns whether data has been modified since last update.
 */
namespace svelte\model\business;

use svelte\core\Str;
use svelte\core\Collection;
use svelte\condition\PostData;
use svelte\model\business\BusinessModel;
use svelte\core\PropertyNotSetException;

/**
 * Abstract business model Record definition.
 *
 * RESPONSIBILITIES
 * - Implement methods for validity checking & reporting
 * - Implement methods for field/property managment and access
 * - Define API to maintain and/or assert valid state of dataObject
 * - Provide methods for linking Records through association
 * - Provide methods to expose to BusinessModelManager
 *
 * COLLABORATORS
 * - {@link \svelte\model\business\Field}s
 *
 * @property-read \svelte\core\Str $key Returns value of primary key.
 * @property-read bool $isModified Returns whether data has been modified since last update.
 * @property-read bool $isValid Returns whether data is in a valid/complete state from data store or as new.
 * @property-read bool $isNew Returns whether this is yet to be updated to data storage.
 */
abstract class Record extends BusinessModel
{
  private $dataObject;
  private $validFromSource;
  private $modified;

  /**
   * Returns property name of concrete classes primary key.
   * @return \svelte\core\Str Name of property that is concrete classes primary key
   */
  abstract protected static function primaryKeyName() : Str;

  /**
   * Creates record, new or with encapsulated source data contained.
   * @param \stdClass $dataObject Simple data container
   */
  final public function __construct(\stdClass $dataObject = null)
  {
    parent::__construct();
    $this->dataObject = (isset($dataObject))? $dataObject : new \stdClass();
    $this->updated();
    $className = get_class($this);
    foreach (get_class_methods($className) as $methodName)
    {
      if (strpos($methodName, 'get_') === 0)
      {
        foreach (get_class_methods(__NAMESPACE__ . '\Record') as $parentMethod)
        {
          if ($methodName == $parentMethod) { continue 2; }
        }
        $propertyName = str_replace('get_', '', $methodName);
        $this->$propertyName;
      }
    }
  }

  /**
   * Get ID (URN).
   * **DO NOT CALL DIRECTLY, USE this->id;**
   * @return \svelte\core\Str Unique identifier for *this*
   */
  final public function get_id() : Str
  {
    return Str::COLON()->prepend(
      $this->processType((string)$this, TRUE)
    )->append(
      Str::hyphenate($this->key)
    );
  }

  /**
   * Get value
   * **DO NOT CALL DIRECTLY, USE this->value;**
   * @return \svelte\core\Str Value for *this*
   */
  protected function get_value() : Str
  {
    return $this->id;
  }

  /**
   * ArrayAccess method offsetSet, USES DISCOURAGED.
   * @param mixed $offset Index to place provided object.
   * @param mixed $object SvelteObject to be placed at provided index.
   */
  public function offsetSet($offset, $object)
  {
    if (is_numeric($offset) ||(!($object instanceof \svelte\model\business\field\Field)))
    {
      throw new \BadMethodCallException(
        'Adding properties through offsetSet STRONGLY DISCOURAGED, refer to manual!'
      );
    }
    if (!isset($this->dataObject->$offset))
    {
      $this->dataObject->$offset = NULL;
    }
    parent::offsetSet($offset, $object);
  }

  /**
   * ArrayAccess method offsetUnset, USES DISCOURAGED.
   * @param mixed $offset API to match \ArrayAccess interface
   */
  public function offsetUnset($offset)
  {
    if (isset($this->dataObject->$offset))
    {
      throw new \BadMethodCallException(
        'Unsetting properties already populated with a valid value NOT alowed!'
      );
    }
    parent::offsetUnset($offset);
  }

  /**
   * Returns value of primary key.
   * **DO NOT CALL DIRECTLY, USE this->key;**
   * @return \svelte\core\Str Value of primary key
   */
  final protected function get_key() : Str
  {
    $pkName = (string)$this->primaryKeyName();
    return Str::set((isset($this->dataObject->$pkName))? $this->dataObject->$pkName : 'new');
  }

  /**
   * Validate postdata against this and update accordingly.
   * @param \svelte\condition\PostData $postdata Collection of InputDataCondition\s
   *  to be assessed for validity and imposed on *this* business model.
   */
  final public function validate(PostData $postdata)
  {
    $this->errorCollection = new Collection(Str::set('svelte\core\Str'));
    $pk = $this[(string)$this->primaryKeyName()];
    foreach ($this as $child)
    {
      if ($child === $pk) { continue; }
      $child->validate($postdata);
    }
    if ($this->isNew) { $pk->validate($postdata); }
  }

  /**
   * Gets the value of a given property.
   * **DO NOT USE, METHOD TO ONLY BE CALLED FROM CHILD FIELD**
   * @param string $propertyName Name of property.
   * @return mixed The value of property assosiated with requested property.
   */
  public function getPropertyValueFromField(string $propertyName)
  {
    $propertyName = (string)$propertyName;
    return $this->dataObject->$propertyName;
  }

  /**
   * Sets the value of a given property.
   * **DO NOT USE, METHOD TO ONLY BE CALLED FROM CHILD FIELD**
   * @param string $propertyName Name of property to be set.
   * @param mixed The value to be set on provided property.
   */
  public function setPropertyValueFromField(string $propertyName, $value)
  {
    $this->dataObject->$propertyName = $value;
    $this->modified = true;
  }

  /**
   * Allows C# type access to properties.
   * **DO NOT CALL THIS METHOD DIRECTLY, TO BE HANDLED INTERNALLY!**
   * @param string $propertyName Name of property (handled internally)
   * @param mixed $propertyValue The value to set on requested property (handled internally)
   * @throws \svelte\validation\FailedValidationException When &value fails validation.
   * @throws svelte\validation\ValidationRule\FailedValidationException when
   */
  public function __set($propertyName, $propertyValue)
  {
    if (isset($this[$propertyName]))
    {
      $this[$propertyName]->processValidationRule($propertyValue);
      $this->dataObject->$propertyName = $propertyValue;
      $this->modified = true;
      return;
    }
    parent::__set($propertyName, $propertyValue);
    // @codeCoverageIgnoreStart
  }

  /**
   * Returns whether data has been modified since last update.
   * **DO NOT CALL DIRECTLY, USE this->isModified;**
   * @return bool Value of isModified
   */
  final protected function get_isModified() : bool
  {
    return $this->modified;
  }

  /**
   * Returns whether data is in a valid/complete state from data store or as new.
   * **DO NOT CALL DIRECTLY, USE this->isValid;**
   * @return bool Value of isValid
   */
  final protected function get_isValid() : bool
  {
    $pkName = (string)$this->primaryKeyName();
    return ($this->validFromSource || (
        isset($this->dataObject->$pkName) && $this->checkRequired($this->dataObject)
      )
    );
  }

  /**
   * Returns whether this is yet to be updated to data storage.
   * **DO NOT CALL DIRECTLY, USE this->new;**
   * @return bool Value of isNew
   */
  final protected function get_isNew() : bool
  {
    return (!$this->validFromSource);
  }

  /**
   * Set this as updated.
   * **METHOD TO ONLY BE CALLED FROM BUSINESSMODELMANGER**
   */
  public function updated()
  {
    $pkName = (string)$this->primaryKeyName();
    $this->validFromSource = (isset($this->dataObject->$pkName) && $this->checkRequired($this->dataObject));
    $this->modified = false;
  }

  /**
   * Check requeried properties have value or not.
   * @param DataObject to be checked for requiered property values
   * @return bool Check all requiered properties are set.
   */
  abstract protected function checkRequired($dataObject) : bool;
}
