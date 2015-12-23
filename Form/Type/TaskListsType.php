<?php
namespace TODOListBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

/**
 * Class TaskListsType
 * @package TODOListBundle\Form\Type
 */
class TaskListsType extends AbstractType
{
    private $update;
    private $dataClass;

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $this->update = $options['update'];

        $builder->add('title', TextType::class, [   'label' => false,
                                                    'attr' => [ 'placeholder' => 'Title',
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
            'data_class'    => 'TODOListBundle\Entity\TaskLists',
            'update'        => FALSE
        ));
    }

    public function getBlockPrefix()
    {
        return "TaskListsType";
    }
}