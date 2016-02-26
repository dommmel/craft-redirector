<?php
namespace Craft;

class RedirectorFieldType extends BaseFieldType
{
    public function getName()
    {
        return Craft::t('Legacy URL');
    }

    public function prepValue($value)
    {
      $redirect = craft()->redirector->findByEntryId($this->element->id, $this->element->locale);
      return $redirect['urlPattern'];
    }

    public function getInputHtml($name, $value)
    {   
      return craft()->templates->render('redirector/input', array(
          'name'  => $name,
          'value' => $value
      ));
    }
    public function prepValueFromPost($value)
    {
      // if first character is not a slash then tack it on
      $value = '/' . ltrim($value, '/');

      craft()->redirector->createOrUpdate($this->element->id, $this->element->locale, $value);
    }

    public function defineContentAttribute()
    {
        return false;
    }


}