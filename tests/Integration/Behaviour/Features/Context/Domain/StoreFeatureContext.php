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

namespace Tests\Integration\Behaviour\Features\Context\Domain;

use PHPUnit\Framework\Assert as Assert;
use PrestaShop\PrestaShop\Core\Domain\Store\Command\ToggleStoreStatusCommand;
use PrestaShop\PrestaShop\Core\Domain\Store\Query\GetStoreForEditing;

class StoreFeatureContext extends AbstractDomainFeatureContext
{
    private const DUMMY_STORE_ID = 1;

    /**
     * @When I toggle :reference
     *
     * @param string $reference
     */
    public function disableStoreWithReference(string $reference): void
    {
        $toggleStatusCommand = new ToggleStoreStatusCommand(self::DUMMY_STORE_ID);
        $this->getCommandBus()->handle($toggleStatusCommand);
    }

    /**
     * @Then /^the store "(.*)" should have status (enabled|disabled)$/
     *
     * @param string $reference
     * @param string $status
     */
    public function isStoreToggledWithReference(string $reference, string $status): void
    {
        $isEnabled = $status === 'enabled';
        $storeForEditingQuery = new GetStoreForEditing(self::DUMMY_STORE_ID);
        $storeUpdated = $this->getQueryBus()->handle($storeForEditingQuery);
        Assert::assertEquals((bool) $storeUpdated->isActive(), $isEnabled);
    }
}
