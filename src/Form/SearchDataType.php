<?php
namespace App\Form;

use App\Model\SearchData;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;

/**
 * Classe de formulaire pour la recherche.
 * 
 * Cette classe définit un formulaire de recherche avec un champ de texte.
 */
class SearchDataType extends AbstractType
{
    /**
     * Construit le formulaire de recherche.
     *
     * @param FormBuilderInterface $builder L'interface de construction du formulaire.
     * @param array $options Les options du formulaire.
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('search', TextType::class, [
                'required' => false,
                'label' => false,
                'attr' => [
                    'placeholder' => 'Rechercher via un mot-clé',
                    'class' => 'form-control',
                ],
            ])
        ;
    }

    /**
     * Configure les options du formulaire.
     *
     * @param OptionsResolver $resolver L'interface de résolution des options.
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => SearchData::class,
            'method' => 'GET',
            'csrf_protection' => false,
        ]);
    }
}