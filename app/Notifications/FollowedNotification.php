<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class FollowedNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
     
    //コントローラーから飛んできたデータを入れる
    public function __construct()
    {
        //コントローラーから飛んでくる値たちの言い換え作業？
        $this->following_user = $following_user;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['database'];
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
     
    //これはdataカラムに入れるデータを入れる。
    public function toDatabase($notifiable)
    {
        $following_user_id = $follow->following_user_id;
        $following_user_name = User::where('id', $following_user_id)->name->get();
        
        return [
            //通知内容として渡すデータ
            'following_user_id' => $following_user_id,
            'following_user_name' => $following_user_name,
        ];
    }
}
