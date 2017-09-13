# Campaign monitor wrapper module for Silverstripe

Simple implementation of the campaign monitor API within Silverstripe

## Credits and Authors

 * Damian Mooyman - <https://github.com/tractorcow/silverstripe-campaignmonitor/>

## License

 * TODO

## Requirements

 * SilverStripe 4.0
 * PHP 5.3
 * Campaign Monitor PHP library 2.5.2

## Installation instructions

composer require "tractorcow/silverstripe-campaignmonitor": "3.0.*@dev"

composer require "campaignmonitor/createsend-php": "v2.5.2"


## Examples

### Using the API to set a destination list (SiteConfig extension)

Given a hard coded API key, allow the user to select a client from their account,
and subsequently a list.

```php

	function updateCMSFields(FieldList $fields) {

		// Load base object
		$resources = new CMResources("my api key");

		// Get clients under our account
		$clients = $resources->Clients()->map();
		$fields->addFieldToTab(
			'Root.CampaignMonitor',
			new DropdownField('Client', 'Client', $clients)
		);

		// check if client is available to select
		if($this->owner->Client && ($client = $resources->getClient($this->owner->Client))) {
			$lists = $client->Lists()->map();
			$fields->addFieldToTab(
				'Root.CampaignMonitor',
				new DropdownField('DefaultList', 'Default List', $lists)
			);
		}
	}

```


### Saving a subscriber

Handling subscription details from a form submission

```php

	public function subscribe($data, $form) {
		$listID = SiteConfig::current_site_config()->DefaultList;
		$resources = new CMResources("my api key");
		if($resources && $listID && $list = $resources->getList($listID)) {
			$this->addUserToList($data, $list);
			Director::redirect($this->Link('thanks'));
		}
		// Error handling here
	}

	protected function addUserToList($data, $list) {
		if(empty($list)) return;
		
		// Create subscriber
		$fields = array(
			'EmailAddress' => $data['Email'],
			'Name' => $data['FirstName'],
			'CustomFields' => array(
				'LastName' => $data['LastName'],
				'Company' => $data['Company'],
				'Phone' => $data['Phone'],
				'Mobile' => $data['Mobile']
			),
			'Resubscribe' => true,
			'RestartSubscriptionBasedAutoresponders' => true
		);
		$subscriber = new CMSubscriber(null, $fields, $list);
		$subscriber->Save();
	}

```


### Get a list of sent Campaigns

Get a list of all sent campaigns for a client including from name, from email, 
reply to email, web version URL, ID, subject, name, date sent, and the total number of recipients.

See the [Campaign Monitor API documentation] (https://www.campaignmonitor.com/api/clients/#sent_campaigns) 
for more information.


```php
	public function Campaigns() {
		$resources = new CMResources("my api key");
		if($resources && $client = $resources->getClient("my client id")) {
			return $client->Campaigns();
		}
	}

```
