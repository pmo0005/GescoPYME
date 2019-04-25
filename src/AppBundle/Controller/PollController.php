<?php
// src/AppBundle/Controller/PollController.php

namespace AppBundle\Controller;

use AppBundle\Entity\Company;
use AppBundle\Entity\PollQuestion;
use AppBundle\Entity\PollQuestionAnswer;
use AppBundle\Entity\PollQuestionType;
use AppBundle\Entity\PollRegistry;
use AppBundle\Entity\Product;
use AppBundle\Entity\User;
use Doctrine\Common\Collections\ArrayCollection;
use FOS\UserBundle\Model\UserManager;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class PollController extends Controller
{

    const TYPE_GENERAL = "TYPE_GENERAL";
    const TYPE_COMPANY = "TYPE_COMPANY";
    const TYPE_PRODUCT = "TYPE_PRODUCT";
    const MAX_ALLOWED_ACTIVE_QUESTIONS = 3;

    public function indexAction($productId = null)
    {
        $em = $this->getDoctrine();
        $productRepo = $em->getRepository("AppBundle:Product");

        /** @var Product $product */
        $product = $productRepo->find($productId);
        if (!isset($product)) {
            throw new NotFoundHttpException();
        }

        $questions = $this->getPollQuestions($product);
        /** @var PollQuestion $question */
        $question = $questions[2];

        $reg = new PollRegistry();
        $reg
            ->setUser("test@guest.com")
            ->setProduct($product);

        $builder = $this->createFormBuilder($reg)
            ->add('user', null, array("label" => "Email"))
            ->add('observations', null, array("label" => "Observaciones"))
            ->add('pregunta', ChoiceType::class, array(
                'mapped' => false,
                'required' => true,
                'expanded' => false,
                'label' => $question->getText(),
                'choices' => $question->getAnswers(),
                'choice_label' => function(PollQuestionAnswer $answer, $key, $value) {
                    return strtoupper($answer->getText());
                },
                'choice_value' => function (PollQuestionAnswer $answer = null) {
                    return $answer ? $answer->getId() : '';
                },
            ));

        /** @var PollQuestion $question */
        foreach ($questions as $question) {
            $builder->add("question_".$question->getId(), ChoiceType::class, array(
                'mapped' => false,
                'required' => true,
                'expanded' => false,
                'label' => $question->getText(),
                'choices' => $question->getAnswers(),
                'choice_label' => function(PollQuestionAnswer $answer, $key, $value) {
                    return strtoupper($answer->getText());
                },
                'choice_value' => function (PollQuestionAnswer $answer = null) {
                    return $answer ? $answer->getId() : '';
                },
            ));
        }

        $form = $builder->getForm();

        return $this->render("AppBundle::poll.html.twig", [
            'form' => $form->createView(),
            'product' => $product,
        ]);

        /*
        return new JsonResponse(array(
            "product_id" => $product->getName(),
            "company" => $product->getCompany()->getName(),
            "questions" => array_map(function ($q) { return $q->getId(); }, $questions)
        ));
        */
    }

    /**
     * @param Product $product
     * @return ArrayCollection
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    private function getPollQuestions ($product) {
        $limit = self::MAX_ALLOWED_ACTIVE_QUESTIONS;
        $company = $product->getCompany();

        $generalQuestions = $this->getActiveQuestionsByType(self::TYPE_GENERAL, $limit);
        $companyQuestions = $this->getActiveQuestionsByType(self::TYPE_COMPANY, $limit, $company);
        $productQuestions = $this->getActiveQuestionsByType(self::TYPE_PRODUCT, $limit, $company, $product);

        return array_merge($generalQuestions, $companyQuestions, $productQuestions);
    }

    /**
     * @param string $type
     * @param int $limit
     * @param Company $company
     * @param Product $product
     * @return array
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    private function getActiveQuestionsByType(
        $type, $limit = self::MAX_ALLOWED_ACTIVE_QUESTIONS, $company = null, $product = null
    ) {
        return $this
            ->getDoctrine()
            ->getRepository("AppBundle:PollQuestion")
            ->findBy(array(
                "enabled" => true,
                "type" => $this->getOrCreateQuestionType($type),
                "company" => $company,
                "product" => $product
            ), null, $limit);
    }

    /**
     * @param $type
     * @return PollQuestionType|null|object
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    private function getOrCreateQuestionType($type) {
        $em = $this->getDoctrine();
        $type = $em
            ->getRepository("AppBundle:PollQuestionType")
            ->findOneBy(array("name" => $type));
        if (!isset($type)) {
            $type = new PollQuestionType();
            $type->setName($type);
            $em->persist($type);
            $em->flush();
        }

        return $type;
    }
}