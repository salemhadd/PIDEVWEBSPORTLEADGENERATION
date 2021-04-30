<?php

namespace App\Form;
use App\Entity\User;
use App\Entity\Commande;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use App\Entity\Produit;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;




class CommandeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('idproduit',EntityType::class,['class'=>Produit::class,'choice_label'=>'idproduit'])
            ->add('date',DateTimeType::class, ['widget' => 'single_text' ])
            ->add('email')
            ->add('id',EntityType::class,['class'=>User::class,'choice_label'=>'id'])

;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Commande::class,
        ]);
    }
}
