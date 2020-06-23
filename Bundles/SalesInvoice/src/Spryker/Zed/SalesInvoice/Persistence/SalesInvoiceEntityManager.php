<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesInvoice\Persistence;

use Generated\Shared\Transfer\OrderInvoiceTransfer;
use Orm\Zed\Sales\Persistence\Map\SpySalesOrderItemTableMap;
use Orm\Zed\SalesInvoice\Persistence\Map\SpySalesOrderInvoiceTableMap;
use Orm\Zed\SalesInvoice\Persistence\SpySalesOrderInvoice;
use Spryker\Zed\Kernel\Persistence\AbstractEntityManager;

/**
 * @method \Spryker\Zed\SalesInvoice\Persistence\SalesInvoicePersistenceFactory getFactory()
 */
class SalesInvoiceEntityManager extends AbstractEntityManager implements SalesInvoiceEntityManagerInterface
{
    /**
     * @param \Generated\Shared\Transfer\OrderInvoiceTransfer $orderInvoiceTransfer
     *
     * @return \Generated\Shared\Transfer\OrderInvoiceTransfer
     */
    public function createOrderInvoice(OrderInvoiceTransfer $orderInvoiceTransfer): OrderInvoiceTransfer
    {
        $orderInvoiceEntity = new SpySalesOrderInvoice();
        $orderInvoiceEntity->fromArray($orderInvoiceTransfer->toArray());
        $orderInvoiceEntity->setFkSalesOrder($orderInvoiceTransfer->getIdSalesOrder());

        $orderInvoiceEntity->save();

        return $orderInvoiceTransfer->setIdSalesOrderInvoice($orderInvoiceEntity->getIdSalesOrderInvoice());
    }

    /**
     * @param int $idSalesOrder
     * @param int $idSalesOrderInvoice
     *
     * @return void
     */
    public function updateOrderItemInvoiceIdByOrderId(int $idSalesOrder, int $idSalesOrderInvoice): void
    {
        $columnPhpName = SpySalesOrderItemTableMap::translateFieldName(
            SpySalesOrderItemTableMap::COL_FK_SALES_ORDER_INVOICE,
            SpySalesOrderItemTableMap::TYPE_COLNAME,
            SpySalesOrderItemTableMap::TYPE_PHPNAME
        );

        $this->getFactory()
            ->getSalesOrderItemPropelQuery()
            ->filterByFkSalesOrder($idSalesOrder)
            ->update([
                $columnPhpName => $idSalesOrderInvoice,
            ]);
    }

    /**
     * @param int[] $orderInvoiceIds
     *
     * @return void
     */
    public function markOrderInvoicesAsEmailSent(array $orderInvoiceIds): void
    {
        $columnPhpName = SpySalesOrderInvoiceTableMap::translateFieldName(
            SpySalesOrderInvoiceTableMap::COL_EMAIL_SENT,
            SpySalesOrderInvoiceTableMap::TYPE_COLNAME,
            SpySalesOrderInvoiceTableMap::TYPE_PHPNAME
        );

        $this->getFactory()
            ->getSalesOrderInvoicePropelQuery()
            ->update([
                 $columnPhpName => true,
            ]);
    }
}
