<?php

namespace AppBundle\Library\Serializer\AngularSchemaFormSerializer\FieldSerializer;

use Symfony\Component\Form\FormInterface;

/**
 *
 * @author Saman Shafigh <samanshafigh@gmail.com>
 */
class CheckboxFieldSerializer extends FieldSerializer
{
    /**
     * Get field data
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
                'type' => 'boolean',
                'title' => $label,
            ),
            'form' => array(
                'key' => $name,
                'type' => 'checkbox',
                'title' => $label,
            ),
        );        
    }  
}