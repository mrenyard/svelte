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
 * @author mrenyard (renyard.m@gmail.com)
 * @version 0.0.9;
 */
namespace tests\svelte\condition;

require_once '/usr/share/php/svelte/core/SvelteObject.class.php';
require_once '/usr/share/php/svelte/core/Str.class.php';
require_once '/usr/share/php/svelte/core/PropertyNotSetException.class.php';
require_once '/usr/share/php/svelte/condition/iEnvironment.class.php';
require_once '/usr/share/php/svelte/condition/Environment.class.php';
require_once '/usr/share/php/svelte/condition/SQLEnvironment.class.php';

use \svelte\core\Str;
use \svelte\core\PropertyNotSetException;
use \svelte\condition\SQLEnvironment;

/**
 * Collection of tests for \svelte\condition\Environment.
 * .
 */
class SQLEnvironmentTest extends \PHPUnit\Framework\TestCase
{
  private $testInstance;

  /**
   * Setup - add variables
   */
  public function setUp() : void
  {
    $this->testInstance = SQLEnvironment::getInstance();
  }

  /**
   * Collection of assertions for \svelte\condition\Environment::getInstance().
   * - assert __constructor inaccessible (protected).
   * - assert is instance of {@link \svelte\condition\Environment}
   * - assert is instance of {@link \svelte\condition\iEnvironment}
   * - assert is instance of {@link \svelte\core\SvelteObject}
   * - assert returns the SAME instance on every call.
   * @link svelte.condition.Environment#method_getInstance svelte\condition\Environment\getInstance()
   */
  public function testGetInstance()
  {
    try {
      $testObject = new SQLEnvironment();
    } catch (\Error $expected) {
      $this->assertInstanceOf('\svelte\condition\Environment', $this->testInstance);
      $this->assertInstanceOf('\svelte\condition\iEnvironment', $this->testInstance);
      $this->assertInstanceOf('\svelte\core\SvelteObject', $this->testInstance);
      $this->assertSame(SQLEnvironment::getInstance(), $this->testInstance);
      return;
    }
    $this->fail('An expected \Error has NOT been raised');
  }

  /**
   * Collection of assertions for \svelte\condition\Environment::memberAccess.
   * - assert throws {@link \svelte\core\PropertyNotSetException} when trying to set 'memberAccess'
   *   - with message: <em>'[className]->memberAccess is NOT settable'</em>.
   * - assert allows retrieval of 'memberAccess'.
   * - assert retreved is an instance of {@link \svelte\core\Str}.
   * - assert retreved is same string as provided at construction (.).
   * @link svelte.condition.Environment#method_get_memberAccess svelte\condition\Condition::memberAccess.
   */
  public function testMemberAccess()
  {
    try {
      $this->testInstance->memberAccess = Str::set('not settable');
    } catch (PropertyNotSetException $expected) {
      $this->assertInstanceOf('\svelte\core\Str', $this->testInstance->memberAccess);
      $this->assertSame('.', (string)$this->testInstance->memberAccess);
      return;
    }
    $this->fail('An expected \svelte\core\PropertyNotSetException has NOT been raised');
  }

  /**
   * Collection of assertions for \svelte\condition\Environment::assignment.
   * - assert throws {@link \svelte\core\PropertyNotSetException} when trying to set 'assignment'
   *   - with message: <em>'[className]->assignment is NOT settable'</em>.
   * - assert allows retrieval of 'assignment'.
   * - assert retreved is an instance of {@link \svelte\core\Str}.
   * - assert retreved is same string as provided at construction (=).
   * @link svelte.condition.Environment#method_get_assignment svelte\condition\Condition::assignment.
   */
  public function testAssignment()
  {
    try {
      $this->testInstance->assignment = Str::set('not settable');
    } catch (PropertyNotSetException $expected) {
      $this->assertSame(
        'svelte\condition\SQLEnvironment->assignment is NOT settable',
        $expected->getMessage()
      );
      $this->assertInstanceOf('\svelte\core\Str', $this->testInstance->assignment);
      $this->assertSame('=', (string)$this->testInstance->assignment);
      return;
    }
    $this->fail('An expected \svelte\core\PropertyNotSetException has NOT been raised');
  }

  /**
   * Collection of assertions for \svelte\condition\Environment::equalTo.
   * - assert throws {@link \svelte\core\PropertyNotSetException} when trying to set 'equalTo'
   *   - with message: <em>'[className]->equalTo is NOT settable'</em>.
   * - assert allows retrieval of 'equalTo'.
   * - assert retreved is an instance of {@link \svelte\core\Str}.
   * - assert retreved is same string as provided at construction (=).
   * @link svelte.condition.Environment#method_get_equalTo svelte\condition\Condition::equalTo.
   */
  public function testEqualTo()
  {
    try {
      $this->testInstance->equalTo = Str::set('not settable');
    } catch (PropertyNotSetException $expected) {
      $this->assertSame(
        'svelte\condition\SQLEnvironment->equalTo is NOT settable',
        $expected->getMessage()
      );
      $this->assertInstanceOf('\svelte\core\Str', $this->testInstance->equalTo);
      $this->assertSame(' = ', (string)$this->testInstance->equalTo);
      return;
    }
    $this->fail('An expected \svelte\core\PropertyNotSetException has NOT been raised');
  }

  /**
   * Collection of assertions for \svelte\condition\Environment::notEqualTo.
   * - assert throws {@link \svelte\core\PropertyNotSetException} when trying to set 'notEqualTo'
   *   - with message: <em>'[className]->notEqualTo is NOT settable'</em>.
   * - assert allows retrieval of 'notEqualTo'.
   * - assert retreved is an instance of {@link \svelte\core\Str}.
   * - assert retreved is same string as provided at construction (<>).
   * @link svelte.condition.Environment#method_get_notEqualTo svelte\condition\Condition::notEqualTo.
   */
  public function testNotEqualTo()
  {
    try {
      $this->testInstance->notEqualTo = Str::set('not settable');
    } catch (PropertyNotSetException $expected) {
      $this->assertSame(
        'svelte\condition\SQLEnvironment->notEqualTo is NOT settable',
        $expected->getMessage()
      );
      $this->assertInstanceOf('\svelte\core\Str', $this->testInstance->notEqualTo);
      $this->assertSame(' <> ', (string)$this->testInstance->notEqualTo);
      return;
    }
    $this->fail('An expected \svelte\core\PropertyNotSetException has NOT been raised');
  }

  /**
   * Collection of assertions for \svelte\condition\Environment::lessThan.
   * - assert throws {@link \svelte\core\PropertyNotSetException} when trying to set 'lessThan'
   *   - with message: <em>'[className]->lessThan is NOT settable'</em>.
   * - assert allows retrieval of 'lessThan'.
   * - assert retreved is an instance of {@link \svelte\core\Str}.
   * - assert retreved is same string as provided at construction ( < ).
   * @link svelte.condition.Environment#method_get_lessThan svelte\condition\Condition::lessThan.
   */
  public function testLessThan()
  {
    try {
      $this->testInstance->lessThan = Str::set('not settable');
    } catch (PropertyNotSetException $expected) {
      $this->assertSame(
        'svelte\condition\SQLEnvironment->lessThan is NOT settable',
        $expected->getMessage()
      );
      $this->assertInstanceOf('\svelte\core\Str', $this->testInstance->lessThan);
      $this->assertSame(' < ', (string)$this->testInstance->lessThan);
      return;
    }
    $this->fail('An expected \svelte\core\PropertyNotSetException has NOT been raised');
  }

  /**
   * Collection of assertions for \svelte\condition\Environment::greaterThan.
   * - assert throws {@link \svelte\core\PropertyNotSetException} when trying to set 'greaterThan'
   *   - with message: <em>'[className]->greaterThan is NOT settable'</em>.
   * - assert allows retrieval of 'greaterThan'.
   * - assert retreved is an instance of {@link \svelte\core\Str}.
   * - assert retreved is same string as provided at construction ( > ).
   * @link svelte.condition.Environment#method_get_greaterThan svelte\condition\Condition::greaterThan.
   */
  public function testGreaterThan()
  {
    try {
      $this->testInstance->greaterThan = Str::set('not settable');
    } catch (PropertyNotSetException $expected) {
      $this->assertSame(
        'svelte\condition\SQLEnvironment->greaterThan is NOT settable',
        $expected->getMessage()
      );
      $this->assertInstanceOf('\svelte\core\Str', $this->testInstance->greaterThan);
      $this->assertSame(' > ', (string)$this->testInstance->greaterThan);
      return;
    }
    $this->fail('An expected \svelte\core\PropertyNotSetException has NOT been raised');
  }

  /**
   * Collection of assertions for \svelte\condition\Environment::and.
   * - assert throws {@link \svelte\core\PropertyNotSetException} when trying to set 'and'
   *   - with message: <em>'[className]->and is NOT settable'</em>.
   * - assert allows retrieval of 'and'.
   * - assert retreved is an instance of {@link \svelte\core\Str}.
   * - assert retreved is same string as provided at construction ( AND ).
   * @link svelte.condition.Environment#method_get_and svelte\condition\Condition::and.
   */
  public function testAnd()
  {
    try {
      $this->testInstance->and = Str::set('not settable');
    } catch (PropertyNotSetException $expected) {
      $this->assertSame(
        'svelte\condition\SQLEnvironment->and is NOT settable',
        $expected->getMessage()
      );
      $this->assertInstanceOf('\svelte\core\Str', $this->testInstance->and);
      $this->assertSame(' AND ', (string)$this->testInstance->and);
      return;
    }
    $this->fail('An expected \svelte\core\PropertyNotSetException has NOT been raised');
  }

  /**
   * Collection of assertions for \svelte\condition\Environment::or.
   * - assert throws {@link \svelte\core\PropertyNotSetException} when trying to set 'or'
   *   - with message: <em>'[className]->or is NOT settable'</em>.
   * - assert allows retrieval of 'or'.
   * - assert retreved is an instance of {@link \svelte\core\Str}.
   * - assert retreved is same string as provided at construction ( OR ).
   * @link svelte.condition.Environment#method_get_or svelte\condition\Condition::or.
   */
  public function testOr()
  {
    try {
      $this->testInstance->or = Str::set('not settable');
    } catch (PropertyNotSetException $expected) {
      $this->assertSame(
        'svelte\condition\SQLEnvironment->or is NOT settable',
        $expected->getMessage()
      );
      $this->assertInstanceOf('\svelte\core\Str', $this->testInstance->or);
      $this->assertSame(' OR ', (string)$this->testInstance->or);
      return;
    }
    $this->fail('An expected \svelte\core\PropertyNotSetException has NOT been raised');
  }

  /**
   * Collection of assertions for \svelte\condition\Environment::openingParenthesis.
   * - assert throws {@link \svelte\core\PropertyNotSetException} when trying to set 'openingParenthesis'
   *   - with message: <em>'[className]->memberAccess is NOT settable'</em>.
   * - assert allows retrieval of 'openingParenthesis'.
   * - assert retreved is an instance of {@link \svelte\core\Str}.
   * - assert retreved is same string as provided at construction (").
   * @link svelte.condition.Environment#method_get_openingParenthesis svelte\condition\Condition::openingParenthesis.
   */
  public function testOpeningParenthesis()
  {
    try {
      $this->testInstance->openingParenthesis = Str::set('not settable');
    } catch (PropertyNotSetException $expected) {
      $this->assertSame(
        'svelte\condition\SQLEnvironment->openingParenthesis is NOT settable',
        $expected->getMessage()
      );
      $this->assertInstanceOf('\svelte\core\Str', $this->testInstance->openingParenthesis);
      $this->assertSame('"', (string)$this->testInstance->openingParenthesis);
      return;
    }
    $this->fail('An expected \svelte\core\PropertyNotSetException has NOT been raised');
  }

  /**
   * Collection of assertions for \svelte\condition\Environment::closingParenthesis.
   * - assert throws {@link \svelte\core\PropertyNotSetException} when trying to set 'closingParenthesis'
   *   - with message: <em>'[className]->closingParenthesis is NOT settable'</em>.
   * - assert allows retrieval of 'closingParenthesis'.
   * - assert retreved is an instance of {@link \svelte\core\Str}.
   * - assert retreved is same string as provided at construction (").
   * @link svelte.condition.Environment#method_get_closingParenthesis svelte\condition\Condition::closingParenthesis.
   */
  public function testClosingParenthesis()
  {
    try {
      $this->testInstance->closingParenthesis = Str::set('not settable');
    } catch (PropertyNotSetException $expected) {
      $this->assertSame(
        'svelte\condition\SQLEnvironment->closingParenthesis is NOT settable',
        $expected->getMessage()
      );
      $this->assertInstanceOf('\svelte\core\Str', $this->testInstance->closingParenthesis);
      $this->assertSame('"', (string)$this->testInstance->closingParenthesis);
      return;
    }
    $this->fail('An expected \svelte\core\PropertyNotSetException has NOT been raised');
  }
}
