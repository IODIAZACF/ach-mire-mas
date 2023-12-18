#!/usr/bin/php

<?php
include __DIR__ . '/config.php';
require_once(__DIR__ . '/vendor/autoload.php');

use pimax\FbBotApp;
use pimax\Menu\MenuItem;
use pimax\Menu\LocalizedMenu;
use pimax\Messages\Message;
use pimax\Messages\MessageButton;
use pimax\Messages\StructuredMessage;
use pimax\Messages\MessageElement;
use pimax\Messages\MessageReceiptElement;
use pimax\Messages\Address;
use pimax\Messages\Summary;
use pimax\Messages\Adjustment;
use pimax\Messages\AccountLink;
use pimax\Messages\ImageMessage;
use pimax\Messages\QuickReply;
use pimax\Messages\QuickReplyButton;
use pimax\Messages\SenderAction;
use pimax\UserProfile;



$bot = new FbBotApp(FB_TOKEN);
$user = $bot->userProfile('999489783486827');
echo utf8_decode($user->getFirstName()) . "\n";
echo utf8_decode($user->getLastName()) . "\n";

//echo $result['first_name'] . "\n";
print_r($user);

?> 