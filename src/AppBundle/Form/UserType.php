<?php

namespace AppBundle\Form;

use AppBundle\Entity\Competence;
use AppBundle\Repository\CompetenceRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Vich\UploaderBundle\Form\Type\VichImageType;

class UserType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email', EmailType::class, ['label' => 'form.email', 'translation_domain' => 'FOSUserBundle'])
            ->add('username', null, ['label' => 'Nom', 'translation_domain' => 'FOSUserBundle'])
            ->add('plainPassword', RepeatedType::class, [
                'type' => PasswordType::class,
                'options' => [
                    'translation_domain' => 'FOSUserBundle',
                    'attr' => [
                        'autocomplete' => 'new-password',
                    ],
                ],
                'first_options' => ['label' => 'form.password'],
                'second_options' => ['label' => 'form.password_confirmation'],
                'invalid_message' => 'fos_user.password.mismatch',
            ]) ->add("description",TextareaType::class,array( 'required' => false, 'label' => 'Description :'))
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
            ->add("imageFile",VichImageType::class,array( 'required' => false, 'label' => 'Image de Profil : '));

        ;
      //  $builder->add('dateInscrip')->add('role')->add('status')->add('description')->add('imageName')->add('imageSize')->add('updatedAt')->add('competences');
       /* $builder->add('username', TextType::class, array('label' => 'Nom d\'utilisateur :'))
            ->add('email', TextType::class, array('label' => 'Adresse e-mail :'))
            ->add('password', PasswordType::class, array('label' => 'Mot de passe :', 'required' => false))->add('Cree', SubmitType::class, [
                'attr' => ['class' => 'save'],
            ]);;*/

    }/**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\User'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'appbundle_user';
    }


}
