<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantProductOfferStorage\Business\Deleter;

use Orm\Zed\ProductOffer\Persistence\Map\SpyProductOfferTableMap;
use Spryker\Zed\MerchantProductOfferStorage\Dependency\Facade\MerchantProductOfferStorageToEventBehaviorFacadeInterface;
use Spryker\Zed\MerchantProductOfferStorage\Persistence\MerchantProductOfferStorageEntityManagerInterface;

class ProductOfferStorageDeleter implements ProductOfferStorageDeleterInterface
{
    /**
     * @var \Spryker\Zed\MerchantProductOfferStorage\Dependency\Facade\MerchantProductOfferStorageToEventBehaviorFacadeInterface
     */
    protected $eventBehaviorFacade;

    /**
     * @var \Spryker\Zed\MerchantProductOfferStorage\Persistence\MerchantProductOfferStorageEntityManagerInterface
     */
    protected $merchantProductOfferStorageEntityManager;

    /**
     * @param \Spryker\Zed\MerchantProductOfferStorage\Dependency\Facade\MerchantProductOfferStorageToEventBehaviorFacadeInterface $eventBehaviorFacade
     * @param \Spryker\Zed\MerchantProductOfferStorage\Persistence\MerchantProductOfferStorageEntityManagerInterface $merchantProductOfferStorageEntityManager
     */
    public function __construct(
        MerchantProductOfferStorageToEventBehaviorFacadeInterface $eventBehaviorFacade,
        MerchantProductOfferStorageEntityManagerInterface $merchantProductOfferStorageEntityManager
    ) {
        $this->eventBehaviorFacade = $eventBehaviorFacade;
        $this->merchantProductOfferStorageEntityManager = $merchantProductOfferStorageEntityManager;
    }

    /**
     * @param \Generated\Shared\Transfer\EventEntityTransfer[] $eventTransfers
     *
     * @return void
     */
    public function deleteProductOfferStorageCollectionByProductOfferReferenceEvents(array $eventTransfers): void
    {
        $productOfferReferences = $this->eventBehaviorFacade->getEventTransfersAdditionalValues($eventTransfers, SpyProductOfferTableMap::COL_PRODUCT_OFFER_REFERENCE);

        if (!$productOfferReferences) {
            return;
        }

        $this->deleteProductOfferStorageCollectionByProductOfferReferences($productOfferReferences);
    }

    /**
     * @param string[] $productOfferReferences
     *
     * @return void
     */
    public function deleteProductOfferStorageCollectionByProductOfferReferences(array $productOfferReferences): void
    {
        $this->merchantProductOfferStorageEntityManager->deleteProductOfferStorageEntitiesByProductOfferReferences($productOfferReferences);
    }

    /**
     * @param string[] $productOfferReferences
     * @param string $storeName
     *
     * @return void
     */
    public function deleteProductOfferStorageEntitiesByProductOfferReferencesAndStore(array $productOfferReferences, string $storeName): void
    {
        $this->merchantProductOfferStorageEntityManager->deleteProductOfferStorageEntitiesByProductOfferReferencesAndStore(
            $productOfferReferences,
            $storeName
        );
    }
}
