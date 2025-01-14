/**
 * Copyright since 2007 PrestaShop SA and Contributors
 * PrestaShop is an International Registered Trademark & Property of PrestaShop SA
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.md.
 * It is also available through the world-wide-web at this URL:
 * https://opensource.org/licenses/OSL-3.0
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@prestashop.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade PrestaShop to newer
 * versions in the future. If you wish to customize PrestaShop for your
 * needs please refer to https://devdocs.prestashop.com/ for more information.
 *
 * @author    PrestaShop SA and Contributors <contact@prestashop.com>
 * @copyright Since 2007 PrestaShop SA and Contributors
 * @license   https://opensource.org/licenses/OSL-3.0 Open Software License (OSL 3.0)
 */

import Router from '@components/router';
import ServiceType from '@PSTypes/services';

const {$} = window;

export default class CombinationsService implements ServiceType {
  productId: number;

  router: Router;

  filters: Record<string, any>;

  orderBy: string | null;

  orderWay: string | null;

  /**
   * @param {Number} productId
   */
  constructor(productId: number) {
    this.productId = productId;
    this.router = new Router();
    this.filters = {};
    this.orderBy = null;
    this.orderWay = null;
  }

  /**
   * @param {Number} offset
   * @param {Number} limit
   *
   * @returns {Promise}
   */
  fetch(offset: number, limit: number): JQuery.jqXHR<any> {
    const filterId = `product_combinations_${this.productId}`;
    const requestParams: Record<string, any> = {};
    // Required for route generation
    requestParams.productId = this.productId;

    // These are the query parameters
    requestParams[filterId] = {};
    requestParams[filterId].offset = offset;
    requestParams[filterId].limit = limit;
    requestParams[filterId].filters = this.filters;
    if (this.orderBy !== null) {
      requestParams[filterId].orderBy = this.orderBy;
    }
    if (this.orderWay !== null) {
      requestParams[filterId].sortOrder = this.orderWay;
    }

    return $.get(this.router.generate('admin_products_combinations', requestParams));
  }

  /**
   * @param {number} combinationId
   *
   * @returns {Promise}
   */
  deleteCombination(combinationId: number): JQuery.jqXHR<any> {
    return $.ajax({
      url: this.router.generate('admin_products_combinations_delete_combination', {
        combinationId,
      }),
      type: 'DELETE',
    });
  }

  /**
   * @param {Number} combinationId
   * @param {Object} data
   *
   * @returns {Promise}
   */
  updateListedCombination(combinationId: number, data: Record<string, any>): JQuery.jqXHR<any> {
    return $.ajax({
      url: this.router.generate('admin_products_combinations_update_combination_from_listing', {
        combinationId,
      }),
      data,
      type: 'PATCH',
    });
  }

  /**
   * @param {Object} data Attributes indexed by attributeGroupId { 1: [23, 34], 3: [45, 52]}
   */
  generateCombinations(data: Record<string, any>): JQuery.jqXHR<any> {
    return $.ajax({
      url: this.router.generate('admin_products_combinations_generate', {
        productId: this.productId,
      }),
      data,
      method: 'POST',
    });
  }

  /**
   * @returns {Promise}
   */
  getCombinationIds(): JQuery.jqXHR<any> {
    return $.get(
      this.router.generate('admin_products_combinations_ids', {
        productId: this.productId,
      }),
    );
  }

  /**
   * @param {string} orderBy
   * @param {string} orderWay
   */
  setOrderBy(orderBy: string, orderWay: string): void {
    this.orderBy = orderBy;
    this.orderWay = orderWay.toLowerCase() === 'desc' ? 'DESC' : 'ASC';
  }

  /**
   * @returns {Object}
   */
  getFilters(): Record<string, any> {
    return this.filters;
  }

  /**
   * @param {Object} filters
   */
  setFilters(filters: Record<string, any>): void {
    this.filters = filters;
  }
}
