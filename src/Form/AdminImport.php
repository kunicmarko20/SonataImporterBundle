<?php

declare(strict_types=1);

namespace KunicMarko\SonataImporterBundle\Form;

use KunicMarko\SonataImporterBundle\DTO\AdminImport as AdminImportDTO;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

/**
 * @author Marko Kunic <kunicmarko20@gmail.com>
 */
final class AdminImport extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('file', FileType::class)
            ->add('import', SubmitType::class, ['label' => 'Import', 'attr' => ['class' => 'btn btn-success']]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        parent::configureOptions($resolver);

        $resolver->setDefaults([
            'data_class' => AdminImportDTO::class,
            'csrf_protection' => true,
        ]);
    }
}
