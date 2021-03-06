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
namespace tests\svelte\http;

require_once '/usr/share/php/svelte/SETTING.class.php';
require_once '/usr/share/php/svelte/core/SvelteObject.class.php';
require_once '/usr/share/php/svelte/core/Str.class.php';
require_once '/usr/share/php/svelte/core/iCollection.class.php';
require_once '/usr/share/php/svelte/core/Collection.class.php';
require_once '/usr/share/php/svelte/core/iOption.class.php';
require_once '/usr/share/php/svelte/core/Option.class.php';
require_once '/usr/share/php/svelte/core/OptionList.class.php';
require_once '/usr/share/php/svelte/core/BadPropertyCallException.class.php';
require_once '/usr/share/php/svelte/core/PropertyNotSetException.class.php';
require_once '/usr/share/php/svelte/condition/Condition.class.php';
require_once '/usr/share/php/svelte/condition/BusinessCondition.class.php';
require_once '/usr/share/php/svelte/condition/Operator.class.php';
require_once '/usr/share/php/svelte/condition/iEnvironment.class.php';
require_once '/usr/share/php/svelte/condition/Environment.class.php';
require_once '/usr/share/php/svelte/condition/PHPEnvironment.class.php';
require_once '/usr/share/php/svelte/condition/SQLEnvironment.class.php';
require_once '/usr/share/php/svelte/condition/InputDataCondition.class.php';
require_once '/usr/share/php/svelte/condition/FilterCondition.class.php';
require_once '/usr/share/php/svelte/condition/Filter.class.php';
require_once '/usr/share/php/svelte/condition/PostData.class.php';
require_once '/usr/share/php/svelte/model/Model.class.php';
require_once '/usr/share/php/svelte/model/business/BusinessModelManager.class.php';
require_once '/usr/share/php/svelte/model/business/iBusinessModelDefinition.class.php';
require_once '/usr/share/php/svelte/model/business/SimpleBusinessModelDefinition.class.php';
require_once '/usr/share/php/svelte/model/business/BusinessModel.class.php';
require_once '/usr/share/php/svelte/model/business/Record.class.php';
require_once '/usr/share/php/svelte/model/business/RecordCollection.class.php';
require_once '/usr/share/php/svelte/model/business/AuthenticatableUnit.class.php';
require_once '/usr/share/php/svelte/model/business/LoginAccountType.class.php';
require_once '/usr/share/php/svelte/model/business/LoginAccount.class.php';
require_once '/usr/share/php/svelte/model/business/FailedValidationException.class.php';
require_once '/usr/share/php/svelte/model/business/validation/ValidationRule.class.php';
require_once '/usr/share/php/svelte/model/business/validation/LowerCaseAlphanumeric.class.php';
require_once '/usr/share/php/svelte/model/business/validation/RegexEmail.class.php';
require_once '/usr/share/php/svelte/model/business/validation/dbtype/DbTypeValidation.class.php';
require_once '/usr/share/php/svelte/model/business/validation/dbtype/VarChar.class.php';
require_once '/usr/share/php/svelte/model/business/field/Field.class.php';
require_once '/usr/share/php/svelte/model/business/field/Input.class.php';
require_once '/usr/share/php/svelte/model/business/field/SelectOne.class.php';
require_once '/usr/share/php/svelte/http/Session.class.php';
require_once '/usr/share/php/svelte/http/Unauthorized401Exception.class.php';

require_once '/usr/share/php/tests/svelte/http/mocks/SessionTest/HeaderFunctions.php';
require_once '/usr/share/php/tests/svelte/http/mocks/SessionTest/model/business/MockBusinessModelManager.class.php';
require_once '/usr/share/php/tests/svelte/http/mocks/SessionTest/model/business/AnAuthenticatableUnit.class.php';

use svelte\SETTING;
use svelte\http\Session;
use svelte\http\Unauthorized401Exception;
use svelte\model\business\LoginAccountTypeOption;
use svelte\model\business\LoginAccountType;
use svelte\model\business\LoginAccount;

use svelte\model\business\MockBusinessModelManager;
use svelte\model\business\AnAuthenticatableUnit;

/**
 * Collection of tests for svelte\http\Session.
 */
class SessionTest extends \PHPUnit\Framework\TestCase
{
  private static $ref;

  /**
   * @var string $sessionLoginAccountEmail Login email to test againt.
   */
  public static $sessionLoginAccountEmail;

  /**
   * @var string $unencryptedPassword Password for tesing agains.
   */
  public static $unencryptedPassword;

  /**
   * Set-up.
   * - Add values to SETTINGs for our simulated test environment
   * - Set test varibles for sessionLoginAccountEmail and unencryptedPassword
   * - assert throws \BadMethodCallException on calling static function
   *  Seesion::authorizedAs() prior to Seesion::getInstance().
   * - Calls Seesion::getInstance() if NOT already defined prior to test.
   */
  public function setUp() : void
  {
    SETTING::$TEST_ON = TRUE;
    SETTING::$SVELTE_BUSINESS_MODEL_NAMESPACE='svelte\model\business';
    SETTING::$SVELTE_BUSINESS_MODEL_MANAGER = 'svelte\model\business\MockBusinessModelManager';
    SETTING::$SECURITY_PASSWORD_SALT = 'A hard days night!';
    SETTING::$SVELTE_AUTHENTICATABLE_UNIT = 'AnAuthenticatableUnit';
    self::$sessionLoginAccountEmail = 'a.person@domain.com';
    self::$unencryptedPassword = 'P@ssw0rd!';
    if (!isset(self::$ref))
    {
      try {
        Session::authorizedAs(LoginAccountType::REGISTERED());
      } catch (\BadMethodCallException $expected) {
        $this->AssertSame(
          'Session::instance() MUST be called before Session::authorizedAs().',
          $expected->getMessage()
        );
        Session::getInstance();
        self::$ref = Session::getInstance();
        return;
      }
      $this->fail('An expected \BadMethodCallException has NOT been raised');
    }
    SETTING::$TEST_RESET_SESSION = FALSE;
  }

  /**
   * Collection of assertions for \svelte\http\Session::getInstance().
   * - assert is instance of {@link \svelte\core\SvelteObject}
   * - assert is instance of {@link \svelte\http\Session}
   * - assert is same instance on every call (Singleton)
   * - assert cannot be cloned, throws \BadMethodCallException
   *   - with message: *'Clone is not allowed'*
   * @link svelte.http.Session svelte\http\Session
   */
  public function testGetInstance()
  {
    $testObject = Session::getInstance();
    $this->assertInstanceOf('\svelte\core\SvelteObject', $testObject);
    $this->assertInstanceOf('\svelte\http\Session', $testObject);
    $this->assertSame(Session::getInstance(), $testObject);
    try {
      $fail = clone $testObject;
    } catch (\BadMethodCallException $expected) {
      $this->AssertSame('Clone is not allowed', $expected->getMessage());
      unset($fail);
      return;
    }
    $this->fail('An expected \BadMethodCallException has NOT been raised');
  }

  /**
   * Collection of assertions for \svelte\http\Session::authorizedAs()
   *  with already Authenticated $_SESSION['LoginAccount'].
   * - assert $_SESSION['loginAccount'] reference retained.
   * - assert returns TRUE when $_SESSION['LoginAccount'] has sufficient privileges
   * - assert returns FALSE when $_SESSION['LoginAccount'] has insufficient privileges
   * @link svelte.http.Session#method_authorizedAs svelte\http\Session::AuthorizedAs()
   */
  public function testAuthorizedAsAlreadyAuthenticated()
  {
    $_POST = array();
    $dataObject = new \stdClass();
    $dataObject->email = self::$sessionLoginAccountEmail;
    $dataObject->encryptedPassword = crypt(
      self::$unencryptedPassword, \svelte\SETTING::$SECURITY_PASSWORD_SALT
    );
    $dataObject->typeID = LoginAccountType::ADMINISTRATOR()->key;
    $dataObject->auPK = 'aperson';
    $sessionLoginAccount = new LoginAccount($dataObject);
    $_SESSION['loginAccount'] = $sessionLoginAccount;
    SETTING::$TEST_RESET_SESSION = TRUE;
    $testObject = Session::getInstance();
    $this->assertTrue(Session::AuthorizedAs(LoginAccountType::REGISTERED()));
    $this->assertTrue(Session::AuthorizedAs(LoginAccountType::USER()));
    $this->assertTrue(Session::AuthorizedAs(LoginAccountType::AFFILIATE()));
    $this->assertTrue(Session::AuthorizedAs(LoginAccountType::ADMINISTRATOR()));
    $this->assertFalse(Session::AuthorizedAs(LoginAccountType::ADMINISTRATOR_MANAGER()));
    $this->assertFalse(Session::AuthorizedAs(LoginAccountType::SYSTEM_ADMINISTRATOR()));
    $this->assertSame($_SESSION['loginAccount'], $sessionLoginAccount);
  }

  /**
   * Collection of assertions for \svelte\http\Session::authorizeAs()
   *  with already Authenticated $_SESSION['LoginAccount'].
   * - assert $_POST data retained.
   * - assert $_SESSION['loginAccount'] reference retained.
   * - assert returns without interruption when $_SESSION['LoginAccount'] has sufficient privileges
   * - assert throws Unauthorized401Exception when $_SESSION['LoginAccount'] has insufficient privileges
   *   - with message: *'Unauthenticated or insufficient authority'*
   *   - or with $_POST data message: *'Attempting POST to resource REQUIRING authentication or insufficient authority'*
   * @link svelte.http.Session#method_authorizeAs svelte\http\Session::AuthorizeAs()
   */
  public function testAuthorizeAsAlreadyAuthenticated()
  {
    $postArray = array(
      'record-name:key:property-a' => 'valueA',
      'record-name:key:property-b' => 'valueB',
      'record-name:key:property-c' => 'valueC'
    );
    $dataObject = new \stdClass();
    $dataObject->id = 'login-account:aperson';
    $dataObject->email = self::$sessionLoginAccountEmail;
    $dataObject->encryptedPassword = crypt(
      self::$unencryptedPassword, \svelte\SETTING::$SECURITY_PASSWORD_SALT
    );
    $dataObject->typeID = LoginAccountType::ADMINISTRATOR()->key;
    $dataObject->auPK = 'aperson';
    $sessionLoginAccount = new LoginAccount($dataObject);
    $_SESSION['loginAccount'] = $sessionLoginAccount;
    $_POST = $postArray;
    SETTING::$TEST_RESET_SESSION = TRUE;
    $testObject = Session::getInstance();
    $this->assertNull($testObject->AuthorizeAs(LoginAccountType::REGISTERED()));
    $this->assertSame($postArray, $_POST);
    $this->assertSame($_SESSION['loginAccount'], $sessionLoginAccount);
    $this->assertNull($testObject->AuthorizeAs(LoginAccountType::USER()));
    $this->assertSame($postArray, $_POST);
    $this->assertSame($_SESSION['loginAccount'], $sessionLoginAccount);
    $this->assertNull($testObject->AuthorizeAs(LoginAccountType::AFFILIATE()));
    $this->assertSame($postArray, $_POST);
    $this->assertSame($_SESSION['loginAccount'], $sessionLoginAccount);
    $this->assertNull($testObject->AuthorizeAs(LoginAccountType::ADMINISTRATOR()));
    $this->assertSame($postArray, $_POST);
    $this->assertSame($_SESSION['loginAccount'], $sessionLoginAccount);
    try {
      $this->assertFalse($testObject->AuthorizeAs(LoginAccountType::ADMINISTRATOR_MANAGER()));
    } catch (Unauthorized401Exception $expected) {
      $this->assertSame(
        'Attempting POST to resource REQUIRING authentication or insufficient authority',
        $expected->getMessage()
      );
      $this->assertSame($_SESSION['loginAccount'], $sessionLoginAccount);
      $_POST = array();
      try {
        $this->assertFalse($testObject->AuthorizeAs(LoginAccountType::SYSTEM_ADMINISTRATOR()));
      } catch (Unauthorized401Exception $expected) {
        $this->assertSame('Unauthenticated or insufficient authority', $expected->getMessage());
        $this->assertSame($_SESSION['loginAccount'], $sessionLoginAccount);
        return;
      }
    }
    $this->fail('An expected Unauthorized401Exception has NOT been raised');
  }

  /**
   * Collection of assertions for \svelte\http\Session::authorizeAs()
   *  with no $_SESSION['LoginAccount'] or a set of valid credentials.
   * - assert throws \svelte\http\Unauthorized401Exception
   *   - with message: *'Unauthenticated or insufficient authority'*
   * - assert $_SESSION['loginAccount'] NOT set.
   * @link svelte.http.Session#method_authorizeAs svelte\http\Session::AuthorizeAs()
   */
  public function testAuthorizeAsNoCredentials()
  {
    $_SESSION = array();
    $_POST = array();
    SETTING::$TEST_RESET_SESSION = TRUE;
    $testObject = Session::getInstance();
    try {
      $testObject->authorizeAs(LoginAccountType::REGISTERED());
    } catch (Unauthorized401Exception $expected) {
      $this->assertSame('Unauthenticated or insufficient authority', $expected->getMessage());
      $this->assertFalse(isset($_SESSION['loginAccount']));
      return;
    }
    $this->fail('An expected Unauthorized401Exception has NOT been raised');
  }

  /**
   * Collection of assertions for \svelte\http\Session::authorizeAs()
   *  with no $_SESSION['LoginAccount'] or a set of valid credentials but with $_POST data.
   * - assert throws \svelte\http\Unauthorized401Exception
   *   - with message: *'Attempting POST to resource REQUIRING authentication or insufficient authority'*
   * - assert $_POST data array stored in $_SESSION as $_SESSION['post_array']
   * - assert $_SESSION['loginAccount'] NOT set.
   * @link svelte.http.Session#method_authorizeAs svelte\http\Session::AuthorizeAs()
   */
  public function testAuthorizeAsNoCredentialsWithPost()
  {
    $postArray = array(
      'record-name:key:property-a' => 'valueA',
      'record-name:key:property-b' => 'valueB',
      'record-name:key:property-c' => 'valueC'
    );
    $_SESSION = array();
    $_POST = $postArray;
    SETTING::$TEST_RESET_SESSION = TRUE;
    $testObject = Session::getInstance();
    try {
      $testObject->authorizeAs(LoginAccountType::REGISTERED());
    } catch (Unauthorized401Exception $expected) {
      $this->assertSame(
        'Attempting POST to resource REQUIRING authentication or insufficient authority',
        $expected->getMessage()
      );
      $this->assertTrue(isset($_SESSION['post_array']));
      $this->assertSame($_SESSION['post_array'], $postArray);
      $this->assertFalse(isset($_SESSION['loginAccount']));
      return;
    }
    $this->fail('An expected Unauthorized401Exception has NOT been raised');
  }

  /**
   * Collection of assertions for \svelte\http\Session::authorizeAs()
   *  while providing malformed login-email.
   * - assert throws \svelte\http\Unauthorized401Exception
   *   - with message: *'Invalid email format'*
   * - assert $_POST login related data expunged.
   * - assert $_SESSION['loginAccount'] NOT set.
   * @link svelte.http.Session#method_authorizeAs svelte\http\Session::AuthorizeAs()
   */
  public function testAuthorizeAsWithMalformedEmail()
  {
    $_SESSION = array();
    $_POST['login-email'] = 'not.email.address';
    SETTING::$TEST_RESET_SESSION = TRUE;
    $testObject = Session::getInstance();
    try {
      $testObject->authorizeAs(LoginAccountType::REGISTERED());
    } catch (Unauthorized401Exception $expected) {
      $this->assertSame('Invalid email format', $expected->getMessage());
      $this->assertFalse(isset($_POST['login-email']));
      $this->assertFalse(isset($_SESSION['loginAccount']));
      return;
    }
    $this->fail('An expected Unauthorized401Exception has NOT been raised');
  }

  /**
   * Collection of assertions for \svelte\http\Session::authorizeAs()
   *  while ONLY providing login-email without login-password or [authenticatableUnit]:new:email.
   * - assert throws \svelte\http\Unauthorized401Exception
   *   - with message: *'SHOULD NEVER REACH HERE!'*
   * - assert $_POST login related data expunged.
   * - assert $_POST data array stored in $_SESSION as $_SESSION['post_array']
   * - assert $_SESSION['loginAccount'] NOT set.
   * @link svelte.http.Session#method_authorizeAs svelte\http\Session::AuthorizeAs()
   */
  public function testAuthorizeAsWithEmailNoPassword()
  {
    $additionalPostdata = array(
      'record-name:key:property-a' => 'valueA',
      'record-name:key:property-b' => 'valueB',
      'record-name:key:property-c' => 'valueC'
    );
    $_SESSION = array();
    $_POST = $additionalPostdata;
    $_POST['login-email'] = self::$sessionLoginAccountEmail;
    SETTING::$TEST_RESET_SESSION = TRUE;
    $testObject = Session::getInstance();
    try {
      $testObject->authorizeAs(LoginAccountType::REGISTERED());
    } catch (Unauthorized401Exception $expected) {
      $this->assertSame('SHOULD NEVER REACH HERE!', $expected->getMessage());
      $this->assertFalse(isset($_POST['login-email']));
      $this->assertSame($_SESSION['post_array'], $additionalPostdata);
      $this->assertFalse(isset($_SESSION['loginAccount']));
      return;
    }
    $this->fail('An expected Unauthorized401Exception has NOT been raised');
  }

  /**
   * Collection of assertions for \svelte\http\Session::authorizeAs()
   *  while logging-in with an email NOT in database.
   * - assert throws \svelte\http\Unauthorized401Exception
   *   - with message: *'Account (email) NOT in database'*
   * - assert $_POST login related data expunged.
   * - assert $_POST data array stored in $_SESSION as $_SESSION['post_array']
   * - assert $_SESSION['loginAccount'] NOT set.
   * @link svelte.http.Session#method_authorizeAs svelte\http\Session::AuthorizeAs()
   */
  public function testAuthorizeAsEmailNotInDataStore()
  {
    $additionalPostdata = array(
      'record-name:key:property-a' => 'valueA',
      'record-name:key:property-b' => 'valueB',
      'record-name:key:property-c' => 'valueC'
    );
    $_SESSION = array();
    $_POST = $additionalPostdata;
    $_POST['login-email'] = 'unregistered.email@domain.com';
    $_POST['login-password'] = self::$unencryptedPassword;
    SETTING::$TEST_RESET_SESSION = TRUE;
    $testObject = Session::getInstance();
    try {
      $testObject->authorizeAs(LoginAccountType::REGISTERED());
    } catch (Unauthorized401Exception $expected) {
      $this->assertSame('Account (email) NOT in database', $expected->getMessage());
      $this->assertFalse(isset($_POST['login-email']));
      $this->assertFalse(isset($_POST['login-password']));
      $this->assertSame($_SESSION['post_array'], $additionalPostdata);
      $this->assertFalse(isset($_SESSION['loginAccount']));
      return;
    }
    $this->fail('An expected Unauthorized401Exception has NOT been raised');
  }

  /**
   * Collection of assertions for \svelte\http\Session::authorizeAs()
   *  while logging-in with an invalid password.
   * - assert throws \svelte\http\Unauthorized401Exception
   *   - with message: *'Invalid password or insufficient privileges'*
   * - assert $_POST login related data expunged.
   * - assert $_POST data array stored in $_SESSION as $_SESSION['post_array']
   * - assert $_SESSION['loginAccount'] NOT set.
   * @link svelte.http.Session#method_authorizeAs svelte\http\Session::AuthorizeAs()
   */
  public function testAuthorizeAsWithInvalidPassword()
  {
    $additionalPostdata = array(
      'record-name:key:property-a' => 'valueA',
      'record-name:key:property-b' => 'valueB',
      'record-name:key:property-c' => 'valueC'
    );
    $_SESSION = array();
    $_POST = $additionalPostdata;
    $_POST['login-email'] = self::$sessionLoginAccountEmail;
    $_POST['login-password'] = 'b@dP@ssw0rd';
    SETTING::$TEST_RESET_SESSION = TRUE;
    $testObject = Session::getInstance();
    try {
      $testObject->authorizeAs(LoginAccountType::REGISTERED());
    } catch (Unauthorized401Exception $expected) {
      $this->assertSame('Invalid password or insufficient privileges', $expected->getMessage());
      $this->assertFalse(isset($_POST['login-email']));
      $this->assertFalse(isset($_POST['login-password']));
      $this->assertSame($_SESSION['post_array'], $additionalPostdata);
      $this->assertFalse(isset($_SESSION['loginAccount']));
      return;
    }
    $this->fail('An expected Unauthorized401Exception has NOT been raised');
  }

  /**
   * Collection of assertions for \svelte\http\Session::authorizeAs()
   *  while logging-in with a set of valid Credentials but insufficient privileges.
   * - assert throws \svelte\http\Unauthorized401Exception
   *   - with message: *'Invalid password or insufficient privileges'*
   * - assert $_POST login related data expunged.
   * - assert $_POST data array stored in $_SESSION as $_SESSION['post_array']
   * @link svelte.http.Session#method_authorizeAs svelte\http\Session::AuthorizeAs()
   */
  public function testAuthorizeAsWithInsufficientPrivileges()
  {
    $additionalPostdata = array(
      'record-name:key:property-a' => 'valueA',
      'record-name:key:property-b' => 'valueB',
      'record-name:key:property-c' => 'valueC'
    );
    $_SESSION = array();
    $_POST = $additionalPostdata;
    $_POST['login-email'] = self::$sessionLoginAccountEmail;
    $_POST['login-password'] = self::$unencryptedPassword;
    SETTING::$TEST_RESET_SESSION = TRUE;
    $testObject = Session::getInstance();
    try {
      $testObject->authorizeAs(LoginAccountType::ADMINISTRATOR_MANAGER());
    } catch (Unauthorized401Exception $expected) {
      $this->assertSame('Invalid password or insufficient privileges', $expected->getMessage());
      $this->assertFalse(isset($_POST['login-email']));
      $this->assertFalse(isset($_POST['login-password']));
      $this->assertSame($_SESSION['post_array'], $additionalPostdata);
      return;
    }
    $this->fail('An expected Unauthorized401Exception has NOT been raised');
  }

  /**
   * Collection of assertions for \svelte\http\Session::authorizeAs()
   *  while logging-in with valid Credentials and holding $_SESSION['post_array'] from previous request.
   * - assert $_POST login related data expunged.
   * - assert $_SESSION['post_array'] passed back to $_POST
   * - assert returns without interruption as has sufficient privileges
   * - assert $_SESSION['loginAccount'] reference is successfully set.
   * @link svelte.http.Session#method_authorizeAs svelte\http\Session::AuthorizeAs()
   */
  public function testAuthorizeAsWithValidCredentials()
  {
    $postArray = array(
      'record-name:key:property-a' => 'valueA',
      'record-name:key:property-b' => 'valueB',
      'record-name:key:property-c' => 'valueC'
    );
    $_SESSION['post_array'] = $postArray;
    $_POST['login-email'] = self::$sessionLoginAccountEmail;
    $_POST['login-password'] = self::$unencryptedPassword;
    SETTING::$TEST_RESET_SESSION = TRUE;
    $testObject = Session::getInstance();
    $testObject->authorizeAs(LoginAccountType::REGISTERED());
    $this->assertFalse(isset($_POST['login-email']));
    $this->assertFalse(isset($_POST['login-password']));
    $this->assertSame($postArray, $_POST);
    $this->assertTrue(isset($_SESSION['loginAccount']));
    $this->assertSame(self::$sessionLoginAccountEmail, $_SESSION['loginAccount']->email->value);
    $this->assertSame('aperson', $_SESSION['loginAccount']->uname->value);
    $this->assertSame('aperson', $_SESSION['loginAccount']->auPK->value);
    $this->assertSame('person', $_SESSION['loginAccount']->familyName->value);
    $this->assertSame('ann', $_SESSION['loginAccount']->givenName->value);
    $this->assertSame(
      $_SESSION['loginAccount']->getPropertyValue('encryptedPassword'),
      crypt(self::$unencryptedPassword, SETTING::$SECURITY_PASSWORD_SALT)
    );
  }

  /**
   * Collection of assertions for \svelte\http\Session::authorizeAs()
   *  with new authenticatible unit details to setup as new login account BUT emails mismatch.
   * - assert throws \svelte\http\Unauthorized401Exception
   *   - with message: *'New Authenticatible Unit Form: e-mail mismatch'*
   * - assert $_POST login related data expunged.
   * - assert $_POST data array stored in $_SESSION as $_SESSION['post_array']
   * - assert $_SESSION['loginAccount'] NOT set.
   * @link svelte.http.Session#method_authorizeAs svelte\http\Session::AuthorizeAs()
   */
  public function testAuthorizeAsNewLoginAccountEmailMismatch()
  {
    $additionalPostdata = array (
      'an-authenticatable-unit:new:uname' => 'user',
      'an-authenticatable-unit:new:email' => 'correct@email.com',
      'an-authenticatable-unit:new:family-name' => 'Surname',
      'an-authenticatable-unit:new:given-name' => 'Name'
    );
    $_SESSION = array();
    $_POST = $additionalPostdata;
    $_POST['login-email'] = 'misspell@email.com';
    SETTING::$TEST_RESET_SESSION = TRUE;
    $testObject = Session::getInstance();
    try {
      $this->assertTrue($testObject->AuthorizeAs(LoginAccountType::REGISTERED()));
    } catch (Unauthorized401Exception $expected) {
      $this->assertSame('New Authenticatable Unit Form: e-mail mismatch', $expected->getMessage());
      $this->assertFalse(isset($_POST['login-email']));
      $this->assertFalse(isset($_POST['an-authenticatable-unit:new:email']));
      $this->assertSame($_SESSION['post_array'], $additionalPostdata);
      $this->assertFalse(isset($_SESSION['loginAccount']));
      // TODO:mrenyard: test errorCollection
      return;
    }
    $this->fail('An expected Unauthorized401Exception has NOT been raised');
  }

  /**
   * Collection of assertions for \svelte\http\Session::authorizeAs()
   *  with new authenticatible unit details to setup as new login account BUT alreay exists.
   * - assert throws \svelte\http\Unauthorized401Exception
   *   - with message: *'Trying to create new login where one already exists!'*
   * - assert $_POST login related data expunged.
   * - assert $_POST data array stored in $_SESSION as $_SESSION['post_array']
   * - assert $_SESSION['loginAccount'] NOT set.
   * @link svelte.http.Session#method_authorizeAs svelte\http\Session::AuthorizeAs()
   */
  public function testAuthorizeAsNewLoginAccountEmailAlreadyExists()
  {
    $additionalPostdata = array (
      'an-authenticatable-unit:new:uname' => 'user',
      'an-authenticatable-unit:new:email' => self::$sessionLoginAccountEmail,
      'an-authenticatable-unit:new:family-name' => 'Surname',
      'an-authenticatable-unit:new:given-name' => 'Name'
    );
    $_SESSION = array();
    $_POST = $additionalPostdata;
    $_POST['login-email'] = self::$sessionLoginAccountEmail;
    SETTING::$TEST_RESET_SESSION = TRUE;
    $testObject = Session::getInstance();
    try {
      $this->assertTrue($testObject->AuthorizeAs(LoginAccountType::REGISTERED()));
    } catch (Unauthorized401Exception $expected) {
      $this->assertSame('Trying to create new login where one already exists!', $expected->getMessage());
      $this->assertFalse(isset($_POST['login-email']));
      $this->assertFalse(isset($_POST['person:new:email']));
      $this->assertSame($_SESSION['post_array'], $additionalPostdata);
      $this->assertFalse(isset($_SESSION['loginAccount']));
      // TODO:mrenyard: test errorCollection
      return;
    }
    $this->fail('An expected Unauthorized401Exception has NOT been raised');
  }

  /**
   * Collection of assertions for \svelte\http\Session::authorizeAs()
   *  with new authenticatible unit details to setup as a new login account.
   * - assert returns without interruption as has sufficient privileges.
   * - assert $_POST login related data expunged.
   * - assert $_POST data values retained as successful authorisation.
   * - assert $_SESSION['loginAccount'] reference is successfully set.
   * - assert $_SESSION['loginAccount'] isValid.
   * - assert $_SESSION['loginAccount'] properties are set as expected.
   * - assert related authenticatible unit properties are accessable throuth loginAccount.
   * - assert a password has been auto generated for loginAccount.
   * - assert both loginAccount and associated AuthenticatableUnit are updated through relevant BusinessModelManager.
   * @link svelte.http.Session#method_authorizeAs svelte\http\Session::AuthorizeAs()
   */
  public function testAuthorizeAsNewLoginAccount()
  {
    $additionalPostdata = array (
      'an-authenticatable-unit:new:uname' => 'user',
      'an-authenticatable-unit:new:email' => 'correct@email.com',
      'an-authenticatable-unit:new:family-name' => 'surname',
      'an-authenticatable-unit:new:given-name' => 'name'
    );
    $_SESSION = array();
    $_POST = $additionalPostdata;
    $_POST['login-email'] = 'correct@email.com';
    SETTING::$TEST_RESET_SESSION = TRUE;
    $testObject = Session::getInstance();
    $this->assertNull($testObject->AuthorizeAs(LoginAccountType::REGISTERED()));
    $this->assertFalse(isset($_POST['login-email']));
    $this->assertFalse(isset($_POST['login-password']));
    $this->assertFalse(isset($_SESSION['post_array']));
    $this->assertTrue(isset($_SESSION['loginAccount']));
    $this->assertTrue($_SESSION['loginAccount']->isValid);
    $this->assertSame('correct@email.com', $_SESSION['loginAccount']->email->value);
    $this->assertSame('user', $_SESSION['loginAccount']->auPK->value);
    $this->assertSame('user', $_SESSION['loginAccount']->uname->value);
    $this->assertSame('surname', $_SESSION['loginAccount']->familyName->value);
    $this->assertSame('name', $_SESSION['loginAccount']->givenName->value);
    $this->assertSame(
      crypt((string)$_SESSION['loginAccount']->getUnencryptedPassword(), \svelte\SETTING::$SECURITY_PASSWORD_SALT),
      MockBusinessModelManager::$loginAccountDataObject->encryptedPassword
    );
    $this->assertTrue(isset(MockBusinessModelManager::$updateLog['svelte\model\business\AnAuthenticatableUnit:user']));
    $this->assertTrue(isset(MockBusinessModelManager::$updateLog['svelte\model\business\LoginAccount:user']));
  }
}
