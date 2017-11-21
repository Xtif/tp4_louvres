<?php

namespace FormBundle\FormType;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Symfony\Component\Form\Extension\Core\Type\DateType;

class JourType extends AbstractType
{
  public function buildForm(FormBuilderInterface $builder, array $options)
  {
    $builder->add('jour', DateType::class, array(
      'widget' => 'single_text',
      'html5'  => false,
      'attr'   => ['class' => 'datepicker'],
      'format' => 'd/M/y',
      'label'  => false
    ));
  }

  public function configureOptions(OptionsResolver $resolver)
  {
    $resolver->setDefaults(array(
      'data_class' => 'FormBundle\Entity\Jour'
    ));
  }
}
