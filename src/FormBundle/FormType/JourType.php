<?php

namespace FormBundle\FormType;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Symfony\Component\Form\Extension\Core\Type\DateType;

use Symfony\Component\Validator\Constraints\NotBlank;

// Creation du champ de formulaire de type Jour
class JourType extends AbstractType
{
  public function buildForm(FormBuilderInterface $builder, array $options)
  {
    $builder->add('jour', DateType::class, array(
      'widget' => 'single_text',
      'html5'  => false,
      'attr'   => [
        'class' => 'datepicker', 
        'readonly' => true
      ],
      'format' => 'd/M/y',
      'label'  => false,
      'invalid_message' => 'Veuillez entrer une date valide',
      'constraints' => new NotBlank(array('message' => 'Le jour est obligatoire'))
    ));
  }

  public function configureOptions(OptionsResolver $resolver)
  {
    $resolver->setDefaults(array(
      'data_class' => 'FormBundle\Entity\Jour'
    ));
  }
}
