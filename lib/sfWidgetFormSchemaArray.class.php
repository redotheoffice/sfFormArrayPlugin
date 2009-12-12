<?php

/**
 * sfWidgetFormSchemaArray represents a widget which allows creating key-value forms
 *
 * @package default
 * @author The Young Shepherd
 **/
class sfWidgetFormSchemaArray extends sfWidgetFormSchema
{
  protected
    $valueWidget = null,
    $keyWidget = null,
    $addedFields = array(),
    $keyMap = array(),
    $keyMapErrors = null;
    
  public function __construct($fields = null, $options = array(), $attributes = array(), $labels = array(), $helps = array())
  {
    if (is_array($fields))
    {
      $this->keyWidget = isset($fields['_key']) ? $fields['_key'] : new sfWidgetFormInputText();
      $this->valueWidget = isset($fields['_value']) ? $fields['_value'] : new sfWidgetFormInputText();
      unset($fields['_key'], $fields['_value']);
    }

    parent::__construct($fields, $options, $attributes, $labels, $helps);
  }

  public function setValueWidget(sfWidgetForm $widget)
  {
    $this->valueWidget = $widget;
  }

  public function getValueWidget()
  {
    return $this->valueWidget;
  }
  
  public function setKeyWidget(sfWidgetForm $widget)
  {
    $this->keyWidget = $widget;
  }
  
  public function getKeyWidget()
  {
    return $this->keyWidget;
  }
  
  public function getAddedFields()
  {
    return $this->addedFields;
  }

  public function getKeyMap()
  {
    return $this->keyMap;
  }
  
  public function getKeyMapErrors()
  {
    return $this->keyMapErrors;
  }

  public function render($name, $values = array(), $attributes = array(), $errors = array())
  {
    $this->addedFields = array();
    $this->keyMap = array();
    
    // extract the keymap from the values
    if (isset($values['_key']))
    {
      $this->keyMap = $values['_key'];
      unset($values['_key']); 
    }
    
    // add widget for each key=>value combination in the array
    foreach ($values as $key => $value)
    {
      if (!isset($this[$key]))
      {
        $this->addedFields[] = $key;
        $this[$key] = $this->getValueWidget(); 
      }
    }
    
    // make sure all added fields exist in the keymap
    $flatMap = array_combine($this->addedFields, $this->addedFields);
    $this->keyMap = array_merge($flatMap, $this->keyMap);


    // remove the keymap errors, otherwise sfWidgetFormSchema thinks they are global
    // errors to the schema
    $this->keyMapErrors = isset($errors['_key']) ? $errors['_key'] : null;
    if ($errors instanceof sfValidatorErrorSchema)
    {
      $namedErrors = $errors->getNamedErrors();
      unset($namedErrors['_key']);
      
      $errors = new sfValidatorErrorSchema($errors->getValidator(), $errors->getGlobalErrors());
      $errors->addErrors($namedErrors);
    }
    else
    {
      unset($errors['_key']);
    }    
    
    // call the regular rendering method of sfWidgetFormSchema
    $result = parent::render($name, $values, $attributes, $errors);
    
    // remove the added widget to this schema
    foreach ($this->addedFields as $key)
    {
      unset($this[$key]);
    }
    
    $this->addedFields = array();
    
    // decorate the output so that this is a nested entry
    return strtr($this->getFormFormatter()->getDecoratorFormat(), array('%content%' => $result));
  }
  
  public function getFormFormatter()
  {
    // the formformatter is decorated with special methods making array
    // forms possible (for generating the labels)
    $formFormatter = parent::getFormFormatter();
    return new sfWidgetFormSchemaFormatterDecoratorArray($formFormatter, $this);
  }
}
