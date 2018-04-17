<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SharedCart;

use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;
use Spryker\Zed\SharedCart\Dependency\Facade\SharedCartToCustomerFacadeBridge;
use Spryker\Zed\SharedCart\Dependency\Facade\SharedCartToQuoteFacadeBridge;

class SharedCartDependencyProvider extends AbstractBundleDependencyProvider
{
    public const FACADE_QUOTE = 'FACADE_QUOTE';
    public const FACADE_CUSTOMER = 'FACADE_CUSTOMER';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideBusinessLayerDependencies(Container $container)
    {
        $container = $this->addCustomerFacade($container);
        $container = $this->addQuoteFacade($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addQuoteFacade(Container $container)
    {
        $container[static::FACADE_QUOTE] = function (Container $container) {
            return new SharedCartToQuoteFacadeBridge($container->getLocator()->quote()->facade());
        };

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addCustomerFacade(Container $container)
    {
        $container[static::FACADE_CUSTOMER] = function (Container $container) {
            return new SharedCartToCustomerFacadeBridge($container->getLocator()->customer()->facade());
        };

        return $container;
    }
}
