<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\ResetType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProjectsTypeEdit extends AbstractType
{
    
     /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('name',TextType::class,['label'=>"form.project.name"])
             ->add('start')
             ->add('end')
             ->add('status', ChoiceType::class, [
                    'choices'  => [
                        'form.project.status.finished' => 1,
                        'form.project.status.unfinished' => 0,
                        ]
                    ]
                )

             ->add('performer',TextType::class,['label'=>"form.project.implementer"])
             ->add('content',TextareaType::class,['label'=>"form.project.content","trim"=>"false",'attr'=>['col'=>'10','rows'=>'7']])  ;
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Projects'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'appbundle_projects';
    }


}
