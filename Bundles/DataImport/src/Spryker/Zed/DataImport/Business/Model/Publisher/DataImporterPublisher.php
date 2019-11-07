<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DataImport\Business\Model\Publisher;

use Generated\Shared\Transfer\EventEntityTransfer;
use Spryker\Shared\Config\Config;
use Spryker\Shared\DataImport\DataImportConstants;
use Spryker\Zed\Kernel\Locator;

class DataImporterPublisher implements DataImporterPublisherInterface
{
    public const CHUNK_SIZE = 20000;

    /**
     * @var \Spryker\Zed\Event\Business\EventFacadeInterface|null
     */
    protected static $eventFacade;

    /**
     * @var array
     */
    protected static $importedEntityEvents = [];

    /**
     * @var array
     */
    protected static $triggeredEventIds = [];

    /**
     * @param string $eventName
     * @param int $entityId
     *
     * @return void
     */
    public static function addEvent($eventName, $entityId): void
    {
        if (isset(static::$triggeredEventIds[$eventName][$entityId])) {
            return;
        }

        static::$importedEntityEvents[$eventName][$entityId] = true;

        $chunkSize = Config::get(DataImportConstants::PUBLISHER_TRIGGER_CHUNK_SIZE, static::CHUNK_SIZE);

        if (count(static::$importedEntityEvents, COUNT_RECURSIVE) >= $chunkSize) {
            static::triggerEvents();
        }
    }

    /**
     * @deprecated use addEvent() instead.
     *
     * @param array $events
     *
     * @return void
     */
    public static function addImportedEntityEvents(array $events): void
    {
        static::$importedEntityEvents = array_merge_recursive(static::$importedEntityEvents, $events);
    }

    /**
     * @param int|null $flushChunkSize
     *
     * @return void
     */
    public static function triggerEvents(?int $flushChunkSize = null): void
    {
        $uniqueEvents = static::$importedEntityEvents;
        foreach ($uniqueEvents as $eventName => $ids) {
            $events = [];
            foreach ($ids as $key => $value) {
                $events[] = (new EventEntityTransfer())->setId($key);
                static::$triggeredEventIds[$eventName][$key] = true;
            }

            static::loadEventFacade();
            static::$eventFacade->triggerBulk($eventName, $events);
        }

        static::$importedEntityEvents = [];

        if ($flushChunkSize === null) {
            $flushChunkSize = Config::get(DataImportConstants::PUBLISHER_FLUSH_CHUNK_SIZE, static::FLUSH_CHUNK_SIZE);
        }

        if (count(static::$triggeredEventIds, COUNT_RECURSIVE) >= $flushChunkSize) {
            static::$triggeredEventIds = [];
        }
    }

    /**
     * @deprecated $ids will be unique by calling DataImporterPublisher::addEvent(),
     * No necessary to call this method anymore
     *
     * @param array $mergedArray
     *
     * @return array
     */
    protected static function getUniqueArray(array $mergedArray): array
    {
        $uniqueArray = [];
        foreach ($mergedArray as $event => $ids) {
            $uniqueArray[$event] = array_unique($ids, SORT_REGULAR);
        }

        return $uniqueArray;
    }

    /**
     * Added here for keeping the BC, needs to inject this from outside
     *
     * @return void
     */
    protected static function loadEventFacade()
    {
        if (!static::$eventFacade) {
            $locatorClassName = Locator::class;
            static::$eventFacade = $locatorClassName::getInstance()->event()->facade();
        }
    }
}
