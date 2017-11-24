<?php

namespace FormBundle\FormType;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\CountryType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;


class BilletType extends AbstractType
{

  public function buildForm(FormBuilderInterface $builder, array $options)
  {
    $builder
      ->add('nom',                TextType::class)
      ->add('prenom',             TextType::class)
      ->add('pays',               CountryType::class)
      ->add('dateNaissance',      DateType::class, array(
                                      'widget'    => 'single_text',
                                      'html5'     => false,
                                      'attr'      => [
                                        'class'   => 'date-naissance col-3',
                                        'readonly'=> true
                                      ],
                                      'format'    => 'd/M/y',
                                      'label'     => false))
      ->add('type',               ChoiceType::class, array(
                                      'choices'   => array(
                                          'Demi-journée (à partir de 14h)' => '0',
                                          'Journée'                        => '1'), 
                                      'multiple'  => false, 
                                      'expanded'  => true))
      ->add('tarifReduit',        CheckboxType::class, array(
                                      'required'  => false, 
                                      'label'     => 'Tarif réduit (étudiant, employé du musée, employé du ministère de la culture, militaire, chômeur)', 
                                      'attr'      => ['onclick' => 'tarifReduitToggle(this)']));
  }


  public function configureOptions(OptionsResolver $resolver)
  {
    $resolver->setDefaults(array(
      'data_class' => 'FormBundle\Entity\Billet'
    ));
  }

}