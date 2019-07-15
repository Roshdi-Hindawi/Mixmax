<?php

namespace MainBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use MainBundle\Form\MediaType;

class WorkerType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name',TextType::class, ['required' => true])
            ->add('phone', TelType::class, ['required' => true])
            ->add('birthdate', HiddenType::class, ['required' => true])
            ->add('startDate', DateType::class, ['required' => true])
            ->add('post',TextType::class, ['required' => true])
            ->add('gender', ChoiceType::class, ['choices' => [
                                                    'Homme'=> 'Homme',
                                                    'Femme'=> 'Femme']])
            ->add('salary', NumberType::class, ['required' => true])
            ->add('facility')
            ->add('status', ChoiceType::class, ['choices' => [
                                                    'En Poste'=> 'En Poste',
                                                    'Démissionner'=> 'Démissionner',
                                                    'Suspendu'=> 'Suspendu',
                                                    'Viré'=> 'viré']] )
            ->add('image', MediaType::class, ['required' => false]);
    }/**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'MainBundle\Entity\Worker'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'mainbundle_worker';
    }

}
