<?php
namespace OffreBundle\Type;

use DateTime;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;

class CandidatureAcceptType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('dateMeeting', DateTimeType::class, [
                'label' => "Date du RDV",
                'widget' => 'single_text',
                'input' => 'datetime',
                'data' => new DateTime()])
            ->add('submit', SubmitType::class, ['label' => "Accepter"])
            ->getForm();
    }
}