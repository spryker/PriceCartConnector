<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CategoryImage\Business\Model;

use Generated\Shared\Transfer\CategoryImageSetTransfer;
use Generated\Shared\Transfer\CategoryTransfer;

interface ReaderInterface
{
    /**
     * @param int $idCategory
     *
     * @return \Generated\Shared\Transfer\CategoryImageSetTransfer[]
     */
    public function findCategoryImagesSetCollectionByCategoryId(int $idCategory): array;

    /**
     * @param int $idCategoryImageSet
     *
     * @return \Generated\Shared\Transfer\CategoryImageSetTransfer|null
     */
    public function findCategoryImagesSetCollectionById(int $idCategoryImageSet): ?CategoryImageSetTransfer;

    /**
     * @param \Generated\Shared\Transfer\CategoryTransfer $categoryTransfer
     *
     * @return \Generated\Shared\Transfer\CategoryTransfer
     */
    public function expandProductAbstractWithImageSets(CategoryTransfer $categoryTransfer): CategoryTransfer;
}
