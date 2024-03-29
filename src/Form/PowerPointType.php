<?php

namespace App\Form;

use App\Entity\PowerPoint;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\ColorType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
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
            ->add('nbDansesSlides', IntegerType::class, [

                'label'=> 'Nombre de danses par slides',
                'attr'=>['min'=>1, 'max'=>7]
            ])
            ->add('primaryDanseColor', ColorType::class,[
                'label'=>'Couleur de la danse principale',
            ])
            ->add('secondaryDanseColor', ColorType::class,[
                'label'=>'Couleur des danses secondaires',
            ])
            ->add('backgroundSlidesDefaut', CheckboxType::class, [
                'label'=> "Utiliser l'arrière plan par défaut",
                'required' => false,
            ])
            ->add('backgroundSlidesImageFile', FileType::class, [
                'required' => false,
                'label' => 'Arrière plan de la présentation, fonctionne si "arrière-plan par défaut" est décoché',
            ])
            ->add('onlySave', SubmitType::class, [
                'label'=> 'Enregistrer',
                'disabled' => $options['disabled_button_generate'],
                'attr'=> ['class'=> 'btn btn-primary btn-submit-form']
            ])
            ->add('save', SubmitType::class, [
                'label'=> $options['name_button'],
                'attr'=>['class'=>'btn btn-info btn-submit-form']
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
