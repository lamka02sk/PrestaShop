<?php
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

declare(strict_types=1);

namespace PrestaShop\PrestaShop\Core\Grid\Data\Factory;

use CartRule;
use Customer;
use PrestaShop\PrestaShop\Core\Grid\Data\GridData;
use PrestaShop\PrestaShop\Core\Grid\Record\RecordCollection;
use PrestaShop\PrestaShop\Core\Grid\Search\SearchCriteriaInterface;

/**
 * Class CustomerDiscountGridDataFactory is responsible of returning grid data for customer's discounts.
 */
final class CustomerDiscountGridDataFactory implements GridDataFactoryInterface
{
    /**
     * @var int
     */
    private $contextLangId;
    /**
     * @var Customer
     */
    private $customer;

    /**
     * @param int $contextLangId
     * @param Customer $customer
     */
    public function __construct(
        int $contextLangId,
        Customer $customer
    ) {
        $this->contextLangId = $contextLangId;
        $this->customer = $customer;
    }

    /**
     * {@inheritdoc}
     */
    public function getData(SearchCriteriaInterface $searchCriteria)
    {
        $allDiscounts = CartRule::getAllCustomerCartRules(
            $this->customer->id
        );

        $discountsToDisplay = array_slice(
            $allDiscounts,
            (int) $searchCriteria->getOffset(),
            (int) $searchCriteria->getLimit()
        );

        $records = new RecordCollection($discountsToDisplay);

        return new GridData(
            $records,
            count($allDiscounts)
        );
    }
}
