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
namespace tests\svelte\model\business\field;

require_once '/usr/share/php/svelte/core/SvelteObject.class.php';
require_once '/usr/share/php/svelte/model/business/field/Field.class.php';

require_once '/usr/share/php/tests/svelte/model/business/field/mocks/FieldTest/Record.class.php';
require_once '/usr/share/php/tests/svelte/model/business/field/mocks/FieldTest/MockField.class.php';
require_once '/usr/share/php/tests/svelte/model/business/field/mocks/FieldTest/MockBusinessModel.class.php';
require_once '/usr/share/php/tests/svelte/model/business/field/mocks/FieldTest/MockBusinessModelWithErrors.class.php';

use svelte\core\Str;
use svelte\core\Collection;
use svelte\core\PropertyNotSetException;
use svelte\condition\PostData;

use tests\svelte\model\business\field\mocks\FieldTest\MockField;
use tests\svelte\model\business\field\mocks\FieldTest\MockBusinessModel;
use tests\svelte\model\business\field\mocks\FieldTest\MockBusinessModelWithErrors;

use svelte\model\business\Record;

/**
 * Collection of tests for \svelte\model\business\field\Field.
 */
class FieldTest extends \PHPUnit\Framework\TestCase
{
  private $testObject;
  private $mockRecord;
  private $children;
  private $testChild1;
  private $testChild2;
  private $testChild3;
  private $grandchildren;
  private $grandchild;

  /**
   * Setup - add variables
   */
  public function setUp()
  {
    Record::reset();
    MockField::reset();
    MockBusinessModel::reset();
    $this->children = new Collection();
    $this->grandchildren = new Collection();

    $this->mockRecord = new Record();
    $this->testObject = new MockField(Str::set('aProperty'), $this->mockRecord, $this->children);

    $this->testChild1 = new MockBusinessModel('First child');
    $this->testChild2 = new MockBusinessModelWithErrors('Second child');
    $this->testChild3 = new MockBusinessModel('Third child', $this->grandchildren);
    $this->grandchild = new MockBusinessModelWithErrors('First grandchild');

    $this->children->add($this->testChild1);
    $this->children->add($this->testChild2);
    $this->children->add($this->testChild3);
    $this->grandchildren->add($this->grandchild);
  }

  /**
   * Collection of assertions for \svelte\model\business\field\Field::__construct().
   * - assert is instance of {@link \svelte\core\SvelteObject}
   * - assert is instance of {@link \svelte\model\Model}
   * - assert is instance of {@link \svelte\model\business\BusinessModel}
   * - assert is instance of {@link \IteratorAggregate}
   * - assert is instance of {@link \Countable}
   * - assert is instance of {@link \ArrayAccess}
   * - assert is instance of {@link \svelte\model\field\Field}
   * @link svelte.model.business.field.Field svelte\model\business\field\Field
   */
  public function test__construct()
  {
    $this->assertInstanceOf('\svelte\core\SvelteObject', $this->testObject);
    $this->assertInstanceOf('\svelte\model\Model', $this->testObject);
    $this->assertInstanceOf('\svelte\model\business\BusinessModel', $this->testObject);
    $this->assertInstanceOf('\IteratorAggregate', $this->testObject);
    $this->assertInstanceOf('\Countable', $this->testObject);
    $this->assertInstanceOf('\ArrayAccess', $this->testObject);
    $this->assertInstanceOf('\svelte\model\business\field\Field', $this->testObject);
  }

  /**
   * Collection of assertions for \svelte\model\business\field\Field::id.
   * - assert {@link \svelte\core\PropertyNotSetException} thrown when trying to set property 'id'
   * - assert property 'id' is gettable.
   * - assert returned value instance of {@link \svelte\core\Str}.
   * - assert returned value matches expected result.
   * @link svelte.model.business.field.Field#method_get_id svelte\model\business\field\Field::id
   */
  public function testGet_id()
  {
    try {
      $this->testObject->id = "ID";
    } catch (PropertyNotSetException $expected) {
      $this->assertSame(get_class($this->testObject) . '->id is NOT settable', $expected->getMessage());
      $this->assertInstanceOf('\svelte\core\Str', $this->testObject->id);
      $this->assertSame($this->mockRecord->id . ':a-property', (string)$this->testObject->id);
      $this->assertSame('mock-business-model:0', (string)$this->testChild1->id);
      $this->assertSame('mock-business-model:1', (string)$this->testChild2->id);
      $this->assertSame('mock-business-model:2', (string)$this->testChild3->id);
      $this->assertSame('mock-business-model:3', (string)$this->grandchild->id);
      return;
    }
    $this->fail('An expected \svelte\core\PropertyNotSetException has NOT been raised.');
  }

  /**
   * Collection of assertions for \svelte\model\business\field\Field::description.
   * - assert {@link \svelte\core\PropertyNotSetException} thrown when trying to set property 'description'
   * - assert property 'description' is gettable.
   * - assert returned value instance of {@link \svelte\core\Str}.
   * - assert returned same as 'id'.
   * - assert returned value matches expected result.
   * @link svelte.model.business.field.Field#method_get_description svelte\model\business\field\Field::description
   */
  public function testGet_description()
  {
    try {
      $this->testObject->description = "DESCRIPTION";
    } catch (PropertyNotSetException $expected) {
      $this->assertSame(get_class($this->testObject) . '->description is NOT settable', $expected->getMessage());
      $this->assertInstanceOf('\svelte\core\Str', $this->testObject->description);
      $this->assertEquals($this->testObject->id, $this->testObject->description);
      $this->assertSame($this->mockRecord->id . ':a-property', (string)$this->testObject->description);
      $this->assertEquals('mock-business-model:0', (string)$this->testChild1->description);
      $this->assertEquals('mock-business-model:1', (string)$this->testChild2->description);
      $this->assertEquals('mock-business-model:2', (string)$this->testChild3->description);
      $this->assertEquals('mock-business-model:3', (string)$this->grandchild->description);
      return;
    }
    $this->fail('An expected \svelte\core\PropertyNotSetException has NOT been raised.');
  }

  /**
   * Collection of assertions for \svelte\model\business\field\Field::value.
   * - assert {@link \svelte\core\PropertyNotSetException} thrown when trying to set property 'value'
   * - assert property 'value' is gettable.
   * - assert returned same as provided records getPropertyValue() method.
   * - assert returned value matches expected result.
   * @link svelte.model.business.field.Field#method_get_value svelte\model\business\field\Field::value
   */
  public function testGet_value()
  {
    try {
      $this->testObject->value = 'VALUE';
    } catch (PropertyNotSetException $expected) {
      $value = $this->testObject->value;
      $this->assertSame(1, Record::$getPropertyValueCount);
      $this->assertSame($this->mockRecord->getPropertyValue('aProperty'), $value);
      $this->assertSame('VALUE', $value);
      return;
    }
    $this->fail('An expected \svelte\core\PropertyNotSetException has NOT been raised.');
  }

  /**
   * Collection of assertions for \svelte\model\business\field\Field::type.
   * - assert {@link \svelte\core\PropertyNotSetException} thrown when trying to set property 'type'
   * - assert property 'type' is gettable.
   * - assert returned value is of type {@link \svelte\core\Str}.
   * - assert returned value matches expected result.
   * @link svelte.model.business.field.Field#method_get_type svelte\model\business\field\Field::type
   */
  public function testGet_type()
  {
    try {
      $this->testObject->type = "TYPE";
    } catch (PropertyNotSetException $expected) {
      $this->assertSame(get_class($this->testObject) . '->type is NOT settable', $expected->getMessage());
      $this->assertInstanceOf('\svelte\core\Str', $this->testObject->type);
      $this->assertEquals(' mock-field field', (string)$this->testObject->type);
      $this->assertSame(' mock-business-model business-model', (string)$this->testChild1->type);
      $this->assertSame(' mock-business-model-with-errors mock-business-model', (string)$this->testChild2->type);
      $this->assertSame(' mock-business-model business-model', (string)$this->testChild3->type);
      $this->assertSame(' mock-business-model-with-errors mock-business-model', (string)$this->grandchild->type);
      return;
    }
    $this->fail('An expected \svelte\core\PropertyNotSetException has NOT been raised.');
  }

  /**
   * Collection of assertions for \svelte\model\business\field\Field::getIterator().
   * - assert returns object that is an instance of {@link \Traversable}
   * - assert foreach loop, iterates through each expected object.
   * - assert foreach returned object matches expected.
   * @link svelte.model.business.field.Field#method_getIterator svelte\model\business\field\Field::getIterator()
   */
  public function testGetIterator()
  {
    $this->assertInstanceOf('\Traversable', $this->testObject->getIterator());

    $i = 0;
    $iterator = $this->children->getIterator();
    $iterator->rewind();
    foreach ($this->testObject as $child) {
      $this->assertSame($child, $iterator->current());
      $this->assertSame('mock-business-model:' . $i++, (string)$child->id);
      $iterator->next();
    }
    $this->assertSame('record:new:a-property', (string)$this->testObject->id);
  }

  /**
   * Collection of assertions for \svelte\model\business\field\Field::offsetGet.
   * - assert {@link \OutOfBoundsException} thrown when offset index beyond bounds of its children
   * - assert expected object returned at its expected index.
   * @link svelte.model.business.field.Field#method_offsetGet svelte\model\business\field\Field::offsetGet()
   */
  public function testOffsetGet()
  {
    try {
      $this->testObject[4];
    } catch (\OutOfBoundsException $expected) {

      $this->assertInstanceOf('\svelte\model\business\BusinessModel', $this->testObject[0]);
      $this->assertSame($this->testChild1, $this->testObject[0]);

      $this->assertInstanceOf('\svelte\model\business\BusinessModel', $this->testObject[1]);
      $this->assertSame($this->testChild2, $this->testObject[1]);

      $this->assertInstanceOf('\svelte\model\business\BusinessModel', $this->testObject[2]);
      $this->assertSame($this->testChild3, $this->testObject[2]);
      return;
    }
    $this->fail('An expected \OutOfBoundsException has NOT been raised.');
  }

  /**
   * Collection of assertions for \svelte\model\business\field\Field::offsetExists.
   * - assert True returned on isset() when within expected bounds.
   * - assert False returned on isset() when outside expected bounds.
   * @link svelte.model.business.field.Field#method_offsetExists svelte\model\business\field\Field::offsetExists()
   */
  public function testOffsetExists()
  {
    $this->assertTrue(isset($this->testObject[0]));
    $this->assertTrue(isset($this->testObject[1]));
    $this->assertTrue(isset($this->testObject[2]));
    $this->assertTrue(isset($this->testObject[2][0]));
    $this->assertFalse(isset($this->testObject[3]));
  }

  /**
   * Collection of assertions for svelte\model\business\field\Field::offsetSet().
   * - assert throws BadMethodCallException as this method should be inaccessible
   *   - with message: <em>'Array access setting is not allowed, please use add.'</em>
   * @link svelte.model.business.field.Field#method_offsetSet \svelte\model\business\field\Field::offsetSet()
   */
  public function testOffsetSet()
  {
    $this->expectException(\BadMethodCallException::class);
    $this->expectExceptionMessage = 'Array access setting is not allowed.';
    $this->testObject[3] = new MockBusinessModel('Forth child');
  }

  /**
   * Collection of assertions for \svelte\model\business\field\Field::offsetUnset.
   * - assert throws BadMethodCallException whenever offsetUnset is called
   *  - with message *Array access unsetting is not allowed.*
   * @link svelte.model.business.field.Field#method_offsetUnset svelte\model\business\field\Field::offsetUnset()
   */
  public function testOffsetUnset()
  {
    $this->expectException(\BadMethodCallException::class);
    $this->expectExceptionMessage = 'Array access unsetting is not allowed.';
    unset($this->testObject[0]);
  }

  /**
   * Collection of assertions for \svelte\model\business\field\Field::validate() where PostData
   * does NOT contain an InputDataCondition with an attribute that matches the testObject's id.
   * - assert returns void (null) when called.
   * - assert if provided PostData does NOT contain an InputDataCondition with an attribute that
   *    matches the testObject's id, then its processValidationRule method, is NOT called.
   * - assert validate method is propagated through (touched on) testsObject and all of
   *  its children and grandchildren.
   * @link svelte.model.business.field.Field#method_validate svelte\model\business\field\Field::validate()
   */
  public function testValidateProcessValidationRuleNotCalled()
  {
    $this->assertNull($this->testObject->validate(new PostData()));
    $this->assertSame(0, MockField::$processValidationRuleCount);
    $this->assertSame(1, $this->testChild1->validateCount);
    $this->assertSame(1, $this->testChild2->validateCount);
    $this->assertSame(1, $this->testChild3->validateCount);
    $this->assertSame(1, $this->grandchild->validateCount);
  }

  /**
   * Further collection of assertions for \svelte\model\business\field\Field::validate(), where
   * PostData contains an InputDataCondition with an attribute that matches the testObject's id.
   * - assert returns void (null) when called.
   * - assert if provided PostData contains an InputDataCondition with an attribute that matches
   *    the testObject's id, then its processValidationRule method is called.
   * - assert if provided PostData contains an InputDataCondition with an attribute that matches
   *    the testObject's id and its processValidationRule method is called and passes, then its
   *    containingRecord setPropertyMethod is called.
   * - assert validate method is propagated through (touched on) testObject and all of
   *  its children and grandchildren.
   * @link svelte.model.business.field.Field#method_validate svelte\model\business\field\Field::validate()
   */
  public function testValidateProcessValidationRuleCalled()
  {
    $this->assertNull($this->testObject->validate(PostData::build(array(
      'record:new:a-property' => 'GOOD'
    ))));
    $this->assertSame(1, MockField::$processValidationRuleCount);
    $this->assertSame(1, $this->testChild1->validateCount);
    $this->assertSame(1, $this->testChild2->validateCount);
    $this->assertSame(1, $this->testChild3->validateCount);
    $this->assertSame(1, $this->grandchild->validateCount);
    $this->assertSame(1, Record::$setPropertyValueCount);
  }

  /**
   * Collection of assertions for \svelte\model\business\field\Field::hasErrors().
   * - assert returns True when any child/grandchild has recorded errors.
   * - assert if provided PostData does NOT contain an InputDataCondition with an attribute that
   *    matches the testObject's id, then its processValidationRule method, is NOT called.
   * - assert propagates through child/grandchild until reaches one that has recorded errors.
   * @link svelte.model.business.field.Field#method_hasErrors svelte\model\business\field\Field::hasErrors()
   */
  public function testHasErrors()
  {
    $this->assertNull($this->testObject->validate(new PostData()));
    $this->assertTrue($this->testObject->hasErrors());

    $this->assertSame(0, MockField::$processValidationRuleCount);
    $this->assertSame(1, MockField::$hasErrorsCount);
    $this->assertSame(1, $this->testChild1->hasErrorsCount);
    $this->assertSame(1, $this->testChild2->hasErrorsCount);
    $this->assertSame(0, $this->testChild3->hasErrorsCount);
    $this->assertSame(0, $this->grandchild->hasErrorsCount);
  }

  /**
   * Collection of assertions for \svelte\model\business\field\Field::getErrors().
   * - assert following validate(), the expected iCollection of error messages returned from
   * getErrors() are as expected, depending on which level they are called.
   * - assert any following call to hasErrors returns the same collection of messages as previously.
   * - assert a single collection containing all errors including children and grandchildren
   *  of top testObject returned when called on testObject.
   * - assert a single collection containing relevent sub errors returned when called on sub BusinessModels
   * @link svelte.model.business.field.Field#method_getErrors svelte\model\business\field\Field::getErrors()
   */
  public function testGetErrors()
  {
    $this->assertNull($this->testObject->validate(new PostData()));
    $this->assertTrue($this->testObject->hasErrors());
    $errors = $this->testObject->getErrors();

    // All errors including children and grandchildren of top testObject returned in a single collection.
    $this->assertSame('Second child\'s first error occurred during validation!', (string)$errors[0]);
    $this->assertSame('Second child\'s second error occurred during validation!', (string)$errors[1]);
    $this->assertSame('Second child\'s third error occurred during validation!', (string)$errors[2]);
    $this->assertSame('First grandchild\'s first error occurred during validation!', (string)$errors[3]);
    $this->assertSame('First grandchild\'s second error occurred during validation!', (string)$errors[4]);
    $this->assertSame('First grandchild\'s third error occurred during validation!', (string)$errors[5]);
    $this->assertFalse(isset($errors[6]));

    // Returns same results on subsequent call, while BusinessModels are in same state.
    $secondCallOnErrors = $this->testObject->getErrors();
    $this->assertEquals($secondCallOnErrors, $errors);
    $this->assertFalse(isset($secondCallOnErrors[6]));

    // Calls on sub BusinessModels return expected sub set of Errors.
    $child2Errors = $this->testChild2->getErrors();
    $this->assertSame('Second child\'s first error occurred during validation!', (string)$child2Errors[0]);
    $this->assertSame('Second child\'s second error occurred during validation!', (string)$child2Errors[1]);
    $this->assertSame('Second child\'s third error occurred during validation!', (string)$child2Errors[2]);

    // Calls on sub BusinessModels return expected sub set of Errors, even on grandchildren.
    $grandchildErrros = $this->grandchild->getErrors();
    $this->assertSame('First grandchild\'s first error occurred during validation!', (string)$grandchildErrros[0]);
    $this->assertSame('First grandchild\'s second error occurred during validation!', (string)$grandchildErrros[1]);
    $this->assertSame('First grandchild\'s third error occurred during validation!', (string)$grandchildErrros[2]);
    $this->assertFalse(isset($child3Errros[3]));

    // Because testChild3 in the parent of grandchild it returns grandchild errors alone with any of own.
    $child3Errros = $this->testChild3->getErrors();
    $this->assertSame('First grandchild\'s first error occurred during validation!', (string)$child3Errros[0]);
    $this->assertSame('First grandchild\'s second error occurred during validation!', (string)$child3Errros[1]);
    $this->assertSame('First grandchild\'s third error occurred during validation!', (string)$child3Errros[2]);
    $this->assertFalse(isset($child3Errros[3]));
  }

  /**
   * Further collection of assertions for \svelte\model\business\field\Field::hasErrors(), where
   * PostData contains an InputDataCondition with an attribute that matches the testObject's id
   * and its processValidationRule method is called and fails.
   * - assert if provided PostData contains an InputDataCondition with an attribute that matches
   *    the testObject's id, then its processValidationRule method is called.
   * - assert if provided PostData contains an InputDataCondition with an attribute that matches
   *    the testObject's id and its processValidationRule method is called and fails, throwing a
   *    FailedValidationException then its message is added to its errorCollection for retrieval
   *    by its hasErrors and getErrors methods.
   * - assert validate method is propagated through (touched on) testObject and all of
   *  its children and grandchildren.
   * @link svelte.model.business.field.Field#method_hasErrors svelte\model\business\field\Field::hasErrors()
   */
  public function testHasErrorsProcessValidationRuleCalled()
  {
    $this->assertNull($this->testObject->validate(PostData::build(array(
      'record:new:a-property' => 'BAD'
    ))));
    $this->assertSame(1, MockField::$processValidationRuleCount);
    $this->assertSame(1, Record::$setPropertyValueCount);
    $this->assertSame(1, $this->testChild1->validateCount);
    $this->assertSame(1, $this->testChild2->validateCount);
    $this->assertSame(1, $this->testChild3->validateCount);
    $this->assertSame(1, $this->grandchild->validateCount);

    $this->assertTrue($this->testObject->hasErrors());
    $this->assertSame(1, MockField::$hasErrorsCount);
    $this->assertSame(0, $this->testChild1->hasErrorsCount);
    $this->assertSame(0, $this->testChild2->hasErrorsCount);
    $this->assertSame(0, $this->testChild3->hasErrorsCount);
    $this->assertSame(0, $this->grandchild->hasErrorsCount);

    $errors = $this->testObject->getErrors();
    $this->assertSame('MockField\'s has error due to $value of BAD!', (string)$errors[0]);
    $this->assertSame('Second child\'s first error occurred during validation!', (string)$errors[1]);
    $this->assertSame('Second child\'s second error occurred during validation!', (string)$errors[2]);
    $this->assertSame('Second child\'s third error occurred during validation!', (string)$errors[3]);
    $this->assertSame('First grandchild\'s first error occurred during validation!', (string)$errors[4]);
    $this->assertSame('First grandchild\'s second error occurred during validation!', (string)$errors[5]);
    $this->assertSame('First grandchild\'s third error occurred during validation!', (string)$errors[6]);
    $this->assertFalse(isset($errors[7]));
  }

  /**
   * Collection of assertions for \svelte\model\business\field\Field::count().
   * - assert return expected int value related to the number of child BusinessModels held.
   * @link svelte.model.business.field.Field#method_count svelte\model\business\field\Field::count()
   */
  public function testCount()
  {
    $this->assertSame(3 ,$this->testObject->count());
  }
}