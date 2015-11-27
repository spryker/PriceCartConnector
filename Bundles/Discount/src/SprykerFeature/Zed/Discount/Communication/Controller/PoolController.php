<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Discount\Communication\Controller;

use Generated\Shared\Transfer\VoucherCodesTransfer;
use Orm\Zed\Discount\Persistence\Map\SpyDiscountVoucherPoolCategoryTableMap;
use Orm\Zed\Discount\Persistence\SpyDiscount;
use Propel\Runtime\Map\TableMap;
use SprykerFeature\Zed\Discount\DiscountConfig;
use SprykerFeature\Zed\Application\Communication\Controller\AbstractController;
use SprykerFeature\Zed\Discount\Communication\Table\VoucherPoolTable;
use SprykerFeature\Zed\Discount\Persistence\DiscountQueryContainer;
use SprykerFeature\Zed\Discount\Business\DiscountFacade;
use SprykerFeature\Zed\Discount\Communication\DiscountDependencyContainer;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * @method DiscountDependencyContainer getDependencyContainer()
 * @method DiscountQueryContainer getQueryContainer()
 * @method DiscountFacade getFacade()
 */
class PoolController extends AbstractController
{

    const TERM = 'term';
    const BLANK = '';

    public function createAction(Request $request)
    {
        $form = $this->getDependencyContainer()->createVoucherCodesForm();
        $form->handleRequest($request);

        if ($form->isValid()) {
            $formData = $form->getData();

            $voucherCodesTransfer = (new VoucherCodesTransfer())->fromArray($formData, true);

            $voucherPoolTransfer = $this->getFacade()->saveVoucherCode($voucherCodesTransfer);

            return $this->redirectResponse(sprintf(
                DiscountConfig::URL_DISCOUNT_POOL_EDIT,
                DiscountConfig::PARAM_ID_POOL,
                $voucherPoolTransfer->getIdDiscountVoucherPool()
            ));
        }

        return [
            'form' => $form->createView(),
        ];
    }

    /**
     * @param Request $request
     *
     * @return array
     */
    public function editAction(Request $request)
    {
        $form = $this->getDependencyContainer()->createVoucherCodesForm();
        $form->handleRequest($request);

        if ($form->isValid()) {
            $formData = $form->getData();

            $voucherCodesTransfer = (new VoucherCodesTransfer())->fromArray($formData, true);
            $voucherPoolTransfer = $this->getFacade()->saveVoucherCode($voucherCodesTransfer);

            return $this->redirectResponse(sprintf(
                DiscountConfig::URL_DISCOUNT_POOL_EDIT,
                DiscountConfig::PARAM_ID_POOL,
                $voucherPoolTransfer->getIdDiscountVoucherPool()
            ));
        }

        return [
            'form' => $form->createView(),
        ];
    }

    /**
     * @param int $idPoolCategory
     *
     * @return array
     */
//    public function createCategoryAction($idPoolCategory = 0)
//    {
//        $form = $this->getDependencyContainer()->createPoolCategoryForm($idPoolCategory);
//        $form->handleRequest();
//
//        if ($form->isValid()) {
//            $facade = $this->getFacade();
//
//            $category = new VoucherPoolCategoryTransfer();
//            $category->fromArray($form->getData());
//
//            $facade->createDiscountVoucherPoolCategory($category);
//        }
//
//        return [
//            'form' => $form->createView(),
//        ];
//    }

    /**
     * @param Request $request
     *
     * @return array
     */
    public function editCategoryAction(Request $request)
    {
        $idPoolCategory = $request->query->get('id', 0);

        return $this->createCategoryAction($idPoolCategory);
    }

    /**
     * @param Request $request
     *
     * @return array
     */
    public function categoriesAction()
    {
        $table = $this->getDependencyContainer()->createPoolCategoriesTable();

        return [
            'categories' => $table->render(),
        ];
    }

    /**
     * @return JsonResponse
     */
    public function categoriesTableAction()
    {
        $table = $this->getDependencyContainer()->createPoolCategoriesTable();

        return $this->jsonResponse(
            $table->fetchData()
        );
    }

    /**
     * @return array
     */
    public function indexAction()
    {
        $table = $this->getDependencyContainer()->createVoucherPoolTable();

        return [
            'categories' => $table->render(),
        ];
    }

    /**
     * @return JsonResponse
     */
    public function poolTableAction()
    {
        $table = $this->getDependencyContainer()->createVoucherPoolTable();

        return $this->jsonResponse(
            $table->fetchData()
        );
    }

    /**
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function categorySuggestAction(Request $request)
    {
        $term = $request->get(self::TERM);

        $categories = $this->getQueryContainer()
            ->queryDiscountVoucherPoolCategory()
            ->findByName('%' . $term . '%');

        $result = [];
        if (count($categories) > 0) {
            $names = $categories->toArray(null, false, TableMap::TYPE_COLNAME);

            foreach ($names as $value) {
                $result[] = $value[SpyDiscountVoucherPoolCategoryTableMap::COL_NAME];
            }
        }

        return $this->jsonResponse($result);
    }

    /**
     * @param int $idPool
     *
     * @return VoucherCodesTransfer
     *
     * @todo remove me
     *
     * @deprecated
     */
//    protected function getVoucherCodesTransfer($idPool)
//    {
//        $discountVoucherPoolEntity = $this->getQueryContainer()->queryVoucherCodeByIdVoucherCode($idPool)->findOne();
//
//        $discountEntity = $this->getDiscountByIdVoucherPool($idPool);
//
//        $decisionRuleEntities = $discountEntity->getDecisionRules();
//        $discountCollectorEntities = $discountEntity->getDiscountCollectors();
//        $discountVoucherPool = $discountVoucherPoolEntity->toArray();
//        $discountVoucherPool[CartRuleForm::FIELD_COLLECTOR_PLUGINS] = $discountCollectorEntities->toArray();
//
//        $voucherCodesTransfer = (new VoucherCodesTransfer())->fromArray($discountVoucherPool, true);
//        $voucherCodesTransfer->setDecisionRules($decisionRuleEntities->toArray());
//        $voucherCodesTransfer->setCalculatorPlugin($discountEntity->getCalculatorPlugin());
//
//        $voucherCodesTransfer->setIsPrivileged((bool) $discountEntity->getIsPrivileged());
//        $voucherCodesTransfer->setValidFrom($discountEntity->getValidFrom());
//        $voucherCodesTransfer->setValidTo($discountEntity->getValidTo());
//
//        return $voucherCodesTransfer;
//    }

    /**
     * @param int $idVoucherPool
     *
     * @return SpyDiscount
     */
//    protected function getDiscountByIdVoucherPool($idVoucherPool)
//    {
//        return $this->getQueryContainer()
//            ->queryDiscount()
//            ->filterByFkDiscountVoucherPool($idVoucherPool)
//            ->findOne();
//    }

}
