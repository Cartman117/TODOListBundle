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

    private $update;
    private $dataClass;

    public function  __construct($update = false, $dataClass = "Acme\TODOListBundle\Entity\Tasks")
    {
        $this->update = $update;
        $this->dataClass = $dataClass;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('title', 'text', ["label" => false,
                                        "attr" => ["placeholder" => "Title",
                                                   "class" => "form-control"]]);
        $builder->add('notes', 'textarea', ["label" => false,
                                            "required" => false,
                                            "attr" => ["placeholder" => "Notes",
                                                       "class" => "form-control"]]);
        $builder->add('due', 'date', ["label" => false,
                                      "attr" => ["placeholder" => "Due",
                                                 "class" => "form-control"]]);

        if($this->update){
            $builder->add("Update", "submit", ["attr" => ["class" => "form-control"]]);
        }
        else{
            $builder->add("Create", "submit", ["attr" => ["class" => "form-control"]]);
        }
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => $this->dataClass
        ));
    }

    public function getName()
    {
        return 'tasksType';
    }
} 