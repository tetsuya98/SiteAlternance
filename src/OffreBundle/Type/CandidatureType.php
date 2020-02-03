<?php
namespace OffreBundle\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Vich\UploaderBundle\Form\Type\VichFileType;

class CandidatureType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('motivation', TextareaType::class, ['label' => "Motivations"])
            ->add('file', VichFileType::class, ['required' => false, 'label' => 'Pièce jointe'])
            ->add('submit', SubmitType::class, ['label' => "Candidater"])
            ->getForm();
    }
}