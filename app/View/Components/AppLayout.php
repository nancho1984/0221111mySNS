<?php

namespace App\View\Components;

use Illuminate\View\Component;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Notification;

class AppLayout extends Component
{
    /**
     * Get the view / contents that represents the component.
     *
     * @return \Illuminate\View\View
     */
    public function render()
    {
        $user = Auth::user();
        
        if($user != NULL)
        {
            //ユーザーがログインしてるとき
            $search_notice = Notification::where('user_id', $user->id)
                ->where('read_at', NULL);
            //カウントかえす
            $number_notices = $search_notice->count();
            
        }else{
            //ログインしてないとき
            $number_notices = NULL;
        }
        //dd($notifications);
        
        return view('layouts.app',compact(
            'number_notices',
            ));
    }
}
