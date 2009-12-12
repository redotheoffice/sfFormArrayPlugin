<?php

/**
 * sfValidatorSchemaArray represents an array of fields with unknown length
 */
class sfValidatorSchemaArray extends sfValidatorSchema
{
  protected
    $valueValidator = null,
    $keyValidator = null,
    $addedFields = array(),
    $keyMutations = array();
    
  /**
   * Constructor.
   *
   * The first argument can be:
   *
   *  * null
   *  * an array of named sfValidatorBase instances
   *    - key '_key' holds key validator or is not defined
   *    - key '_value' holds value validator or is not defined
   *    - all other keys are ignored
   *
   * @param mixed $fields    Array of sfValidatorBase
   * @param array $options   An array of options
   * @param array $messages  An array of error messages
   *
   * @see sfValidatorBase
   */
  public function __construct($fields = array(), $options = array(), $messages = array())
  {
    $this->addMessage('key_map_duplicate', 'Another fieldname \'%key%\' already exists.');
    $this->addMessage('key_is_reserved', 'The name \'%key%\' is reserved for another field.');

    $this->keyValidator = isset($fields['_key']) ? $fields['_key'] : new sfValidatorString();
    $this->valueValidator = isset($fields['_value']) ? $fields['_value'] : new sfValidatorPass();
    unset($fields['_key'], $fields['_value']);
    
    parent::__construct($fields, $options, $messages);
  }

  public function setValueValidator(sfValidatorBase $validator)
  {
    $this->valueValidator = $validator;
  }

  public function getValueValidator()
  {
    return $this->valueValidator;
  }

  public function setKeyValidator(sfValidatorBase $validator)
  {
    $this->keyValidator = $validator;
  }

  public function getKeyValidator()
  {
    return $this->keyValidator;
  }

  /**
   * @see sfValidatorBase
   */
  protected function doClean($values)
  {
    $errorSchema = new sfValidatorErrorSchema($this);
    
    // try to process all key mutations
    if (isset($values['_key']))
    {
      $mapErrors = new sfValidatorErrorSchema($this);
      $keyMap = $values['_key'];
      
      // check if there are keys added which map to defined key values
      foreach ($keyMap as $key => $newKey)
      {
        // check if the new key is a legal form
        try
        {
          $keyMap[$key] = $this->keyValidator->clean($newKey);
        }
        catch (sfValidatorError $e)
        {
          $mapErrors->addError($e, $key);
        }

        // check if the key is already defined with a fixed validator in the schema
        if (isset($this->fields[$newKey]) || $newKey == '_key')
        {
          $e = new sfValidatorError($this, 'key_is_reserved', array(
            'key' => $newKey
          ));
          $mapErrors->addError($e, $key);
        }
      }
      
      // $keyMap now contains a map of cleaned keys, update $values with this map
      $values['_key'] = $keyMap;
      
      // check if there duplicates are there after all mutations are processed
      foreach (array_count_values($keyMap) as $value => $count)
      {
        if ($count > 1)
        {
          // find the keys which have a duplicate value
          $duplicateKeys = array_keys($keyMap, $value);
          foreach ($duplicateKeys as $key)
          {
            // if the key is not changed then don't add an error, otherwise add one
            if ($keyMap[$key] != $key)
            {
              $e = new sfValidatorError($this, 'key_map_duplicate', array(
                'key' => $value
              ));
              $mapErrors->addError($e, $key);
            }
          }
        }
      }

      // if there are no errors found in the keymap, then we can process the keymap
      if (count($mapErrors) == 0)
      {
        // this construction is done to make swapping of keys possible
        // sample keymap:
        // a->b
        // b->a
        $valuesCopy = $values;
        $newValues = array();
        foreach ($keyMap as $oldKey => $newKey)
        {
          $newValues[$newKey] = $valuesCopy[$oldKey];
          unset($values[$oldKey]);
        }
        $values = array_merge($values, $newValues);
        
        // remove the keymap from the values as it is not needed anymore
        unset($values['_key']);
      }
      else
      {
        $errorSchema->addError($mapErrors, '_key');
      }
    }
    
    // add new fields on the fly to check the values, key checking will be done just before
    // postValidation
    $this->addedFields = array();
    foreach ($values as $name => $value)
    {
      if ($name != '_key' && !isset($this->fields[$name]))
      {
        $this->addedFields[] = $name;
        $this->fields[$name] = $this->valueValidator;
      }
    }
    
    // temporarily remove the keymap from the values, so the parent validator can
    // do it's job and doesn't raise an 'unexpected form field' error
    $keyMap = null;
    if (isset($values['_key']))
    {
      $keyMap = $values['_key'];
      unset($values['_key']);
      // make sure all added fields exist in the keymap
      $flatMap = array_combine($this->addedFields, $this->addedFields);
      $keyMap = array_merge($flatMap, $keyMap);
    }
    
    // call the parent with changes to this schema
    try
    {
      $clean = parent::doClean($values);
    }
    catch (sfValidatorErrorSchema $e)
    {
      $errorSchema->addErrors($e);
    }
    catch (sfValidatorError $e)
    {
      $errorSchema->addError($e);
    }

    // reinsert the key map if it was there originally
    if (is_array($keyMap))
    {
      $clean['_key'] = $keyMap;
    }

    // remove the added fields of this schema
    foreach ($this->addedFields as $name)
    {
      unset($this->fields[$name]);
    }
    $this->addedFields = array();
    
    // throw the error again, or return the cleaned fields
    if (count($errorSchema))
    {
      throw $errorSchema;
    }
    return $clean;
  }

  /**
   * postClean first cleans the key names, then runs the postvalidator
   *
   * This method is the last validator executed by doClean().
   *
   * It executes the validator returned by getPostValidator()
   * on the global array of cleaned values.
   *
   * @param  array $values  The input values
   *
   * @throws sfValidatorError
   */
  public function postClean($values)
  {
    $errorSchema = new sfValidatorErrorSchema($this);
    
    // check all 
    foreach ($this->addedFields as $name)
    {
      try
      {
        $newName = $this->keyValidator->clean($name);
        if ($newName !== $name)
        {
          $values[$newName] = $values[$name];
          unset($values[$name]);
        }
      }
      catch (sfValidatorError $e)
      {
        $errorSchema->addError($e);
      }
    }
    
    // call parent method
    try
    {
      $values = parent::postClean($values);
    }
    catch (sfValidatorErrorSchema $e)
    {
      $errorSchema->addErrors($e);
    }
    catch (sfValidatorError $e)
    {
      $errorSchema->addError($e);
    }

    // throw an error, or return the cleaned fields
    if (count($errorSchema))
    {
      throw $errorSchema;
    }
    return $values;
  }
}
