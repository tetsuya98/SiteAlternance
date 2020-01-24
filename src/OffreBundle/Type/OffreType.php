<?php
namespace OffreBundle\Type;

use AppBundle\Entity\Competence;
use AppBundle\Repository\CompetenceRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class OffreType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('titre', TextType::class, ['label' => "Titre de l'offre"])
            ->add('description', TextareaType::class, ['label' => "Description"])
            ->add('nbSemaine', NumberType::class, ['label' => "Durée du stage (en semaine)"])
            ->add('competences', EntityType::class, [
                'label' => 'Compétences de l\'offre',
                'class' => Competence::class,
                'choice_label' => 'competences',
                'query_builder' => function (CompetenceRepository $rep) {
                    return $rep->createQueryBuilder('u')
                        ->orderBy('u.competences', 'ASC');
                },
                'multiple' => true,
            ])
            ->add('submit', SubmitType::class, ['label' => "Publier l'offre"])
            ->getForm();
    }
}