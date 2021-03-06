<?php

namespace App\Form;

use App\Entity\Candidate;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\FormBuilderInterface;
use Vich\UploaderBundle\Form\Type\VichImageType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;

class CandidateType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name')
            ->add('firstname')
            ->add('birthdate', DateType::class, [
                'required'   => false,
                'widget' => 'single_text',
            ])
            ->add('phone')
            ->add('mobile')
            ->add('street')
            ->add('houseNumber')
            ->add('zip')
            ->add('city')
            ->add('mail', EmailType::class, [
                'required'   => false
            ])
            ->add('pictureFile', VichImageType::class, [
                'required'   => false,
                'attr' => ['capture' => 'camera'],
                'download_uri' => false,
                'allow_delete' => false,
                /*
                'download_label' => '...',
                'download_uri' => true,
                'image_uri' => true,
                'asset_helper' => true,
                */
            ])
            ->add('onSite', CheckboxType::class, [
                'required'   => false,
                'attr' => ['class' => 'custom-control-input'],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Candidate::class,
            'translation_domain' => 'forms',
        ]);
    }

    public function getBlockPrefix()
    {
        return '';
    }
}
