<div class="py-4">
    <div>
        <img class="rounded w-36 h-36" src="{{ $user->image }}" alt="Extra large avatar">
        
        <span class="flex flex-wrap">
            <h2 class="text-4xl">{{ $user->nickname }}</h2>
            <h4>＠{{ $user->addressname }}</h4>
        </span>
        
        <span>
            <p>フォロー<a href="{{ route('show_followusers', $user) }}">{{ count($user->following_users()) }}</a></p>
            <p>フォロワー<a href="{{ route('show_followers', $user) }}">{{ count($user->followers()) }}</a></p>
        </span>
        
        <div class="py-2">
            <x-button-follow :user="$user"/>
        </div>
        
        <p>{{ $user->profile_sentence }}</p>
    </div>
    
    <div class="py-4">
        <!-- 見ているユーザーが本人で"ある"ことを確認 -->
        @if(!$user->they_isnt_auth_user())
                <a href="{{ route('edit_profile', $user->id) }}" class="border-2 border-gray-700 bg-white hover:border-gray-500 rounded px-4 py-1.5">
                    プロフィール編集
                </a>
        @endif
    </div>
</div>