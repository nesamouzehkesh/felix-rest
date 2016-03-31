<?php

namespace AppBundle\Library\Serializer\AngularSchemaFormSerializer\FieldSerializer;

use Symfony\Component\Form\FormInterface;

/**
 *
 * @author Saman Shafigh <samanshafigh@gmail.com>
 */
interface FieldSerializerInterface
{
    /**
     * Get field structure data
     * 
     * @param FormInterface $form
     * @return array
     */
    public function getFieldStructure(FormInterface $form);
    
    /**
     * Get field model data
     * 
     * @param FormInterface $form
     * @param type $data
     */
    public function getModel(FormInterface $form, $data);
}
