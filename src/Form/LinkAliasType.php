<?php declare(strict_types=1);

namespace App\Form;

use App\Entity\LinkAlias;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\GreaterThan;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\Url;

class LinkAliasType extends AbstractType
{
    private const MIN_ALIAS_LENGTH = 2;
    private const MAX_ALIAS_LENGTH = 13;

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'originalLink',
                UrlType::class,
                [
                    'required'    => true,
                    'constraints' => [
                        new Url(),
                    ],
                ]
            )
            ->add(
                'alias',
                TextType::class,
                [
                    'required'    => true,
                    'constraints' => [
                        new Length([
                            'min' => self::MIN_ALIAS_LENGTH,
                            'max' => self::MAX_ALIAS_LENGTH
                        ]),
                    ],
                ]
            )
            ->add('useExpire', CheckboxType::class, [
                'required' => false
            ])
            ->add('expired', DateTimeType::class, [
                'constraints' => [
                    new GreaterThan(['value' => new \DateTime()])
                ]
            ])
            ->add('save', SubmitType::class, ['label' => 'generate']);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            [
                'data_class' => LinkAlias::class,
            ]
        );
    }
}