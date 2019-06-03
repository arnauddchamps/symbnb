<?php

namespace App\Form;

use App\Entity\Ad;
use App\Form\ImageType;
use App\Form\ApplicationType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

class AdType extends ApplicationType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'title', 
                TextType::class, 
                $this->getConfiguration("Titre","Tapez un titre pour votre annonce")
            )
            ->add('slug', 
                TextType::class, 
                $this->getConfiguration("Chaine URL","Adresse web (automatique)", [
                    'required' => false
                ])
            )
            ->add('coverImage', 
                UrlType::class, 
                $this->getConfiguration("Url de l'image principale","Donnez l'adresse d'une image")
            )
            ->add('introduction', 
                TextType::class, 
                $this->getConfiguration("Introduction","Tapez une intro")
            )
            ->add('content', 
                TextareaType::class, 
                $this->getConfiguration("Description","Tapez une description à l'annonce")
            )
            ->add('rooms', 
                IntegerType::class, 
                $this->getConfiguration("Nombre de chambres","vous avez cb de chambres ?")
            )
            ->add('price', 
                MoneyType::class, 
                $this->getConfiguration("Prix par nuit","Saisir le prix de la nuitée")
            )
            ->add('images',
                CollectionType::class,
                [ 
                    'entry_type' => ImageType::class,
                    'allow_add' => true,
                    'allow_delete' => true
                ]
            )
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Ad::class,
        ]);
    }
}
