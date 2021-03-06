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
namespace svelte\model\business;

use svelte\core\SvelteObject;
use svelte\core\iCollection;
use svelte\core\Collection;
use svelte\core\Str;
use svelte\model\business\BusinessModel;

/**
 * Referance and maintain a collection of \svelte\model\business\Records.
 *
 * RESPONSIBILITIES
 * - Implement methods for property access
 * - Implement methods for validity checking & reporting
 * - Provide access to this Collection
 * - Provide methods to maintain a Collection of {@link Record}s
 *
 * COLLABORATORS
 * - Collection of {@link \svelte\model\business\Record}s
 */
abstract class RecordCollection extends BusinessModel implements iCollection
{
  /**
   * Default constructor for collection of \svelte\model\business\Records.
   * - Sets composite type for this collection as <i>this</i> class-name with string <i>Collection</i> truncated:
   *  - e.g. {@link \svelte\model\business\UserCollection} would expect to referance only {@link \svelte\model\business\User}s.
   */
  final public function __construct()
  {
    parent::__construct(
      new Collection(
        Str::set(preg_replace('/Collection$/', '', get_class($this)))
      )
    );
  }

  /**
   * Get ID (URN)
   * **DO NOT CALL DIRECTLY, USE this->id;**
   * @return \svelte\core\Str Unique identifier for *this*
   */
  final public function get_id() : Str
  {
    return $this->processType((string)$this, TRUE);
  }

  /**
   * Add a reference (Record), to this collection.
   * @param \svelte\core\SvelteObject $object SvelteObject reference to be added (Record)
   * @throws \InvalidArgumentException When provided object NOT expected type (Record)
   */
  final public function add(SvelteObject $object)
  {
    parent::offsetSet($this->count, $object);
  }

  /**
   * ArrayAccess method offsetSet, DO NOT USE.
   * @param mixed $offset Index to place provided object.
   * @param mixed $object SvelteObject to be placed at provided index.
   * @throws \BadMethodCallException Array access setting is not allowed.
   */
  public function offsetSet($offset, $object)
  {
    throw new \BadMethodCallException('Array access setting is not allowed.');
  }

  /**
   * ArrayAccess method offsetUnset, DO NOT USE.
   * @param mixed $offset API to match \ArrayAccess interface
   * @throws \BadMethodCallException Array access unsetting is not allowed.
   */
  public function offsetUnset($offset)
  {
    throw new \BadMethodCallException('Array access unsetting is not allowed.');
  }
}
