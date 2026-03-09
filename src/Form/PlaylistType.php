<?php

namespace App\Form;

use App\Entity\Playlist;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Formulaire de création et de modification d'une playlist.
 * Génère un formulaire Symfony lié à l'entité Playlist avec les champs :
 * - Nom de la playlist (obligatoire)
 * - Description de la playlist (facultative)
 */

class PlaylistType extends AbstractType
{

    /**
     * Construit le formulaire de création/modification d'une playlist.
     * Définit les champs du formulaire et leurs options.
     *
     * @param FormBuilderInterface $builder Le constructeur de formulaire Symfony
     * @param array $options
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', null, ['required' => true])
            ->add('description')
        ;
    }

    /**
     * Configure les options par défaut du formulaire.
     * Associe le formulaire à l'entité Playlist.
     *
     * @param OptionsResolver $resolver Le résolveur d'options Symfony
     * @return void
     */


    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Playlist::class,
        ]);
    }
}
