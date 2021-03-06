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
use svelte\model\business\BusinessModel;
use svelte\model\business\iBusinessModelDefinition;
use svelte\condition\Filter;

/**
 * Defined abstract for business model managers that manage all models within systems business domain.
 *
 * RESPONSIBILITIES
 * - Create and manage business models
 * - Act as intermediary to any permanent data store
 * - Ensure only one version of any data row in system
 */
abstract class BusinessModelManager extends SvelteObject
{
  /**
   * Get instance - same instance on every request (singleton) within same http request.
   */
  abstract public static function getInstance() : BusinessModelManager;

  /**
   * Returns requested Model.
   * @param \svelte\model\business\iBusinessModelDefinition $definition Definition of requested Model
   * @param \svelte\condition\Filter $filter Optional Filter to be apply to BusinessModel
   * @param int $fromIndex Optional index of first entry in a collection
   * @return \svelte\model\business\BusinessModel Relevant requested BusinessModel
   * @throws \DomainException When {@link \svelte\model\business\BusinessModel}(s) NOT found
   * @throws \svelte\model\business\DataFetchException When unable to fetch from data store
   */
  abstract public function getBusinessModel(iBusinessModelDefinition $definition, Filter $filter = null, $fromIndex = null) : BusinessModel;

  /**
   * Update {@link BusinessModel} to any permanent data store
   * @param \svelte\model\business\BusinessModel $model BusinessModel object to be updated
   * @throws \InvalidArgumentException when {@link \svelte\model\business\BusinessModel}
   *  was not initially retrieved using *this* BusinessModelManager
   * @throws \svelte\model\business\DataWriteException When unable to write to data store
   */
  abstract public function update(BusinessModel $model);

  /**
   * Ensure update of any out of sync Records with any permanent data store.
   * Uses the following properties of {@link \svelte\model\business\Record} for varification:
   * - {@link \svelte\model\business\Record::isValid}
   * - {@link \svelte\model\business\Record::isModified}
   * @throws \svelte\model\business\DataWriteException When unable to write to data store
   */
  abstract public function updateAny();
}
