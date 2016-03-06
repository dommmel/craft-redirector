<?php

namespace Craft;

class RedirectorPlugin extends BasePlugin
{
	public function getName()
	{
		return Craft::t('Redirector');
	}

	public function getVersion()
	{
		return '0.0.4';
	}

	public function getSchemaVersion()
	{
		return '0.0.1';
	}

	public function getDeveloper()
	{
		return 'Flexify.net';
	}

	public function getDeveloperUrl()
	{
		return 'https://www.flexify.net';
	}

	public function getDescription()
	{
	    return 'Manage redirections right from your entries. Access your legacy URLs in your templates (e.g. to keep existing facebook likes).';
	}

	public function getReleaseFeedUrl()
	{
		return 'https://raw.githubusercontent.com/dommmel/craft-redirector/master/releases.json';
	}

	public function getDocumentationUrl()
	{
	    return 'https://github.com/dommmel/craft-redirector';
	}

	public function getSettingsHtml()
	{
	   return craft()->templates->render('redirector/settings', array(
		   'redirects' => craft()->redirector->findAll()
	   ));
	}

	public function init()
	{
		// continue if it's a 404
		craft()->onException = function(\CExceptionEvent $event)
		{
			if(!empty($event->exception->statusCode) && ($event->exception->statusCode == 404))
			{
				// continue if not on control panel
				if(craft()->request->isSiteRequest() && !craft()->request->isLivePreview())
				{
					$url = craft()->request->getUrl();

					// Look up redirection instructions in the plugin's database table
					$redirect = craft()->redirector->findByUrl($url);

					if($redirect)
					{
						// Look up entry to redirect to
						$entry = craft()->entries->getEntryById($redirect['targetEntryId'], $redirect['locale']);

						// only redirect to entry if it is live
						if ($entry->status == 'live') 
						{
							// redirect (permanently)
							craft()->request->redirect($entry->getUrl(), true, 301);
							$event->handled = true;
						}
					}
				}
			}
		};
	}
}
