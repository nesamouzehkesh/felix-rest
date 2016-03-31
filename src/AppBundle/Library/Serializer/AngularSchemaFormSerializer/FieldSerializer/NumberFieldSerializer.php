<?php

namespace AppBundle\Library\Serializer\AngularSchemaFormSerializer\FieldSerializer;

use Symfony\Component\Form\FormInterface;

/**
 *
 * @author Saman Shafigh <samanshafigh@gmail.com>
 */
class NumberFieldSerializer extends FieldSerializer
{
    /**
     * Get schema data
     * 
     * @param FormInterface $form
     * @return array
     */
    public function getFieldStructure(FormInterface $form)
    {
        $label = $this->getFormLabel($form);
        $name = $form->getName();
        
        return array(
            'name' => $name,
            'schema' => array(
                'type' => 'number',
                'title' => $label,
            ),
            'form' => array(
                'key' => $name,
                'type' => 'number',
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
        return intval($data);
    } 
}