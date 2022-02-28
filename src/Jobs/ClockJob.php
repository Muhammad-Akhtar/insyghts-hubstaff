<?php

namespace Insyghts\Hubstaff\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Insyghts\Hubstaff\Models\ServerTimestamp;
use Insyghts\Hubstaff\Services\HubstaffServerService;
use JohnDoe\BlogPackage\Models\ServerTim;

class ClockJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct()
    {

    }

    public function handle()
    {
        $this->timestamp = new ServerTimestamp();
        $this->serverService = new HubstaffServerService($this->timestamp);
        $response = $this->serverService->getTimestamp();
        $timeString = (int)$response['data'];
        $currentTime = gmdate('Y-m-d G:i:s', $timeString);
        $newTime = strtotime($currentTime . ' + 1 minute');
        $this->timestamp->updateClock($newTime);
    }
}