<?php

namespace App\Form;

use App\Entity\Car;
use App\Entity\CarBrand;
use App\Entity\CarBrandModel;
use App\Entity\City;
use App\Enum\CarBody;
use App\Enum\CarFuel;
use App\Enum\CarGearbox;
use App\Enum\CarSeats;
use App\Enum\CarWheelDrive;
use Doctrine\DBAL\Types\StringType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\RangeType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Contracts\Translation\TranslatorInterface;

class CarType extends AbstractType
{
    private $translator;

    public function __construct(TranslatorInterface $translator)
    {
        $this->translator = $translator;
    }
    
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class, [
                'label' => $this->translator->trans('car.title'),
                'constraints' => [
                    new NotBlank(),
                ],
            ])
            ->add('description', TextareaType::class, [
                'label' => $this->translator->trans('car.description'),
                'constraints' => [
                    new NotBlank(),
                ],
            ])
            ->add('prod_year', NumberType::class, [
                'label' => $this->translator->trans('car.prod_year'),
                'constraints' => [
                    new NotBlank(),
                ],
            ])
            ->add('body_type', ChoiceType::class, [
                'choices' => array_keys(CarBody::getList()),
                'placeholder' => '',
                'label' => $this->translator->trans('car.body_type'),
                'constraints' => [
                    new NotBlank(),
                ],
            ])
            ->add('seats', ChoiceType::class, [
                'choices' => array_keys(CarSeats::getList()),
                'placeholder' => '',
                'label' => $this->translator->trans('car.seats'),
            ])
            ->add('fuel', ChoiceType::class, [
                'choices' => array_keys(CarFuel::getList()),
                'placeholder' => '',
                'label' => $this->translator->trans('car.fuel'),
                'constraints' => [
                    new NotBlank(),
                ],
            ])
            ->add('engine_capacity', NumberType::class, [
                'label' => $this->translator->trans('car.engine_capacity'),
            ])
            ->add('gearbox_type', ChoiceType::class, [
                'choices' => array_keys(CarGearbox::getList()),
                'placeholder' => '',
                'label' => $this->translator->trans('car.gearbox_type'),
                'constraints' => [
                    new NotBlank(),
                ],
            ])
            ->add('wheel_drive', ChoiceType::class, [
                'choices' => array_keys(CarWheelDrive::getList()),
                'placeholder' => '',
                'label' => $this->translator->trans('car.wheel_drive'),
            ])
            ->add('odometer', NumberType::class, [
                'label' => $this->translator->trans('car.odometer'),
                'constraints' => [
                    new NotBlank(),
                ],
            ])
            ->add('city', EntityType::class, [
                'class' => City::class,
                'choice_label' => 'title',
                'label' => $this->translator->trans('car.city'),
            ])
            ->add('brand', EntityType::class, [
                'class' => CarBrand::class,
                'choice_label' => 'title',
                'placeholder' => $this->translator->trans('form.choose_option'),
                'label' => $this->translator->trans('car.brand'),
                'constraints' => [
                    new NotBlank(),
                ],
            ])
            ->add('brand_model', EntityType::class, [
                'class' => CarBrandModel::class,
                'choice_label' => 'title',
                'placeholder' => $this->translator->trans('form.choose_option'),
                'label' => $this->translator->trans('car.brand_model'),
                'constraints' => [
                    new NotBlank(),
                ],
            ])
            ->add('file_id', CollectionType::class, [
                'entry_type' => NumberType::class,
                'mapped' => false,
            ])
            ->add('save', SubmitType::class, [
                'label' => $this->translator->trans('form.save'),
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Car::class,
            'csrf_protection' => false,
        ]);
    }
}
