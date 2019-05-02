<?php
// src/AppBundle/Controller/PollController.php

namespace AppBundle\Controller;

use AppBundle\Entity\Company;
use AppBundle\Entity\PollQuestion;
use AppBundle\Entity\PollQuestionAnswer;
use AppBundle\Entity\PollQuestionType;
use AppBundle\Entity\PollRegistry;
use AppBundle\Entity\PollUserAnswer;
use AppBundle\Entity\Product;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class PollController extends Controller
{

    const TYPE_GENERAL = "TYPE_GENERAL";
    const TYPE_COMPANY = "TYPE_COMPANY";
    const TYPE_PRODUCT = "TYPE_PRODUCT";
    const MAX_ALLOWED_ACTIVE_QUESTIONS = 3;

    public function indexAction(Request $request, $productId = null)
    {
        $em = $this->getDoctrine()->getManager();
        $productRepo = $em->getRepository("AppBundle:Product");

        /** @var Product $product */
        $product = $productRepo->find($productId);
        if (!isset($product)) {
            throw new NotFoundHttpException();
        }

        $form = $this->createPollRegistryForm($product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $reg = $this->handlePollResponses($form);
            return $this->render("AppBundle::thanks.html.twig", [
                'user' => $reg->getUser(),
                'product' => $reg->getProduct()
            ]);
        } else {
            return $this->render("AppBundle::poll.html.twig", [
                'form' => $form->createView(),
                'product' => $product,
            ]);
        }
    }

    /**
     * @param Product $product
     * @return \Symfony\Component\Form\FormInterface
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function createPollRegistryForm(Product $product) {
        $reg = new PollRegistry();
        $reg->setProduct($product);

        $questions = $this->getPollQuestions($product);

        $formBuilder = $this->createFormBuilder($reg)
            ->add('user', null, array(
                "required" => true,
                "label" => "Email"
            ));

        /** @var PollQuestion $question */
        foreach ($questions as $question) {
            $formBuilder->add("question_".$question->getId(), ChoiceType::class, array(
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

        $formBuilder
            ->add('observations', null, array(
                "required" => true,
                "label" => "Observaciones"
            ))
            ->add('save', SubmitType::class, array(
                'label' => 'Enviar',
                'attr' => array('class' => 'btn btn-primary action-save'),
            ));

        return $formBuilder->getForm();
    }

    /**
     * @param Form $form
     * @return PollRegistry
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    private function handlePollResponses(Form $form) {
        $em = $this->getDoctrine()->getManager();
        /** @var PollRegistry $reg */
        $reg = $form->getData();

        /** @var PollQuestion $question */
        foreach ($this->getPollQuestions($reg->getProduct()) as $question) {
            /** @var PollQuestionAnswer $answer */
            $answer = $form->get("question_".$question->getId())->getData();
            if (isset($answer)) {
                $userAnswer = new PollUserAnswer();
                $userAnswer
                    ->setRegistry($reg)
                    ->setAnswer($answer)
                    ->setQuestionTextCopy($answer->getQuestion()->getText())
                    ->setAnswerTextCopy($answer->getText())
                    ->setAnswerValueCopy($answer->getValue());
                $em->persist($userAnswer);

                $reg->addUserAnswer($userAnswer);
            }
        }

        $em->persist($reg);
        $em->flush();

        return $reg;
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
        $em = $this->getDoctrine()->getManager();
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