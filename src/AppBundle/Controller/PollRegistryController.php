<?php
// src/AppBundle/Controller/PollRegistryController.php
namespace AppBundle\Controller;

use AppBundle\Entity\Company;
use AppBundle\Entity\PollRegistry;
use AppBundle\Entity\PollUserAnswer;
use AppBundle\Entity\Product;
use AppBundle\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AdminController as BaseAdminController;
use Symfony\Component\Finder\Exception\AccessDeniedException;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class PollRegistryController extends BaseAdminController
{
    protected function filterByProductAction()
    {
        $regId = $this->request->query->get("id", null);
        if (isset($regId)) {
            /** @var PollRegistry $reg */
            $reg = $this->em
                ->getRepository("AppBundle:PollRegistry")
                ->find($regId);
            $product = $reg->getProduct();
            if (isset($product)) {
                return $this->redirectToRoute('easyadmin', array(
                    'action' => 'list',
                    'productId' => $product->getId(),
                    'entity' => $this->request->query->get('entity'),
                ));
            }
        }
    }

    protected function listAction()
    {
        /** @var User $user */
        $user = $this->get('security.token_storage')->getToken()->getUser();
        $userCompany = $user->getCompany();
        $productId = $this->request->query->get("productId", null);

        $dqlFilter = "entity.product IN " . $this->dqlCompanyProductsList($userCompany);
        if (isset($productId)) {
            $dqlFilter = $dqlFilter . " AND entity.product = " . $productId;
        }
        $this->entity['list']['dql_filter'] = $dqlFilter;

        return parent::listAction();
    }

    protected function searchAction()
    {
        /** @var User $user */
        $user = $this->get('security.token_storage')->getToken()->getUser();
        $userCompany = $user->getCompany();
        $productId = $this->request->query->get("productId", null);

        $dqlFilter = "entity.product IN " . $this->dqlCompanyProductsList($userCompany);
        if (isset($productId)) {
            $dqlFilter = $dqlFilter . " AND entity.product = " . $productId;
        }
        $this->entity['search']['dql_filter'] = $dqlFilter;

        return parent::searchAction();
    }

    protected function showAction()
    {
        /** @var User $currentUser */
        $currentUser = $this->get('security.token_storage')->getToken()->getUser();
        $id = $this->request->query->get('id');

        if (!$this->authorize($currentUser, $id)) {
            throw new AccessDeniedException('You cant be here');
        }

        return parent::showAction();
    }

    protected function editAction()
    {
        throw new BadRequestHttpException('Poll registry can not be edited');
    }

    protected function deleteAction()
    {
        throw new BadRequestHttpException('Poll registry can not be deleted');
    }

    protected function newAction()
    {
        throw new BadRequestHttpException('Poll registry can not be added from admin panel');
    }

    /**
     * @param User $user
     * @param string|integer $regId
     * @return bool
     */
    private function authorize($user = null, $regId = null) {
        $userCompanyId = $user->getCompany()->getId();

        /** @var PollRegistry $regId */
        $reg = $this->em
            ->getRepository("AppBundle:PollRegistry")
            ->find($regId);

        if (isset($reg)) {
            /** @var Company $regCompany */
            $regCompany = $reg->getProduct()->getCompany();
            if (isset($regCompany)) {
                if ($regCompany->getId() === $userCompanyId) {
                    return true;
                }
            }
        }

        return false;
    }

    private function dqlCompanyProductsList (Company $company) {
        $products = $company->getProducts();
        $ids = $products->map(function(Product $product) { return $product->getId(); })->toArray();
        return " (" . implode(",", $ids) . ")";
    }
}