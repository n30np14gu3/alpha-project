<?php

namespace App\Http\Controllers;

use App\Http\Helpers\ReCaptcha;
use App\Http\Helpers\TicketHelper;
use App\Http\Requests;

use App\Http\Helpers\UserHelper;
use App\Models\Ticket;
use App\Models\TicketMessage;
use App\Models\UserSettings;
use Illuminate\Http\Request;

use App\Models\User;
use Illuminate\Support\Facades\DB;


class supportController extends Controller
{
    public function allTickets(Request $request){
        $user = UserHelper::CheckAuth($request, true);
        if(!@$user->id)
            return redirect()->route('show_login');

        $tickets = [];
        $tickets_db =Ticket::where('user_id', $user->id)->get();
        foreach($tickets_db as $ticket){
            $ticket_module = [
                'id' => $ticket->id,
                'title' => $ticket->title,
                'last_message' => date("d-m-Y H:i", @TicketMessage::where('ticket_id', $ticket->id)->get()->last()->time),
            ];
            array_push($tickets, $ticket_module);
        }
        $data = [
            'logged' => true,
            'tickets' => $tickets
        ];
        return view('pages.support', $data);
    }

    public function createTicket(Request $request){
        $result = [
            'status' => 'ERROR',
            'message' => 'UNKNOWN ERROR!',
        ];

        $ticketTitle = @$_POST['ticket']['title'];
        $ticketMessage = @$_POST['ticket']['message'];
        if(!$ticketMessage || !$ticketTitle) {
            $result['message'] = 'Одно или несколько полей пустые!';
            return json_encode($result);
        }

        if(!env('BETA_DISABLERECAPTCHA') && !ReCaptcha::Verify())
        {
            $result['message'] = "Ошибка ReCaptcha!";
            return json_encode($result);
        }

        $userData = UserHelper::GetLocalUserInfo($request);
        $newTicketId = TicketHelper::CreateTicket($userData['id'], $ticketTitle);
        TicketHelper::SendMessageToTicket($newTicketId, $ticketMessage, false);

        $result['status'] = 'OK';
        return json_encode($result);
    }

    public function showTicket(Request $request, $ticket_id){
        $data = [
            'messages' => null,
            'logged' => true
        ];

        $ticket_messages = TicketMessage::where('ticket_id', $ticket_id)->orderBy('time')->get();
        if(count($ticket_messages) == 0)
            return redirect()->route('support');


        $ticket = Ticket::where('id', $ticket_id)->get()->first();
        $user = UserHelper::GetLocalUserInfo($request);

        if(@$ticket->user_id != $user['id']){
            return redirect()->route('support');
        }

        $messages = [
            'ticket_id' => $ticket->id,
            'staff_nickname' => @UserSettings::where('user_id', @$ticket->staff_id)->get()->first()->nickname,
            'ticket_title' => @$ticket->title,
            'completed' => @$ticket->completed,
            'data' => []
        ];
        foreach($ticket_messages as $message){
            $message_module = [
              'message' => str_replace("\r\n",'<br>', htmlspecialchars($message->message)),
              'is_answer' => $message->is_answer,
              'time' => date("d-m-Y H:i:s", $message->time)
            ];

            array_push($messages['data'], $message_module);
        }

        $data['messages'] = $messages;
        return view('pages.ticket', $data);
    }

    public function appendTicket(Request $request){
        $result = [
            'status' => 'ERROR',
            'message' => 'UNKNOWN ERROR!',
        ];

        $ticketId = @$_POST['ticket']['id'];
        $ticketMessage = @$_POST['ticket']['message'];

        if(!$ticketMessage || !$ticketId) {
            $result['message'] = 'Одно или несколько полей пустые!';
            return json_encode($result);
        }

        if(!env('BETA_DISABLERECAPTCHA') && !ReCaptcha::Verify())
        {
            $result['message'] = "Ошибка ReCaptcha!";
            return json_encode($result);
        }

        $user = UserHelper::GetLocalUserInfo($request);

        $ticket = Ticket::where('id', $ticketId)->get()->first();
        if(@!$ticket){
            $result['message'] = "Тикет с данным ID не найден!";
            return json_encode($result);
        }

        if(@$ticket->user_id != $user['id']){
            $result['message'] = "Тикет принадлежит другому пользователю";
            return json_encode($result);
        }

        if(@$ticket->completed){
            $result['message'] = "Тикет закрыт!";
            return json_encode($result);
        }

        $last_message = TicketMessage::where('ticket_id', $ticketId)->orderBy('time')->get()->last();
        if(!@$last_message->is_answer){
            $result['message'] = "Дождитесь ответа службы поддержки!";
            return json_encode($result);
        }
        
        TicketHelper::SendMessageToTicket($ticketId, $ticketMessage, false);
        $result['status'] = 'OK';
        return json_encode($result);
    }

    public function closeTicket(Request $request){
        $result = [
            'status' => 'ERROR',
            'message' => 'UNKNOWN ERROR!',
        ];

        $ticketId = @$_POST['ticket_id'];
        if(!$ticketId) {
            $result['message'] = 'Не указан id';
            return json_encode($result);
        }

        $user = UserHelper::GetLocalUserInfo($request);

        $ticket = Ticket::where('id', $ticketId)->get()->first();
        if(@!$ticket){
            $result['message'] = "Тикет с данным ID не найден!";
            return json_encode($result);
        }

        if(@$ticket->user_id != $user['id']){
            $result['message'] = "Тикет принадлежит другому пользователю";
            return json_encode($result);
        }

        if(@$ticket->completed){
            $result['message'] = "Тикет уже закрыт!";
            return json_encode($result);
        }

        $ticket->completed = 1;
        $ticket->save();
        $result['status'] = 'OK';
        return json_encode($result);
    }
}
