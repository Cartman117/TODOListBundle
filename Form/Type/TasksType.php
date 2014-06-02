<?php
/**
 * Created by PhpStorm.
 * User: Kevin
 * Date: 02/06/14
 * Time: 08:17
 */

namespace Acme\TODOListBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class TasksType extends AbstractType {
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('name', 'text');
        $builder->add('content', 'textarea');
        $builder->add('Créer', 'submit');
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Acme\TODOListBundle\Entity\Tasks'
        ));
    }

    public function getName()
    {
        return 'tasksType';
    }
} 