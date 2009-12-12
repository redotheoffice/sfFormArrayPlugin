<?php

class sfWidgetFormSchemaFormatterDecoratorArray extends sfWidgetFormSchemaFormatterDecorator
{
  protected
    $schema = null;
  
  function __construct(sfWidgetFormSchemaFormatter $object, sfWidgetFormSchemaArray $schema)
  {
    parent::__construct($object);
    $this->schema = $schema;
  }

  protected function isAddedField($name)
  {
    return in_array($name, $this->schema->getAddedFields());
  }
  
  protected function generateKeyName($name)
  {
    return $this->schema->generateName(sprintf('_key[%s]', $name));
  }

  public function generateLabel($name, $attributes = array())
  {
    if ($this->isAddedField($name))
    {
      return $this->generateLabelName($name);
    }
    return parent::generateLabel($name, $attributes);
  }

  public function generateLabelName($name)
  {
    if ($this->isAddedField($name))
    {
      $keyWidget = $this->schema->getKeyWidget();
      $keyWidget->setIdFormat($this->schema->getOption('id_format'));
      
      $keyMap = $this->schema->getKeyMap();
      $keyMapErrors = $this->schema->getKeyMapErrors();
      $errors = isset($keyMapErrors[$name]) ? $keyMapErrors[$name] : array();
      
      return $this->formatErrorsForRow($errors).$keyWidget->render($this->generateKeyName($name), $keyMap[$name], array(), $errors);
    }
    return parent::generateLabelName($name);
  }
}
