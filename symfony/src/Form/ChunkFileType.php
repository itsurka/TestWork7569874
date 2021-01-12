<?php

declare(strict_types=1);

namespace App\Form;


use App\Service\FileUploadService;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\NotNull;
use Symfony\Component\Validator\Constraints\Regex;

class ChunkFileType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        //        ccept
//        */*
//Accept-Encoding
//	gzip, deflate
//Accept-Language
//	ru-RU,ru;q=0.8,en-US;q=0.5,en;q=0.3
//Authorization
//	0118287f-3df4-4851-98e2-cc85de2c4935
//Connection
//	keep-alive
//Content-Length
//	102117
//Content-Range
//	bytes 0-102116/102117
//Content-Type
//	image/jpeg
//Cookie
//	PHPSESSID=e4j10p771hd0odsdi9q7lprvas
//FileName
//	file_example_JPG_100kB.jpeg
//Host
//	autosale.local
//Origin
//	http://autosale.local
//Referer
//	http://autosale.local/
//User-Agent
//	Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:84.0) Gecko/20100101 Firefox/84.0

        $builder
            ->add('content_length', TextType::class, [
                'required' => true,
                'constraints' => [
                    new NotBlank(),
                ],
            ])
            ->add('content_range', TextType::class, [
                'required' => true,
                'constraints' => [
                    new Regex([
                        'pattern' => FileUploadService::CONTENT_RANGE_REGEXP,
                    ]),
                    new NotNull(),
                ],
            ])
            ->add('content_type', ChoiceType::class, [
                'choices' => [
                    'image/jpeg',
                    'image/jpg',
                    'image/png',
                    'image/webp',
                ],
                'constraints' => [
                    new NotBlank(),
                ],
            ])
            ->add('file_name', TextType::class, [
                'constraints' => [
                    new NotBlank(),
                ],
            ])
        ;

        /*/bytes (\d+)-(\d+)\/(\d+)/gm*/
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'csrf_protection' => false,
        ]);
    }
}
