<?php

namespace SprykerFeature\Zed\System\Communication\Controller\Widget;

use SprykerFeature\Zed\Library\Controller\Action\AbstractGridController;
use Symfony\Component\HttpFoundation\Request;

class FacadeApiGridController extends AbstractGridController
{

    /**
     * @param Request $request
     * @return array
     */
    public function indexAction(Request $request)
    {
        return $this->viewResponse([
            'grid' => $this->initializeGrid($request)
        ]);
    }

    /**
     * @param Request $request
     * @return mixed|\SprykerFeature_Zed_System_Communication_Grid_FacadeApi
     */
    protected function initializeGrid(Request $request)
    {
        $dataSource = new \SprykerFeature_Zed_System_Communication_Grid_FacadeApi_DataSource();
        return new \SprykerFeature_Zed_System_Communication_Grid_FacadeApi($dataSource);
    }


}
