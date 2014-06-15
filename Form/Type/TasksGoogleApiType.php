<?php
namespace Acme\TODOListBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class TasksGoogleApiType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('title', 'text');
        $builder->add('notes', 'textarea');
        $builder->add('due', 'date');
        $builder->add('Créer', 'submit');
    }

    public function getName()
    {
        return 'taskListsGoogleApiType';
    }
}