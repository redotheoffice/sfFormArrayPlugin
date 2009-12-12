<?php

/**
 * Decorator to class sfWidgetFormSchemaFormatter, automatically generated by sfDecorator
*/
abstract class sfWidgetFormSchemaFormatterDecorator extends sfWidgetFormSchemaFormatter
{
  protected
    $object = null;
  
  function __construct(sfWidgetFormSchemaFormatter $object)
  {
    $this->object = $object;
  }

  /** forwards to class sfWidgetFormSchemaFormatter (autocode by generate:decorator) */
  public function formatRow($label, $field, $errors = array(), $help = '', $hiddenFields = null)
  {
    return $this->object->formatRow($label, $field, $errors, $help, $hiddenFields);
  }

  /** forwards to class sfWidgetFormSchemaFormatter (autocode by generate:decorator) */
  public function translate($subject, $parameters = array())
  {
    return $this->object->translate($subject, $parameters);
  }

  /** forwards to class sfWidgetFormSchemaFormatter (autocode by generate:decorator) */
  public function formatHelp($help)
  {
    return $this->object->formatHelp($help);
  }

  /** forwards to class sfWidgetFormSchemaFormatter (autocode by generate:decorator) */
  public function formatErrorRow($errors)
  {
    return $this->object->formatErrorRow($errors);
  }

  /** forwards to class sfWidgetFormSchemaFormatter (autocode by generate:decorator) */
  public function formatErrorsForRow($errors)
  {
    return $this->object->formatErrorsForRow($errors);
  }

  /** forwards to class sfWidgetFormSchemaFormatter (autocode by generate:decorator) */
  public function generateLabel($name, $attributes = array())
  {
    return $this->object->generateLabel($name, $attributes);
  }

  /** forwards to class sfWidgetFormSchemaFormatter (autocode by generate:decorator) */
  public function generateLabelName($name)
  {
    return $this->object->generateLabelName($name);
  }

  /** forwards to class sfWidgetFormSchemaFormatter (autocode by generate:decorator) */
  public function getTranslationCatalogue()
  {
    return $this->object->getTranslationCatalogue();
  }

  /** forwards to class sfWidgetFormSchemaFormatter (autocode by generate:decorator) */
  public function setTranslationCatalogue($catalogue)
  {
    return $this->object->setTranslationCatalogue($catalogue);
  }

  /** forwards to class sfWidgetFormSchemaFormatter (autocode by generate:decorator) */
  public function setRowFormat($format)
  {
    return $this->object->setRowFormat($format);
  }

  /** forwards to class sfWidgetFormSchemaFormatter (autocode by generate:decorator) */
  public function getRowFormat()
  {
    return $this->object->getRowFormat();
  }

  /** forwards to class sfWidgetFormSchemaFormatter (autocode by generate:decorator) */
  public function setErrorRowFormat($format)
  {
    return $this->object->setErrorRowFormat($format);
  }

  /** forwards to class sfWidgetFormSchemaFormatter (autocode by generate:decorator) */
  public function getErrorRowFormat()
  {
    return $this->object->getErrorRowFormat();
  }

  /** forwards to class sfWidgetFormSchemaFormatter (autocode by generate:decorator) */
  public function setErrorListFormatInARow($format)
  {
    return $this->object->setErrorListFormatInARow($format);
  }

  /** forwards to class sfWidgetFormSchemaFormatter (autocode by generate:decorator) */
  public function getErrorListFormatInARow()
  {
    return $this->object->getErrorListFormatInARow();
  }

  /** forwards to class sfWidgetFormSchemaFormatter (autocode by generate:decorator) */
  public function setErrorRowFormatInARow($format)
  {
    return $this->object->setErrorRowFormatInARow($format);
  }

  /** forwards to class sfWidgetFormSchemaFormatter (autocode by generate:decorator) */
  public function getErrorRowFormatInARow()
  {
    return $this->object->getErrorRowFormatInARow();
  }

  /** forwards to class sfWidgetFormSchemaFormatter (autocode by generate:decorator) */
  public function setNamedErrorRowFormatInARow($format)
  {
    return $this->object->setNamedErrorRowFormatInARow($format);
  }

  /** forwards to class sfWidgetFormSchemaFormatter (autocode by generate:decorator) */
  public function getNamedErrorRowFormatInARow()
  {
    return $this->object->getNamedErrorRowFormatInARow();
  }

  /** forwards to class sfWidgetFormSchemaFormatter (autocode by generate:decorator) */
  public function setDecoratorFormat($format)
  {
    return $this->object->setDecoratorFormat($format);
  }

  /** forwards to class sfWidgetFormSchemaFormatter (autocode by generate:decorator) */
  public function getDecoratorFormat()
  {
    return $this->object->getDecoratorFormat();
  }

  /** forwards to class sfWidgetFormSchemaFormatter (autocode by generate:decorator) */
  public function setHelpFormat($format)
  {
    return $this->object->setHelpFormat($format);
  }

  /** forwards to class sfWidgetFormSchemaFormatter (autocode by generate:decorator) */
  public function getHelpFormat()
  {
    return $this->object->getHelpFormat();
  }

  /** forwards to class sfWidgetFormSchemaFormatter (autocode by generate:decorator) */
  public function setWidgetSchema(sfWidgetFormSchema $widgetSchema)
  {
    return $this->object->setWidgetSchema($widgetSchema);
  }

  /** forwards to class sfWidgetFormSchemaFormatter (autocode by generate:decorator) */
  public function getWidgetSchema()
  {
    return $this->object->getWidgetSchema();
  }
  
  function __clone()
  {
    $this->object = clone($this->object);
  }
}