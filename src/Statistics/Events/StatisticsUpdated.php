<?php

namespace BeyondCode\LaravelWebsockets\Statistics\Events;

use BeyondCode\LaravelWebSockets\Dashboard\DashboardLogger;
use BeyondCode\LaravelWebSockets\Statistics\Models\WebSocketsStatisticsEntry;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Queue\SerializesModels;

class StatisticsUpdated implements ShouldBroadcast
{
    use SerializesModels;

    /** @var \BeyondCode\LaravelWebSockets\Statistics\Models\WebSocketsStatisticsEntry */
    protected $webSocketsStatisticsEntry;

    public function __construct(WebSocketsStatisticsEntry $webSocketsStatisticsEntry)
    {
        $this->webSocketsStatisticsEntry = $webSocketsStatisticsEntry;
    }

    public function broadcastWith()
    {
        return [
            'time' => (string)$this->webSocketsStatisticsEntry->created_at,
            'app_id' => $this->webSocketsStatisticsEntry->app_id,
            'peak_connection_count' => $this->webSocketsStatisticsEntry->peak_connection_count,
            'websocket_message_count' => $this->webSocketsStatisticsEntry->websocket_message_count,
            'api_message_count' => $this->webSocketsStatisticsEntry->api_message_count,
        ];
    }

    public function broadcastOn()
    {
        $channelName = str_after(DashboardLogger::LOG_CHANNEL_PREFIX . 'statistics', 'private-');

        return new PrivateChannel($channelName);
    }

    public function broadcastAs()
    {
        return 'statistics-updated';
    }
}