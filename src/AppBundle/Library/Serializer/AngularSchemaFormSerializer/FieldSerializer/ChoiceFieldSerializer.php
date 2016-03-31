<?php

namespace AppBundle\Library\Serializer\AngularSchemaFormSerializer\FieldSerializer;

use Symfony\Component\Form\FormInterface;

/**
 *
 * @author Saman Shafigh <samanshafigh@gmail.com>
 */
class ChoiceFieldSerializer extends FieldSerializer
{
    /**
     * Get field data
     * 
     * @param FormInterface $form
     * @return array
     */
    public function getFieldStructure(FormInterface $form)
    {
        $config = $form->getConfig();
        $options = $config->getOptions();
        $label = $this->getFormLabel($form);
        $name = $form->getName();
        
        if ($options['expanded']) {
            if ($options['multiple']) {
                return array(
                    'name' => $name,
                    'schema' => array(
                        'type' => 'array',
                        'title' => $label,
                        'items' => array(
                            'enum' => array_values($options['choices']),
                            'type' => 'string',
                        )
                    ),
                    'form' => array(
                        'key' => $name,
                        'type' => 'checkboxes',
                        'title' => $label,
                        'titleMap' => $options['choices']
                    ),
                );                  
            } else {
                return array(
                    'name' => $name,
                    'schema' => array(
                        'type' => 'string',
                        'title' => $label,
                        'enum' => array_values($options['choices']),
                    ),
                    'form' => array(
                        'key' => $name,
                        'type' => 'radios',
                        'title' => $label,
                        'titleMap' => $options['choices']
                    ),
                );                  
            }
        }
        
        return array(
            'name' => $name,
            'schema' => array(
                'type' => 'choice',
                'title' => $label,
                'enum' => $options['choices']
            ),
            'form' => array(
                'key' => $name,
                'type' => 'select',
                'title' => $label,
                'titleMap' => $options['choices']
            ),
        );        
    }  
}
