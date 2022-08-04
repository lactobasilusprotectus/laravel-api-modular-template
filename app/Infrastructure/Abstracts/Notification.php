<?php

namespace App\Infrastructure\Abstracts;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Notification as NotificationLaravel;
use JetBrains\PhpStorm\ArrayShape;

abstract class Notification extends NotificationLaravel implements ShouldQueue
{
    use Queueable;

    protected string $title;

    protected string $message;

    protected string $url;

    protected string $color;

    public function __construct(public array $data)
    {
        $this->onQueue('notifications');
    }

    public function via(): array
    {
        return [
          'mail',
          'broadcast',
          'database'
        ];
    }

    public function toBroadcast($notifiable)
    {
        return new BroadcastMessage($this->toArray($notifiable));
    }

    #[ArrayShape(['title' => "string", 'message' => "string", 'url' => "string", 'color' => "string"])]
    public function toArray($notifiable): array
    {
        return [
            'title' => $this->title,
            'message' => $this->message,
            'url' => $this->url,
            'color' => $this->color,
        ];
    }
}
