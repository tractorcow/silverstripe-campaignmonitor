<?php

namespace Tractorcow\CampaignMonitor;

if (is_dir(__DIR__ . '/../vendor')) {
    require_once(__DIR__ . '/../vendor/campaignmonitor/createsend-php/class/serialisation.php');
} else {
    require_once(__DIR__ . '/../../../campaignmonitor/createsend-php/class/serialisation.php');
}

class JsonAssocDeserialiser extends \CS_REST_NativeJsonSerialiser
{
    public function deserialise($text)
    {
        return $this->strip_surrounding_quotes(json_decode($text, true) ?? $text);
    }
}
