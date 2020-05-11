<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesDataExport\Persistence;

use Generated\Shared\Transfer\DataExportConfigurationTransfer;

interface SalesDataExportRepositoryInterface
{
    /**
     * @param \Generated\Shared\Transfer\DataExportConfigurationTransfer $dataExportConfigurationTransfer
     * @param int $offset
     * @param int $limit
     *
     * @return array
     */
    public function getOrdersData(DataExportConfigurationTransfer $dataExportConfigurationTransfer, int $offset, int $limit): array;

    /**
     * @param \Generated\Shared\Transfer\DataExportConfigurationTransfer $dataExportConfigurationTransfer
     * @param int $offset
     * @param int $limit
     *
     * @return array
     */
    public function getOrderItemsData(DataExportConfigurationTransfer $dataExportConfigurationTransfer, int $offset, int $limit) : array;
}
