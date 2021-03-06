<?php

namespace App\Form;

use App\Entity\Event;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class UploadEventType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('filename', FileType::class,[
                'label' => 'Image file',
                'constraints' =>[
                    new File([
                        'maxSize' => '4M',
                        'mimeTypes' => [
                            'image/*'
                        ],
                        'mimeTypesMessage' => 'Error, not supported image file'
                    ])
                ]
            ])
            ->add('description')
            ->add('sport_type', ChoiceType::class,[
                'choices' =>[
                    'Football' => 'Football',
                    'Volleyball' => 'Volleyball',
                    'Basketball' => 'Basketball',
                    'Hockey' => 'Hockey',
                    'Running' => 'Running',
                    'Swimming' => 'Swimming',
                    'Skiing' => 'Skiing',
                    'Other' => 'Other'
                ]
            ])
            ->add('event_datetime')
            ->add('people_needed')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Event::class,
        ]);
    }
}
