<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewSale extends Notification implements ShouldQueue
{
    use Queueable;

    public $sale;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($sale)
    {
        $this->sale = $sale;
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
      $subject = '@'.$this->sale->user()->username.' '.trans('general.has_bought_your_item', [], 'en'). ' - '.$this->sale->products()->name;

        return (new MailMessage)
              ->subject($subject)
              ->greeting(trans('emails.hello', [], 'en') .' '.$notifiable->name)
              ->line($subject)
              ->action(trans('general.my_sales', [], 'en'), url('my/sales'));
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
