<?php

// Webhooks enable real-time notifications of conversation events to be
// delivered to endpoints on your own server. This example creates a webhook
// that is invoked when new conversations and messages are created in the
// specified channel.

require __DIR__ . '/vendor/autoload.php';

//$messageBird = new \MessageBird\Client('YOUR_ACCESS_KEY'); // Set your own API access key here.
$messageBird = new \MessageBird\Client('cQnSwbFuJBZ6sON0z2Ph83eVm', null, [\MessageBird\Client::ENABLE_CONVERSATIONSAPI_WHATSAPP_SANDBOX]);

try {
    $webhook = new \MessageBird\Objects\Conversation\Webhook();
    $webhook->channelId = '8eecc931db28425c9ae08e186e77aaad';
    $webhook->url = 'https://sistemas24.dyndns.info/pagotoday/notificaciones_monitor/whatsapp_inicio.php';
    $webhook->events = array(
        \MessageBird\Objects\Conversation\Webhook::EVENT_CONVERSATION_CREATED,
        //\MessageBird\Objects\Conversation\Webhook::EVENT_MESSAGE_CREATED,

        // Other options:
        // \MessageBird\Objects\Conversation\Webhook::EVENT_CONVERSATION_UPDATED,
        // \MessageBird\Objects\Conversation\Webhook::EVENT_MESSAGE_UPDATED,
    );

    $messageBird->conversationWebhooks->create($webhook);
} catch (\Exception $e) {
    echo sprintf("%s: %s", get_class($e), $e->getMessage());
}
