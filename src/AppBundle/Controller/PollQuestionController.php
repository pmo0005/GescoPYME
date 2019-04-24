<?php
// src/AppBundle/Controller/PollQuestionController.php
namespace AppBundle\Controller;

use AppBundle\Entity\PollQuestion;
use AppBundle\Entity\PollQuestionType;
use AppBundle\Entity\User;
use AppBundle\Form\Type\PollQuestionAnswerType;
use Doctrine\ORM\EntityRepository;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AdminController as BaseAdminController;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Finder\Exception\AccessDeniedException;
use Symfony\Component\HttpFoundation\JsonResponse;

class PollQuestionController extends BaseAdminController
{
    const TYPE_GENERAL = "TYPE_GENERAL";
    const TYPE_COMPANY = "TYPE_COMPANY";
    const TYPE_PRODUCT = "TYPE_PRODUCT";
    const MAX_ALLOWED_ACTIVE_QUESTIONS = 3;

    protected function filterByProductAction()
    {
        $questionId = $this->request->query->get("id", null);
        if (isset($questionId)) {
            /** @var PollQuestion $question */
            $question = $this->em
                ->getRepository("AppBundle:PollQuestion")
                ->find($questionId);
            $product = $question->getProduct();
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
        $entityType = $this->entity['type'];
        $productId = $this->request->query->get("productId", null);

        switch ($entityType) {
            case (self::TYPE_GENERAL):
                $dqlFilter = "entity.type = " . $this->getOrCreateQuestionType($entityType)->getId();
                break;
            case (self::TYPE_COMPANY):
            case (self::TYPE_PRODUCT):
                $dqlFilter = "entity.company = " . $userCompany->getId() .
                    " AND entity.type = " . $this->getOrCreateQuestionType($entityType)->getId();
                break;
            default:
                $dqlFilter = $this->entity['list']['dql_filter'];
        }
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
        $entityType = $this->entity['type'];
        $productId = $this->request->query->get("productId", null);

        switch ($entityType) {
            case (self::TYPE_GENERAL):
                $dqlFilter = "entity.type = " . $this->getOrCreateQuestionType($entityType)->getId();
                break;
            case (self::TYPE_COMPANY):
            case (self::TYPE_PRODUCT):
                $dqlFilter = "entity.company = " . $userCompany->getId() .
                    " AND entity.type = " . $this->getOrCreateQuestionType($entityType)->getId();
                break;
            default:
                $dqlFilter = $this->entity['list']['dql_filter'];
        }
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

        if (!$this->authorize($this->entity, $currentUser, $id)) {
            throw new AccessDeniedException('You cant be here');
        }

        return parent::showAction();
    }

    protected function editAction()
    {
        /** @var User $currentUser */
        $currentUser = $this->get('security.token_storage')->getToken()->getUser();
        $id = $this->request->query->get('id');

        if (!$this->authorize($this->entity, $currentUser, $id)) {
            throw new AccessDeniedException('You cant be here');
        }

        if (!$this->validateMaxActiveQuestions($id, $this->request->query)) {
            return new JsonResponse(array("error" => "Máximo 3 preguntas activas"), 403);
        }

        return parent::editAction();
    }

    protected function deleteAction()
    {
        /** @var User $currentUser */
        $currentUser = $this->get('security.token_storage')->getToken()->getUser();
        $id = $this->request->query->get('id');

        if (!$this->authorize($this->entity, $currentUser, $id)) {
            throw new AccessDeniedException('You cant be here');
        }

        return parent::deleteAction();
    }

    protected function createNewForm($entity, array $entityProperties)
    {
        /** @var User $currentUser */
        $currentUser = $this->get('security.token_storage')->getToken()->getUser();
        $entityType = $this->entity['type'];

        $form = parent::createNewForm($entity, $entityProperties);
        $this->setAnswersFormInput($form);
        if ($entityType === self::TYPE_PRODUCT) {
            $this->setProductFormInput($form, $currentUser->getCompany()->getId());
        }

        return $form;
    }

    protected function createEditForm($entity, array $entityProperties)
    {
        $form = parent::createEditForm($entity, $entityProperties);

        return $form;
    }

    /**
     * @param PollQuestion $entity
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    protected function prePersistEntity($entity)
    {
        /** @var User $currentUser */
        $currentUser = $this->get('security.token_storage')->getToken()->getUser();
        $entityType = $this->entity['type'];
        $entity->setType($this->getOrCreateQuestionType($entityType));

        switch ($entityType) {
            case (self::TYPE_COMPANY):
            case (self::TYPE_PRODUCT):
                $entity->setCompany($currentUser->getCompany());
                break;
        }

        parent::prePersistEntity($entity);
    }

    private function setAnswersFormInput($form) {
        $form->add('answers', CollectionType::class, array(
            'allow_add'    => true,
            'allow_delete' => true,
            'by_reference' => false,
            'entry_type' => PollQuestionAnswerType::class
        ));
    }

    private function setProductFormInput($form, $companyId = 0)
    {
        $form->add('product', EntityType::class, array(
            'class' => 'AppBundle:Product',
            'required' => true,
            'multiple' => false,
            'query_builder' => function (EntityRepository $repository) use ($companyId) {
                $qb = $repository->createQueryBuilder('k');
                return $qb
                    ->where("k.company = :companyId")
                    ->setParameter("companyId", $companyId);
            }
        ));
    }

    /**
     * @param $config
     * @param User $user
     * @param integer $questionId
     * @return bool
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    private function authorize($config, $user = null, $questionId = null) {
        $entityType = $config['type'];
        $userCompanyId = $user->getCompany()->getId();

        /** @var PollQuestion $question */
        $question = $this->em
            ->getRepository("AppBundle:PollQuestion")
            ->find($questionId);
        $questionType = isset($pollQuestion)
            ? $question->getType()
            : $this->getOrCreateQuestionType($entityType);

        if ($questionType->getName() === self::TYPE_GENERAL) {
            if ($user->hasRole("ROLE_SUPER_ADMIN")) {
                return true;
            }
        }

        if (isset($question)) {
            $questionCompany = $question->getCompany();
            if (isset($questionCompany)) {
                if ($questionCompany->getId() === $userCompanyId) {
                    return true;
                }
            }
        }

        return false;
    }

    /**
     * @param $questionId
     * @param $query
     * @return bool
     */
    private function validateMaxActiveQuestions ($questionId, $query) {
        /** @var PollQuestion $question */
        $question = $this->em->getRepository("AppBundle:PollQuestion")->find($questionId);
        $property = $query->get('property', '');
        $value = $query->get('newValue', false) === "true" ? true : false;

        if ($property === "enabled" && $value) {
            $type = $question->getType();
            $activeQuestionsCount = $this->em->getRepository("AppBundle:PollQuestion")->count(array(
                "type" => $type,
                "enabled" => true,
                "company" => $question->getCompany(),
                "product" => $question->getProduct()
            ));
            if ($activeQuestionsCount >= self::MAX_ALLOWED_ACTIVE_QUESTIONS) {
                return false;
            }
        }

        return true;
    }

    /**
     * @param $fields
     * @param User $user
     * @return mixed
     */
    private function fieldsWithRoles($fields, $user) {
        $parsedFields = array();
        foreach ($fields as $fieldId => $field) {
            if (!array_key_exists('roles', $field)) {
                $parsedFields[$fieldId] = $field;
                continue;
            }
            foreach ($field['roles'] as $role) {
                if ($user->hasRole($role)) {
                    $parsedFields[$fieldId] = $field;
                }
            }
        }

        return $parsedFields;
    }

    /**
     * @param $type
     * @return PollQuestionType|null|object
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    private function getOrCreateQuestionType($type) {
        $type = $this->em
            ->getRepository("AppBundle:PollQuestionType")
            ->findOneBy(array("name" => $type));
        if (!isset($type)) {
            $type = new PollQuestionType();
            $type->setName($type);
            $this->em->persist($type);
            $this->em->flush();
        }

        return $type;
    }
}