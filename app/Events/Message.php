<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;


class Message implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;
    public $sender;
    public $reciever;
    public $message;
    public $recieved;
    public $created_at;
   
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($sender,$reciever,$message,$recieved,$created_at)
    {
         $this->sender = $sender ;
         $this->reciever = $reciever ;
         $this->message = $message ;
         $this->recieved = $recieved;
         $this->created_at = $created_at;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new Channel('chat');
    }
    public function broadcastAs(){
        return "message";
    }
}
