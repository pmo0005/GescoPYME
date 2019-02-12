<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Company;
use FOS\RestBundle\Controller\Annotations;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Routing\ClassResourceInterface;
use FOS\RestBundle\Controller\Annotations\RouteResource;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

/**
 * @RouteResource("company", pluralize=false)
 */
class RestCompanyController extends FOSRestController implements ClassResourceInterface
{
    /**
     * @Annotations\Get("/company/{company}")
     *
     * @ParamConverter("company", class="AppBundle:Company")
     *
     * @param Company $company
     *
     * @return Company
     */
    public function getAction(Company $company)
    {
        return $company;
    }


}