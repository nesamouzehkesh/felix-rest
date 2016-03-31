<?php

namespace AppBundle\Library\Serializer\AngularSchemaFormSerializer\FieldSerializer;

use Symfony\Component\Form\FormInterface;

/**
 *
 * @author Saman Shafigh <samanshafigh@gmail.com>
 */
class FieldSerializer implements FieldSerializerInterface
{
    /**
     * 
     * @param FormInterface $form
     * @return FieldSerializerInterface
     */
    public static function getSerializer(FormInterface $form)
    {
        $config = $form->getConfig();
        // Symfony 2
        // TODO: need to be implemented in a better way
        //$type = $config->getType()->getName();
        // Symfony 3
        $type = $config->getType()->getInnerType();
        
        if (in_array($type, array('checkbox', 'choice', 'number'))) {
            $serializer = __NAMESPACE__ . sprintf("\%sFieldSerializer", ucfirst($type));
            
            return new $serializer;
        }
        
        return new FieldSerializer();
    }
        
    /**
     * Get schema data
     * 
     * @param FormInterface $form
     * @return array
     */
    public function getFieldStructure(FormInterface $form)
    {
        $config = $form->getConfig();
        // Symfony 2
        //$type = $config->getType()->getName();
        // Symfony 3
        $type = $config->getType()->getInnerType();
        $label = $this->getFormLabel($form);
        $name = $form->getName();
        
        return array(
            'name' => $name,
            'schema' => array(
                'type' => $type,
                'title' => $label,
            ),
            'form' => array(
                'key' => $name,
                'type' => $type,
                'title' => $label,
            ),
        );          
    }
    
    /**
     * 
     * @param FormInterface $form
     * @param type $data
     * @return type
     */
    public function getModel(FormInterface $form, $data)
    {
        return $data;
    }
    
    /**
     * 
     * @param FormInterface $form
     * @return type
     */
    protected function getFormLabel(FormInterface $form)
    {
        $config = $form->getConfig();
        $label = $config->getOption('label');
        
        if (null === $label) {
            $label = ucfirst(preg_replace('/(?<!^)([A-Z])/', '-\\1', $form->getName()));
        }
        
        return $label;
    }    
}
