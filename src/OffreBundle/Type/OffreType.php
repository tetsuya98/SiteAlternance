<?php
namespace OffreBundle\Type;

use AppBundle\Entity\Competence;
use AppBundle\Repository\CompetenceRepository;
use OffreBundle\Entity\TypeContrat;
use OffreBundle\Repository\TypeContratRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Vich\UploaderBundle\Form\Type\VichImageType;

//
class OffreType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('titre', TextType::class, ['label' => "Intitulé de l'offre"])
            ->add('description', TextareaType::class, ['label' => "Description de l'offre"])
            ->add('nbSemaine', NumberType::class, ['label' => "Durée du stage (en semaine)"])
            ->add('competences', EntityType::class,         [
                'label' => 'Domaines et compétences',
                'class' => Competence::class,
                'choice_label' => 'competences',
                'query_builder' => function (CompetenceRepository $rep) {
                    return $rep->createQueryBuilder('u')
                        ->orderBy('u.competences', 'ASC');
                },
                'multiple' => true,
                'required' => false
            ])
            ->add('typeContrat', EntityType::class, [
                'label' => 'Type de contrat',
                'class' => TypeContrat::class,
                'choice_label' => 'titre',
                'query_builder' => function (TypeContratRepository $rep) {
                    return $rep->createQueryBuilder('u')
                        ->orderBy('u.titre', 'ASC');
                }
            ])
            ->add('imageFile', VichImageType::class, ['required' => false])
            ->add('submit', SubmitType::class, ['label' => "Publier l'offre"])
            ->getForm();
    }
}