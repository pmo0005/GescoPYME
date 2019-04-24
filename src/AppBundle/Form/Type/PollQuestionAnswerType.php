<?php
// AppBundle\Form\Type\PollQuestionAnswerType.php

namespace AppBundle\Form\Type;

use AppBundle\Entity\Poll;
use AppBundle\Entity\PollQuestionAnswer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PollQuestionAnswerType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('text', TextType::class);
        $builder->add('value');
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => PollQuestionAnswer::class,
        ));
    }
}