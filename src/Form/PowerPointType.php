<?php

namespace App\Form;

use App\Entity\PowerPoint;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PowerPointType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('danses', CollectionType::class,[
                'entry_type'=>DanseType::class,
                'allow_add'=> true,
                'entry_options'=> ['label'=>false],
            ])
            ->add('save', SubmitType::class, [
                'label'=> $options['name_button'],
                'attr'=>['class'=>'btn btn-info']
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => PowerPoint::class,
            'name_button'=>'Générer'
        ]);

        $resolver->setAllowedTypes('name_button', 'string');
    }
}
