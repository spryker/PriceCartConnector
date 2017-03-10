<?php
/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Queue\Persistence;

use Orm\Zed\Queue\Persistence\Base\SpyQueueProcessQuery;
use Spryker\Zed\Kernel\Persistence\AbstractQueryContainer;
use Spryker\Zed\PropelOrm\Business\Runtime\ActiveQuery\Criteria;

/**
 * @method QueuePersistenceFactory getFactory()
 */
class QueueQueryContainer extends AbstractQueryContainer implements QueueQueryContainerInterface
{

    /**
     * @param string $serverId
     * @param string $queueName
     *
     * @return SpyQueueProcessQuery
     */
    public function queryProcessesByServerIdAndQueueName($serverId, $queueName)
    {
        return $this->getFactory()
            ->createSpyQueueProcessQuery()
            ->filterByServerId($serverId)
            ->filterByQueueName($queueName);
    }

    /**
     * @param array $processIds
     *
     * @return SpyQueueProcessQuery
     */
    public function queryProcessesByProcessIds(array $processIds)
    {
        return $this->getFactory()
            ->createSpyQueueProcessQuery()
            ->filterByProcessPid($processIds, Criteria::IN);
    }

    /**
     * @param string $serverId
     *
     * @return SpyQueueProcessQuery
     */
    public function queryProcessesByServerId($serverId)
    {
        return $this->getFactory()
            ->createSpyQueueProcessQuery()
            ->filterByServerId($serverId);
    }

    /**
     * @return SpyQueueProcessQuery
     */
    public function queryProcesses()
    {
        return $this->getFactory()->createSpyQueueProcessQuery();
    }
}
