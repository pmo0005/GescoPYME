<?php
// src/AppBundle/Controller/KnowledgeController.php
namespace AppBundle\Controller;

use AppBundle\Entity\Action;
use AppBundle\Entity\Company;
use AppBundle\Entity\Knowledge;
use Doctrine\Common\Collections\ArrayCollection;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AdminController as BaseAdminController;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\OptionsResolver\OptionsResolver;

class KnowledgeController extends BaseAdminController
{
    // Creates the form builder used to create the form rendered in the
    // create and edit actions
    protected function createEntityFormBuilder($entity, $view) {
        $em = $this->getDoctrine()->getManager();

        $formBuilder = parent::createEntityFormBuilder($entity, $view);

        $formBuilder->addEventListener(FormEvents::PRE_SET_DATA, array($this, 'onPreSetData'));
        $formBuilder->addEventListener(FormEvents::PRE_SUBMIT, array($this, 'onPreSubmit'));

        return $formBuilder;
    }

    function onPreSetData(FormEvent $event) {
        /** @var Knowledge $knowledge */
        $knowledge = $event->getData();
        $form = $event->getForm();

        $company = $knowledge->getCompany()
            ? $knowledge->getCompany() : null;


        $this->addElements($form, $company, $knowledge);
    }

    function onPreSubmit(FormEvent $event) {
        $form = $event->getForm();
        $data = $event->getData();

        $company = $this->em->getRepository('AppBundle:Company')
            ->find($data['company']);

        $this->addElements($form, $company);
    }

    protected function addElements(FormInterface $form, Company $company = null, Knowledge $knowledge = null) {
        $form->add('company', EntityType::class, array(
            'required' => true,
            'data' => $company,
            'placeholder' => 'Selecciona una empresa...',
            'class' => 'AppBundle:Company'
        ));

        $actions = array();
        if ($company) {
            $actionsRepo = $this->em->getRepository('AppBundle:Action');
            $actions = $actionsRepo->createQueryBuilder("a")
                ->where("a.company = :companyId")
                ->setParameter("companyId", $company->getId())
                ->getQuery()
                ->getResult();
        }

        if (isset($knowledge) && $knowledge->getId() !== null) {
            $form->add('actions', ChoiceType::class, array(
                'required' => false,
                'placeholder' => 'Selecciona primero una empresa ...',
                'choices' => $actions,
                'multiple' => true,
                'choice_label' => function($action, $key, $value) {
                    /** @var Action $action */
                    return $action->getName();
                },
            ));
        }
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Knowledge'
        ));
    }

    /**
     * Returns a JSON string with the $actions of the Company with the providen id.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function listActionsOfCompanyAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $actionsRepo = $em->getRepository("AppBundle:Action");

        $actions = $actionsRepo->createQueryBuilder("a")
            ->where("a.company = :companyId")
            ->setParameter("companyId", $request->query->get("company_id"))
            ->getQuery()
            ->getResult();

        $responseArray = array();
        /** @var Action $action */
        foreach($actions as $action){
            $responseArray[] = array(
                "id" => $action->getId(),
                "name" => $action->getName()
            );
        }

        return new JsonResponse($responseArray);
    }
}