<?php

namespace AppBundle\Library\Serializer\AngularSchemaFormSerializer;

use Symfony\Component\Form\FormInterface;
use AppBundle\Library\Component\AngularSchemaSerializedForm;

/**
 * A Symfony FormSerializer for angular-schema-form
 * https://github.com/json-schema-form/angular-schema-form
 * 
 * Standard Options for "schema"
 * Look at http://json-schema.org/ for more information
 * 
 * 
 * Standard Options for "form"
 *  {
 *    key: "address.street",      // The dot notatin to the attribute on the model
 *    type: "text",               // Type of field
 *    title: "Street",            // Title of field, taken from schema if available
 *    notitle: false,             // Set to true to hide title
 *    description: "Street name", // A description, taken from schema if available, can be HTML
 *    validationMessage: "Oh noes, please write a proper address",  // A custom validation error message
 *    onChange: "valueChanged(form.key,modelValue)", // onChange event handler, expression or function
 *    feedback: false,             // Inline feedback icons
 *    disableSuccessState: false,  // Set true to NOT apply 'has-success' class to a field that was validated successfully
 *    disableErrorState: false,    // Set true to NOT apply 'has-error' class to a field that failed validation
 *    placeholder: "Input...",     // placeholder on inputs and textarea
 *    ngModelOptions: { ... },     // Passed along to ng-model-options
 *    readonly: true,              // Same effect as readOnly in schema. Put on a fieldset or array
 *                                 // and their items will inherit it.
 *    htmlClass: "street foobar",  // CSS Class(es) to be added to the container div
 *    fieldHtmlClass: "street"     // CSS Class(es) to be added to field input (or similar)
 *    labelHtmlClass: "street"     // CSS Class(es) to be added to the label of the field (or similar)
 *    copyValueTo: ["address.street"],     // Copy values to these schema keys.
 *    condition: "person.age < 18" // Show or hide field depending on an angular expression
 *    destroyStrategy: "remove"    // One of "null", "empty" , "remove", or 'retain'. Changes model on $destroy event. default is "remove".
 *  }
 * 
 * @author Saman Shafigh <samanshafigh@gmail.com>
 */
class FormSerializer
{
    private $serializedData = array(
        'schema' => array(
            'title' => 'Example Schema',
            'type' => 'object',
            'properties' => array(),
            'required' => array()
        ),
        'form' => array(),
        'model' => array(),
        'error' => array()
    );
        
    /**
     * Get form serialized content
     * 
     * @param FormInterface $form
     * @return Array
     */
    public static function serialize(FormInterface $form)
    {
        $serializer = new FormSerializer();
        $serializedForm = $serializer->getSerializedForm($form);
        
        return $serializedForm->getContent();
    }
    
    /**
     * Get form serialized object
     * 
     * @param FormInterface $form
     * @return AngularSchemaSerializedForm
     */
    public function getSerializedForm(FormInterface $form)
    {
        // Add form serialized errors if form is not valid
        $error = false;
        /*
        if ($form->isBound() && ! $form->isValid()) {
            $this->addFormError($form);
            $error = true;
        }
         * 
         */
        
        // Serialize form
        $this->serializeForm($form, $error, '-');
        $this->addExtraFormFieldData($form);
        
        return new AngularSchemaSerializedForm($form, $this->serializedData);
    }

    /**
     * 
     * @param FormInterface $form
     * @param type $error
     * @return type
     */
    private function serializeForm(FormInterface $form, $error = false, $path)
    {
        if ($form->all()) {
            foreach ($form->all() as $child) {
                $name = $child->getName();
                $config = $child->getConfig();
                // Symfony 2
                //$type = $config->getType()->getName();
                // Symfony 3
                $type = $config->getType()->getInnerType();
                if ($type == 'choice') {
                    $data = $child->getViewData();
                } else {
                    $data = $this->serializeForm($child, $error, $path.'-');
                }

                $serializer = FieldSerializer\FieldSerializer::getSerializer($child);
                $structure = $serializer->getFieldStructure($child);
                $model = $serializer->getModel($child, $data);
                    
                if ($child->isRequired()) {
                    $this->serializedData['schema']['required'][] = $name;
                }

                $this->serializedData['schema']['properties'][$name] = $structure['schema'];                
                $this->serializedData['form'][] = $structure['form'];
                $this->serializedData['model'][$name] = $model;
            }
        }
        
        return $form->getViewData();
    }
    
    /**
     * 
     * @param FormInterface $form
     * @return type
     */
    private function addFormError(FormInterface $form)
    {
        $result = array();
        foreach ($form->getErrors() as $error) {
            $result[] = $error->getMessage();
        }
        $this->serializedData['error'] = $result;
        
        return true;
    }
        
    /**
     * 
     * @param FormInterface $child
     * @param type $data
     * @return boolean
     */
    private function addExtraFormFieldData(FormInterface $child)
    {
        $this->serializedData['form'][] = array(
            'type' => 'submit',
            'title' => 'submit',
        );
        /*
        $this->serializedData['form'][] = array(
            'type' => 'reset',
            'label' => 'reset',
        );
        */
        
        return true;
    }
}