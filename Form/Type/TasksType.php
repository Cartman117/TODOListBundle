<?php
/**
 * Created by PhpStorm.
 * User: Kevin
 * Date: 02/06/14
 * Time: 08:17
 */

namespace TODOListBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

/**
 * Class TasksType
 * @package TODOListBundle\Form\Type
 */
class TasksType extends AbstractType {

    private $dataClass;
    private $update;

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $this->update = $options['update'];

        $builder->add('title', TextType::class, [   'label' => false,
                                                    'attr' => [ 'placeholder' => 'Title',
                                                                'class' => 'form-control']]);
        $builder->add('notes', TextareaType::class, [   'label' => false,
                                                        'required' => false,
                                                        'attr' => [ 'placeholder' => 'Notes',
                                                                    'class' => 'form-control']]);
        $builder->add('due', DateType::class, [  'label' => false,
                                                 'attr' => ['placeholder' => 'Due',
                                                 'class' => 'form-control']]);

        if ($this->update) {
            $builder->add('Update', SubmitType::class, ['attr' => ['class' => 'form-control']]);
        } else {
            $builder->add('Create', SubmitType::class, ['attr' => ['class' => 'form-control']]);
        }
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class'    => 'TODOListBundle\Entity\Tasks',
            'update'        => FALSE
        ));
    }

    public function getBlockPrefix()
    {
        return 'TasksType';
    }
} 