<?php
// src/AppBundle/Controller/ProductController.php
namespace AppBundle\Controller;

use AppBundle\Entity\Company;
use AppBundle\Entity\Knowledge;
use AppBundle\Entity\Product;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AdminController as BaseAdminController;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProductController extends BaseAdminController
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
        /** @var Product $product */
        $product = $event->getData();
        $form = $event->getForm();

        // When you create a new person, the City is always empty
        $company = $product->getCompany()
            ? $product->getCompany() : null;


        $this->addElements($form, $company, $product);
    }

    function onPreSubmit(FormEvent $event) {
        $form = $event->getForm();
        $data = $event->getData();

        // Search for selected Company and convert it into an Entity
        $company = $this->em->getRepository('AppBundle:Company')
            ->find($data['company']);

        $this->addElements($form, $company);
    }

    protected function addElements(FormInterface $form, Company $company = null, Product $product = null) {
        $form->add('company', EntityType::class, array(
            'required' => true,
            'data' => $company,
            'placeholder' => 'Selecciona una empresa...',
            'class' => 'AppBundle:Company'
        ));

        $knowledges = array();
        if ($company) {
            $knowledgesRepo = $this->em->getRepository('AppBundle:Knowledge');
            $knowledges = $knowledgesRepo->createQueryBuilder("k")
                ->where("k.company = :companyId")
                ->setParameter("companyId", $company->getId())
                ->getQuery()
                ->getResult();
        }

        if (isset($product) && $product->getId() !== null) {
            $form->add('knowledges', ChoiceType::class, array(
                'required' => false,
                'placeholder' => 'Selecciona primero una empresa ...',
                'choices' => $knowledges,
                'multiple' => true,
                'choice_label' => function($knowledge, $key, $value) {
                    /** @var Knowledge $knowledge */
                    return $knowledge->__toString();
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
            'data_class' => 'AppBundle\Entity\Product'
        ));
    }

    /**
     * Returns a JSON string with the $knowledges of the Company with the provided id.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function listKnowledgesOfCompanyAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $knowledgesRepo = $em->getRepository("AppBundle:Knowledge");

        $knowledges = $knowledgesRepo->createQueryBuilder("k")
            ->where("k.company = :companyId")
            ->setParameter("companyId", $request->query->get("company_id"))
            ->getQuery()
            ->getResult();

        $responseArray = array();
        /** @var Knowledge $knowledge */
        foreach($knowledges as $knowledge){
            $responseArray[] = array(
                "id" => $knowledge->getId(),
                "name" => $knowledge->getName()
            );
        }

        return new JsonResponse($responseArray);
    }

    /**
     * Returns a JSON string with the products of the Company with the providen id.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function listProductsOfCompanyAction(Request $request)
    {
        // Get Entity manager and repository
        $em = $this->getDoctrine()->getManager();
        $productsRepo = $em->getRepository("AppBundle:Product");

        // Search the products that belongs to the company with the given id as GET parameter "company_id"
        $products = $productsRepo->createQueryBuilder("p")
            ->where("p.company = :companyId")
            ->setParameter("companyId", $request->query->get("company_id"))
            ->getQuery()
            ->getResult();

        // Serialize into an array the data that we need, in this case only name and id
        // Note: you can use a serializer as well, for explanation purposes, we'll do it manually
        $responseArray = array();
        /** @var Product $product */
        foreach($products as $product){
            $responseArray[] = array(
                "id" => $product->getId(),
                "name" => $product->getName()
            );
        }

        // Return array with structure of the products of the providen company id
        return new JsonResponse($responseArray);
    }
}