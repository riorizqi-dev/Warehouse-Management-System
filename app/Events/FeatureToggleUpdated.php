<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class FeatureToggleUpdated implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $feature;

    public $action;

    public function __construct($feature, $action = 'updated')
    {
        $this->feature = $feature;
        $this->action = $action;
    }

    public function broadcastOn(): array
    {
        return [
            new Channel('feature-toggles'),
        ];
    }

    public function broadcastAs(): string
    {
        return 'feature.updated';
    }

    public function broadcastWith(): array
    {
        return [
            'action' => $this->action,
            'feature' => [
                'id' => $this->feature->id ?? null,
                'key' => $this->feature->key ?? null,
                'name' => $this->feature->name ?? null,
                'route_name' => $this->feature->route_name ?? null,
                'icon' => $this->feature->icon ?? null,
                'enabled' => $this->feature->enabled ?? false,
            ],
        ];
    }
}
