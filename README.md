# Campaign monitor wrapper module for Silverstripe

Simple implementation of the campaign monitor API within Silverstripe

## Credits and Authors

 * Damian Mooyman - <https://github.com/tractorcow/silverstripe-campaignmonitor/>

## License

 * TODO

## Requirements

 * SilverStripe ^4
 * PHP ^7.1
 * Campaign Monitor PHP library 6.0.0

## Installation instructions

```bash
composer require tractorcow/silverstripe-campaignmonitor
```

## Examples

### Using the API to set a destination list (SiteConfig extension)

Given a hard coded API key, allow the user to select a client from their account,
and subsequently a list.

```php

	function updateCMSFields(FieldList $fields) {

		// Load base object
		$resources = CMResources::create("my api key");

		// Get clients under our account
		$clients = $resources->Clients()->map();
		$fields->addFieldToTab(
			'Root.CampaignMonitor',
			DropdownField::create('Client', 'Client', $clients)
		);

		// check if client is available to select
		if($this->owner->Client && ($client = $resources->getClient($this->owner->Client))) {
			$lists = $client->Lists()->map();
			$fields->addFieldToTab(
				'Root.CampaignMonitor',
				DropdownField::create('DefaultList', 'Default List', $lists)
			);
		}
	}

```


### Saving a subscriber

Handling subscription details from a form submission

```php

	public function subscribe($data, $form) 
	{
		$listID = SiteConfig::current_site_config()->DefaultList;
		$resources = CMResources::create("my api key");
		if($resources && $listID && $list = $resources->getList($listID)) {
			$this->addUserToList($data, $list);
			Director::redirect($this->Link('thanks'));
		}
		// Error handling here
	}

	protected function addUserToList($data, $list) 
	{
		if(empty($list)) return;
		
		// Create subscriber
		$fields = [
			'EmailAddress' => $data['Email'],
			'Name' => $data['FirstName'],
			'CustomFields' => [
				'LastName' => $data['LastName'],
				'Company' => $data['Company'],
				'Phone' => $data['Phone'],
				'Mobile' => $data['Mobile']
			],
			'Resubscribe' => true,
			'RestartSubscriptionBasedAutoresponders' => true
		];
		$subscriber = CMSubscriber::create(null, $fields, $list);
		$subscriber->Save();
	}

```
