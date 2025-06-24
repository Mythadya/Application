<?php
// src/MessageHandler/SyncHolidaysMessageHandler.php
namespace App\MessageHandler;

use App\Message\SyncHolidaysMessage;
use App\Service\JourFerieApiService;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

class SyncHolidaysMessageHandler implements MessageHandlerInterface
{
    public function __construct(
        private JourFerieApiService $apiService
    ) {}

    public function __invoke(SyncHolidaysMessage $message)
    {
        $this->apiService->syncHolidaysForZone(
            $message->getZone(),
            $message->getYear(),
            // $message->getForce() // Add this to your message class if needed
        );
    }
}