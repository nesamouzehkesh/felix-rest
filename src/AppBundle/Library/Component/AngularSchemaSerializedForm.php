<?php

namespace AppBundle\Library\Component;

use Symfony\Component\Form\FormInterface;

/**
 * AngularSchemaSerializedForm
 */
class AngularSchemaSerializedForm
{
    /**
     * @var array
     */
    private $info;
    
    /**
     * @var array
     */
    private $schema;
    
    /**
     * @var array
     */
    private $form;
    
    /**
     *
     * @var array 
     */
    private $model;
    
    /**
     *
     * @var array 
     */
    private $error;
    
    /**
     * 
     * @param FormInterface $form
     * @param array $serializedData
     */
    public function __construct(FormInterface $form, array $serializedData)
    {
        $date = new \DateTime();
        $this->info = array(
            'name' => $form->getName(),
            'time' => $date->getTimestamp(),
            );
        
        $this->schema = $serializedData['schema'];
        $this->error = $serializedData['error'];
        $this->form = $serializedData['form'];
        $this->model = $serializedData['model'];
    }

    /**
     * Get schema
     *
     * @return array 
     */
    public function getSchema()
    {
        return $this->schema;
    }

    /**
     * Get form
     *
     * @return array 
     */
    public function getForm()
    {
        return $this->form;
    }

    /**
     * Get model
     *
     * @return array 
     */
    public function getModel()
    {
        return $this->model;
    }
    
    /**
     * Get error
     *
     * @return array 
     */
    public function getError()
    {
        return $this->error;
    }
    
    /**
     * Get content
     *
     * @return array 
     */
    public function getContent()
    {
        $content = array(
            'info' => $this->info,
            'schema' => $this->schema,
            'form' => $this->form,
            'model' => $this->model,
        );
        
        if (count($this->error) > 0) {
            $content['error'] = $this->error;
        }
        
        return $content;
    }    
}