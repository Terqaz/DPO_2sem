<?php

namespace App\Form;

use App\Entity\Book;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BookType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, [
                'label' => 'Название'
            ])
            ->add('author', TextType::class, [
                'label' => 'Автор'
            ])
            ->add('coverUrl', TextType::class, [
                'label' => 'Ссылка на обложку'
            ])
            ->add('fileUrl', TextType::class, [
                'label' => 'Ссылка на скачивание книги'
            ])
            ->add('dateRead')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Book::class,
        ]);
    }
}
