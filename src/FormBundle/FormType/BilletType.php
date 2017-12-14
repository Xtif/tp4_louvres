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
use Symfony\Component\Validator\Constraints\NotBlank;

// Creation du champ de formulaire de type Billet
class BilletType extends AbstractType
{

  public function buildForm(FormBuilderInterface $builder, array $options)
  {
    $builder
      ->add('nom',                TextType::class, array('constraints' => new NotBlank(array('message' => 'Le nom est obligatoire'))))
      ->add('prenom',             TextType::class, array('constraints' => new NotBlank(array('message' => 'Le prénom est obligatoire'))))
      ->add('pays',               CountryType::class, array('constraints' => new NotBlank(array('message' => 'Le pays est obligatoire'))))
      ->add('dateNaissance',      DateType::class, array(
                                      'widget'    => 'single_text',
                                      'html5'     => false,
                                      'attr'      => [
                                        'class'   => 'date-naissance col-3',
                                        'readonly'=> true,
                                      ],
                                      'format'    => 'd/M/y',
                                      'label'     => false,
                                      'constraints' => new NotBlank(array('message' => 'Le date de naissance est obligatoire'))
                                  ))
      ->add('type',               ChoiceType::class, array(
                                      'choices'   => array(
                                          'Demi-journée (à partir de 14h)' => '0',
                                          'Journée'                        => '1'
                                      ), 
                                      'multiple'  => false, 
                                      'expanded'  => true,
                                      'constraints' => new NotBlank(array('message' => 'Le type est obligatoire'))
                                  ))
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