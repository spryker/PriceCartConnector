<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\BusinessOnBehalfGui\Communication\Plugin\CompanyUserGui;

use Spryker\Zed\CompanyUserGuiExtension\Dependency\Plugin\CompanyUserTableActionLinksExpanderPluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Spryker\Zed\BusinessOnBehalfGui\Communication\BusinessOnBehalfGuiCommunicationFactory getFactory()
 * @method \Spryker\Zed\BusinessOnBehalfGui\BusinessOnBehalfGuiConfig getConfig()
 */
class CompanyUserTableAttachToBusinessUnitActionLinksExpanderPlugin extends AbstractPlugin implements CompanyUserTableActionLinksExpanderPluginInterface
{
    /**
     * {@inheritdoc}
     * - Adds new "Attach to BU" button in actions for company user table for add link of attach customer to business unit page
     *
     * @api
     *
     * @param array $companyUserDataItem
     * @param string[] $actionButtons
     *
     * @return string[]
     */
    public function expandActionLinks(array $companyUserDataItem, array $actionButtons): array
    {
        return $this->getFactory()
            ->createGuiButtonCreator()
            ->addAttachToBusinessUnitButtonForCompanyUserTable($companyUserDataItem, $actionButtons);
    }
}
