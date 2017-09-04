<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Discount;

use Spryker\Shared\Kernel\Store;
use Spryker\Zed\Discount\Communication\Plugin\Calculator\FixedPlugin;
use Spryker\Zed\Discount\Communication\Plugin\Calculator\PercentagePlugin;
use Spryker\Zed\Discount\Communication\Plugin\Collector\ItemByPriceCollectorPlugin;
use Spryker\Zed\Discount\Communication\Plugin\Collector\ItemByQuantityCollectorPlugin;
use Spryker\Zed\Discount\Communication\Plugin\Collector\ItemBySkuCollectorPlugin;
use Spryker\Zed\Discount\Communication\Plugin\DecisionRule\CalendarWeekDecisionRulePlugin;
use Spryker\Zed\Discount\Communication\Plugin\DecisionRule\DayOfTheWeekDecisionRulePlugin;
use Spryker\Zed\Discount\Communication\Plugin\DecisionRule\GrandTotalDecisionRulePlugin;
use Spryker\Zed\Discount\Communication\Plugin\DecisionRule\ItemPriceDecisionRulePlugin;
use Spryker\Zed\Discount\Communication\Plugin\DecisionRule\ItemQuantityDecisionRulePlugin;
use Spryker\Zed\Discount\Communication\Plugin\DecisionRule\MonthDecisionRulePlugin;
use Spryker\Zed\Discount\Communication\Plugin\DecisionRule\SkuDecisionRulePlugin;
use Spryker\Zed\Discount\Communication\Plugin\DecisionRule\SubTotalDecisionRulePlugin;
use Spryker\Zed\Discount\Communication\Plugin\DecisionRule\TimeDecisionRulePlugin;
use Spryker\Zed\Discount\Communication\Plugin\DecisionRule\TotalQuantityDecisionRulePlugin;
use Spryker\Zed\Discount\Dependency\Facade\DiscountToMessengerBridge;
use Spryker\Zed\Discount\Dependency\Facade\DiscountToMoneyBridge;
use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;

class DiscountDependencyProvider extends AbstractBundleDependencyProvider
{

    const STORE_CONFIG = 'STORE_CONFIG';

    const FACADE_MESSENGER = 'MESSENGER_FACADE';
    const FACADE_MONEY = 'MONEY_FACADE';

    const PLUGIN_PROPEL_CONNECTION = 'PROPEL_CONNECTION_PLUGIN';
    const PLUGIN_CALCULATOR_PERCENTAGE = 'PLUGIN_CALCULATOR_PERCENTAGE';
    const PLUGIN_CALCULATOR_FIXED = 'PLUGIN_CALCULATOR_FIXED';
    const PLUGIN_DISCOUNTABLE_ITEM_FILTER = 'PLUGIN_DISCOUNTABLE_ITEM_FILTER';
    const DECISION_RULE_PLUGINS = 'DECISION_RULE_PLUGINS';
    const CALCULATOR_PLUGINS = 'CALCULATOR_PLUGINS';
    const COLLECTOR_PLUGINS = 'COLLECTOR_PLUGINS';

    const CURRENCY_MANAGER = 'CURRENCY_MANAGER';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideBusinessLayerDependencies(Container $container)
    {
        $container = $this->addStore($container);
        $container = $this->addMessengerFacade($container);
        $container = $this->addCalculatorPlugins($container);
        $container = $this->addDecisionRulePlugins($container);
        $container = $this->addCollectorPlugins($container);
        $container = $this->addDiscountableItemFilterPlugins($container);
        $container = $this->addMoneyFacade($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideCommunicationLayerDependencies(Container $container)
    {
        $container = $this->addStore($container);
        $container = $this->addDecisionRulePlugins($container);
        $container = $this->addCalculatorPlugins($container);
        $container = $this->addCollectorPlugins($container);
        $container = $this->addMoneyFacade($container);

        return $container;
    }

    /**
     * @return \Spryker\Zed\Discount\Dependency\Plugin\DiscountCalculatorPluginInterface[]
     */
    public function getAvailableCalculatorPlugins()
    {
        return [
            self::PLUGIN_CALCULATOR_PERCENTAGE => new PercentagePlugin(),
            self::PLUGIN_CALCULATOR_FIXED => new FixedPlugin(),
        ];
    }

    /**
     * @return \Spryker\Zed\Discount\Dependency\Plugin\CollectorPluginInterface[]
     */
    protected function getCollectorPlugins()
    {
        return [
            new ItemBySkuCollectorPlugin(),
            new ItemByQuantityCollectorPlugin(),
            new ItemByPriceCollectorPlugin(),
        ];
    }

    /**
     * @return \Spryker\Zed\Discount\Dependency\Plugin\DecisionRulePluginInterface[]
     */
    protected function getDecisionRulePlugins()
    {
        return [
            new SkuDecisionRulePlugin(),
            new GrandTotalDecisionRulePlugin(),
            new SubTotalDecisionRulePlugin(),
            new TotalQuantityDecisionRulePlugin(),
            new ItemQuantityDecisionRulePlugin(),
            new ItemPriceDecisionRulePlugin(),
            new CalendarWeekDecisionRulePlugin(),
            new DayOfTheWeekDecisionRulePlugin(),
            new MonthDecisionRulePlugin(),
            new TimeDecisionRulePlugin(),
        ];
    }

    /**
     *
     * This is additional filter applied to discountable items, the plugins are triggered after discount collectors run
     * this ensures that certain items are never picked by discount calculation and removed from DiscountableItem stack.
     *
     * @return \Spryker\Zed\Discount\Dependency\Plugin\DiscountableItemFilterPluginInterface[]
     */
    protected function getDiscountableItemFilterPlugins()
    {
        return [];
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addMoneyFacade(Container $container)
    {
        $container[static::FACADE_MONEY] = function (Container $container) {
            $discountToMoneyBridge = new DiscountToMoneyBridge($container->getLocator()->money()->facade());

            return $discountToMoneyBridge;
        };

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addStore(Container $container)
    {
        $container[self::STORE_CONFIG] = function () {
            return Store::getInstance();
        };

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addMessengerFacade(Container $container)
    {
        $container[self::FACADE_MESSENGER] = function (Container $container) {
            return new DiscountToMessengerBridge($container->getLocator()->messenger()->facade());
        };

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addCalculatorPlugins(Container $container)
    {
        $container[self::CALCULATOR_PLUGINS] = function () {
            return $this->getAvailableCalculatorPlugins();
        };

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addDecisionRulePlugins(Container $container)
    {
        $container[self::DECISION_RULE_PLUGINS] = function () {
            return $this->getDecisionRulePlugins();
        };

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addCollectorPlugins(Container $container)
    {
        $container[self::COLLECTOR_PLUGINS] = function () {
            return $this->getCollectorPlugins();
        };

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addDiscountableItemFilterPlugins($container)
    {
        $container[static::PLUGIN_DISCOUNTABLE_ITEM_FILTER] = function () {
            return $this->getDiscountableItemFilterPlugins();
        };

        return $container;
    }

}