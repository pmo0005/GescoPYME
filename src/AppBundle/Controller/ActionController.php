<?php
// src/AppBundle/Controller/ActionController.php
namespace AppBundle\Controller;

use AppBundle\Entity\Action;
use AppBundle\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AdminController as BaseAdminController;
use Symfony\Component\Finder\Exception\AccessDeniedException;

class ActionController extends BaseAdminController
{
    protected function listAction()
    {
        /** @var User $user */
        $user = $this->get('security.token_storage')->getToken()->getUser();
        $userCompany = $user->getCompany();

        $this->entity['list']['dql_filter'] = $user->hasRole("ROLE_SUPER_ADMIN")
            ? $this->entity['list']['dql_filter']
            : "entity.company = " . $userCompany->getId();

        return parent::listAction(); // TODO: Change the autogenerated stub
    }

    protected function searchAction()
    {
        /** @var User $user */
        $user = $this->get('security.token_storage')->getToken()->getUser();
        $userCompany = $user->getCompany();

        $this->entity['search']['dql_filter'] = $user->hasRole("ROLE_SUPER_ADMIN")
            ? $this->entity['list']['dql_filter']
            : "entity.company = " . $userCompany->getId();

        return parent::searchAction(); // TODO: Change the autogenerated stub
    }

    protected function showAction()
    {
        $id = $this->request->query->get('id');

        /** @var User $currentUser */
        $currentUser = $this->get('security.token_storage')->getToken()->getUser();
        $currentUserCompany = $currentUser->getCompany();

        /** @var Action $requestedAction */
        $requestedAction = $this->em->getRepository("AppBundle:Action")->find($id);
        $requestedActionCompany = $requestedAction->getCompany();

        if (
            !$currentUser->hasRole("ROLE_SUPER_ADMIN") &&
            $currentUserCompany->getId() != $requestedActionCompany->getId()
        ) {
            throw new AccessDeniedException('You cant be here');
        }

        return parent::showAction(); // TODO: Change the autogenerated stub
    }

    protected function editAction()
    {
        $id = $this->request->query->get('id');

        /** @var User $currentUser */
        $currentUser = $this->get('security.token_storage')->getToken()->getUser();
        $currentUserCompany = $currentUser->getCompany();

        /** @var Action $requestedAction */
        $requestedAction = $this->em->getRepository("AppBundle:Action")->find($id);
        $requestedActionCompany = $requestedAction->getCompany();

        if (
            !$currentUser->hasRole("ROLE_SUPER_ADMIN") &&
            $currentUserCompany->getId() != $requestedActionCompany->getId()
        ) {
            throw new AccessDeniedException('You cant be here');
        }

        return parent::editAction(); // TODO: Change the autogenerated stub
    }

    protected function deleteAction()
    {
        $id = $this->request->query->get('id');

        /** @var User $currentUser */
        $currentUser = $this->get('security.token_storage')->getToken()->getUser();
        $currentUserCompany = $currentUser->getCompany();

        /** @var Action $requestedAction */
        $requestedAction = $this->em->getRepository("AppBundle:Action")->find($id);
        $requestedActionCompany = $requestedAction->getCompany();

        if (
            !$currentUser->hasRole("ROLE_SUPER_ADMIN") &&
            $currentUserCompany->getId() != $requestedActionCompany->getId()
        ) {
            throw new AccessDeniedException('You cant be here');
        }

        return parent::deleteAction(); // TODO: Change the autogenerated stub
    }

    protected function createEditForm($entity, array $entityProperties)
    {
        $currentUser = $this->get('security.token_storage')->getToken()->getUser();
        $form = parent::createEditForm($entity, $entityProperties);

        if (!$currentUser->hasRole("ROLE_SUPER_ADMIN")) {
            $form->remove("company");
        }

        return $form;
    }

    protected function createNewForm($entity, array $entityProperties)
    {
        $currentUser = $this->get('security.token_storage')->getToken()->getUser();
        $form = parent::createNewForm($entity, $entityProperties);

        if (!$currentUser->hasRole("ROLE_SUPER_ADMIN")) {
            $form->remove("company");
        }

        return $form;
    }

    /**
     * @param Action $entity
     */
    protected function prePersistEntity($entity)
    {
        /** @var User $currentUser */
        $currentUser = $this->get('security.token_storage')->getToken()->getUser();
        if (!$currentUser->hasRole("ROLE_SUPER_ADMIN")) {
            $entity->setCompany($currentUser->getCompany());
        }

        parent::prePersistEntity($entity); // TODO: Change the autogenerated stub
    }
}