<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PayPerViewReceived extends Notification implements ShouldQueue
{
    use Queueable;

    private $data;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($data)
    {
        $this->data = $data;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
      switch ($this->data['type']) {
        case 'post':
          $subject = '@'.$this->data['buyer'] . ' ' . trans('general.has_bought_your_content', [], 'en'). ' "'.str_limit($this->data['content'], 50, '...').'"';
          break;

        case 'message':
          $subject = '@'.$this->data['buyer'] . ' ' . trans('general.has_bought_your_message', [], 'en'). ' "'.str_limit($this->data['content'], 50, '...').'"';
          break;
      }

       return (new MailMessage)
                   ->subject($subject)
                   ->greeting(trans('emails.new_ppv_greeting', ['name' => $notifiable->name], 'en'))
                   ->line(trans('emails.new_ppv_intro', [], 'en'))
                   ->line(trans('emails.new_ppv_line_1', [], 'en'))
                   ->line(trans('emails.new_ppv_line_2', [], 'en'))
                   ->line(trans('emails.new_ppv_line_3', [], 'en'))
                   ->action(trans('general.go_payments_received', [], 'en'), url('my/payments/received'));
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
