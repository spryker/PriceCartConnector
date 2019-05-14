<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\SharedCartsRestApi;

use Spryker\Glue\Kernel\AbstractFactory;
use Spryker\Glue\SharedCartsRestApi\Processor\SharedCart\Mapper\SharedCartMapper;
use Spryker\Glue\SharedCartsRestApi\Processor\SharedCart\Mapper\SharedCartMapperInterface;
use Spryker\Glue\SharedCartsRestApi\Processor\SharedCart\Reader\SharedCartReader;
use Spryker\Glue\SharedCartsRestApi\Processor\SharedCart\Reader\SharedCartReaderInterface;
use Spryker\Glue\SharedCartsRestApi\Processor\SharedCart\Relationship\SharedCartExpander;
use Spryker\Glue\SharedCartsRestApi\Processor\SharedCart\Relationship\SharedCartExpanderInterface;

/**
 * @method \Spryker\Client\SharedCartsRestApi\SharedCartsRestApiClientInterface getClient()
 */
class SharedCartsRestApiFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Glue\SharedCartsRestApi\Processor\SharedCart\Relationship\SharedCartExpanderInterface
     */
    public function createSharedCartExpander(): SharedCartExpanderInterface
    {
        return new SharedCartExpander(
            $this->createSharedCartReader(),
            $this->createSharedCartMapper(),
            $this->getResourceBuilder()
        );
    }

    /**
     * @return \Spryker\Glue\SharedCartsRestApi\Processor\SharedCart\Mapper\SharedCartMapperInterface
     */
    public function createSharedCartMapper(): SharedCartMapperInterface
    {
        return new SharedCartMapper();
    }

    /**
     * @return \Spryker\Glue\SharedCartsRestApi\Processor\SharedCart\Reader\SharedCartReaderInterface
     */
    public function createSharedCartReader(): SharedCartReaderInterface
    {
        return new SharedCartReader(
            $this->getClient()
        );
    }
}
