<?php

namespace App\Form;

use App\Entity\PowerPoint;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PowerPointType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, [
                'label'=>'Nom du powerpoint'
            ])
            ->add('danses', CollectionType::class,[
                'entry_type'=>DanseType::class,
                'allow_add'=> true,
                'allow_delete'=>true,
                'entry_options'=> ['label'=>false, 'attr' => ['class' => 'champ_exist_danses']],

            ])
            ->add('onlySave', SubmitType::class, [
                'label'=> 'Enregistrer',
                'disabled' => $options['disabled_button_generate']
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
            'name_button'=>'Générer',
            'disabled_button_generate'=>true,
        ]);

        $resolver->setAllowedTypes('name_button', 'string');
    }
}
