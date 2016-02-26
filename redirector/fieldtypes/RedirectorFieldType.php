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
		$id = $this->element->id;
		$locale = $this->element->locale;

		// If value is empty delete database record. if not update the record.
		if (str_replace("/","", $value) == "") {
			craft()->redirector->delete($id, $locale);
		} else {
			// if first character is not a slash then tack it on
			$value = '/' . ltrim($value, '/');
			craft()->redirector->createOrUpdate($id, $locale, $value);
		}
	}

	public function defineContentAttribute()
	{
		return false;
	}

}
