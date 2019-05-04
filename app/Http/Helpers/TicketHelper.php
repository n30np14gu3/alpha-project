<?php
namespace App\Http\Helpers;


use App\Models\Ticket;
use App\Models\TicketMessage;

class TicketHelper
{
    /**
     * @param int $user_id
     * @param string $title
     * @return int
     */
    public static function CreateTicket($user_id, $title){
        $ticket = new Ticket();
        $ticket->user_id = $user_id;
        $ticket->title = $title;
        $ticket->staff_id = null;
        $ticket->creation_time = time();
        $ticket->save();

        return $ticket->id;
    }

    /**
     * @param $ticket_id
     * @param $message
     * @param $is_answer
     * @return int
     */
    public static function SendMessageToTicket($ticket_id, $message, $is_answer){
        $ticketMessage = new TicketMessage();
        $ticketMessage->ticket_id = $ticket_id;
        $ticketMessage->message = $message;
        $ticketMessage->is_answer = $is_answer;
        $ticketMessage->time = time();
        $ticketMessage->save();

        return $ticketMessage->id;
    }
}