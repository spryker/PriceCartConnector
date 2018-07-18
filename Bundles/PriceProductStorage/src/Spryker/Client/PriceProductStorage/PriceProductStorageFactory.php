<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\PriceProductStorage;

use Spryker\Client\Kernel\AbstractFactory;
use Spryker\Client\PriceProductStorage\Expander\ProductViewPriceExpander;
use Spryker\Client\PriceProductStorage\Storage\PriceAbstractStorageReader;
use Spryker\Client\PriceProductStorage\Storage\PriceConcreteStorageReader;
use Spryker\Client\PriceProductStorage\Storage\PriceProductMapper;
use Spryker\Client\PriceProductStorage\Storage\PriceProductMapperInterface;
use Spryker\Client\PriceProductStorage\Storage\PriceProductStorageKeyGenerator;

class PriceProductStorageFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Client\PriceProductStorage\Expander\ProductViewPriceExpanderInterface
     */
    public function createProductViewPriceExpander()
    {
        return new ProductViewPriceExpander(
            $this->createPriceAbstractStorageReader(),
            $this->createPriceConcreteStorageReader(),
            $this->getPriceProductClient()
        );
    }

    /**
     * @return \Spryker\Client\PriceProductStorage\Storage\PriceAbstractStorageReaderInterface
     */
    public function createPriceAbstractStorageReader()
    {
        return new PriceAbstractStorageReader(
            $this->getStorage(),
            $this->createPriceProductStorageKeyGenerator(),
            $this->createPriceProductMapper(),
            $this->getPriceDimensionPlugins()
        );
    }

    /**
     * @return \Spryker\Client\PriceProductStorage\Storage\PriceConcreteStorageReaderInterface
     */
    public function createPriceConcreteStorageReader()
    {
        return new PriceConcreteStorageReader(
            $this->getStorage(),
            $this->createPriceProductStorageKeyGenerator(),
            $this->createPriceProductMapper(),
            $this->getPriceDimensionPlugins()
        );
    }

    /**
     * @return \Spryker\Client\PriceProductStorage\Dependency\Client\PriceProductStorageToStorageInterface
     */
    public function getStorage()
    {
        return $this->getProvidedDependency(PriceProductStorageDependencyProvider::CLIENT_STORAGE);
    }

    /**
     * @return \Spryker\Client\PriceProductStorage\Dependency\Client\PriceProductStorageToPriceProductInterface
     */
    public function getPriceProductClient()
    {
        return $this->getProvidedDependency(PriceProductStorageDependencyProvider::CLIENT_PRICE_PRODUCT);
    }

    /**
     * @return \Spryker\Client\PriceProductStorage\Dependency\Client\PriceProductStorageToStoreClientInterface
     */
    public function getStoreClient()
    {
        return $this->getProvidedDependency(PriceProductStorageDependencyProvider::CLIENT_STORE);
    }

    /**
     * @return \Spryker\Client\PriceProductStorage\Storage\PriceProductStorageKeyGeneratorInterface
     */
    public function createPriceProductStorageKeyGenerator()
    {
        return new PriceProductStorageKeyGenerator($this->getSynchronizationService(), $this->getStoreClient());
    }

    /**
     * @return \Spryker\Client\PriceProductStorage\Dependency\Service\PriceProductStorageToSynchronizationServiceBridge
     */
    public function getSynchronizationService()
    {
        return $this->getProvidedDependency(PriceProductStorageDependencyProvider::SERVICE_SYNCHRONIZATION);
    }

    /**
     * @return \Spryker\Client\PriceProductStorageExtension\Dependency\Plugin\PriceProductStoragePriceDimensionPluginInterface[]
     */
    public function getPriceDimensionPlugins(): array
    {
        return $this->getProvidedDependency(PriceProductStorageDependencyProvider::PLUGIN_STORAGE_PRICE_DIMENSION);
    }

    /**
     * @return \Spryker\Client\PriceProductStorage\Storage\PriceProductMapperInterface
     */
    public function createPriceProductMapper(): PriceProductMapperInterface
    {
        return new PriceProductMapper();
    }
}