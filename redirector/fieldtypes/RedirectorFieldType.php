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

	/*
	 * since we don't use a column in the craft_content table to store data
	 * we use the "prepValueFromPost" to do the actual database changes
	 */
	public function prepValueFromPost($value)
	{
		$id = $this->element->id;
		$locale = $this->element->locale;

		// If value is empty delete the database record. if not update the record.
		if (str_replace("/","", $value) == "") {
			craft()->redirector->delete($id, $locale);
			// If the value was set to "/" we can end up here even though no record exists. 
			// Still no validation error should occur
			$isValid = true;
		} else {
			// if first character is not a slash then tack it on
			$value = '/' . ltrim($value, '/');
			$isValid = craft()->redirector->createOrUpdate($id, $locale, $value);
		}
		// Skip the validation function (by returning false) if db operation was successfull
		return $isValid ? false : $value;
	}

	public function defineContentAttribute()
	{
		return false;
	}

	/*
	* The validate function seem to only get called if "prepValueFromPost"
	* above returns a truthy value. So we let the validation happen there
	* based on the db constrains and use the "validate" function purely to
	*  display the error messages.
	*/
	public function validate($value)
	{
		return '"'.$value.'"' . Craft::t(' is already being used somewhere else.');
	}

}
