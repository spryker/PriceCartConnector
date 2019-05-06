<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\CompanyBusinessUnitsRestApi;

use Spryker\Glue\CompanyBusinessUnitsRestApi\Processor\CompanyBusinessUnit\Mapper\CompanyBusinessUnitMapper;
use Spryker\Glue\CompanyBusinessUnitsRestApi\Processor\CompanyBusinessUnit\Mapper\CompanyBusinessUnitMapperInterface;
use Spryker\Glue\CompanyBusinessUnitsRestApi\Processor\CompanyBusinessUnit\Relationship\CompanyBusinessUnitResourceRelationshipExpander;
use Spryker\Glue\CompanyBusinessUnitsRestApi\Processor\CompanyBusinessUnit\Relationship\CompanyBusinessUnitResourceRelationshipExpanderInterface;
use Spryker\Glue\CompanyBusinessUnitsRestApi\Processor\Customer\CustomerExpander;
use Spryker\Glue\CompanyBusinessUnitsRestApi\Processor\Customer\CustomerExpanderInterface;
use Spryker\Glue\Kernel\AbstractFactory;

class CompanyBusinessUnitsRestApiFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Glue\CompanyBusinessUnitsRestApi\Processor\CompanyBusinessUnit\Relationship\CompanyBusinessUnitResourceRelationshipExpanderInterface
     */
    public function createCompanyBusinessUnitResourceRelationshipExpander(): CompanyBusinessUnitResourceRelationshipExpanderInterface
    {
        return new CompanyBusinessUnitResourceRelationshipExpander(
            $this->getResourceBuilder(),
            $this->createCompanyBusinessUnitMapper()
        );
    }

    /**
     * @return \Spryker\Glue\CompanyBusinessUnitsRestApi\Processor\CompanyBusinessUnit\Mapper\CompanyBusinessUnitMapperInterface
     */
    public function createCompanyBusinessUnitMapper(): CompanyBusinessUnitMapperInterface
    {
        return new CompanyBusinessUnitMapper();
    }

    /**
     * @return \Spryker\Glue\CompanyBusinessUnitsRestApi\Processor\Customer\CustomerExpanderInterface
     */
    public function createCustomerExpander(): CustomerExpanderInterface
    {
        return new CustomerExpander();
    }
}