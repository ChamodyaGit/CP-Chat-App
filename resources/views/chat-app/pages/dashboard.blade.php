@extends('chat-app.layouts.app')

@section('content')
    <div class="max-w-4xl mx-auto px-4">
        <h2 class="text-2xl font-semibold mb-6 text-gray-800">Messages</h2>
        <div class="bg-white rounded-xl shadow-md overflow-hidden">
            <div class="p-4 border-b bg-gray-50">
                <input type="text" placeholder="Search users..."
                    class="w-full px-4 py-2 rounded-lg border focus:ring-2 focus:ring-indigo-400 outline-none">
            </div>
            <div class="divide-y">
                @foreach ($users as $user)
                    <a href="{{ route('chat.show', $user->id) }}" id="user-{{ $user->id }}"
                        class="flex items-center p-4 hover:bg-indigo-50 transition {{ $user->unread_count > 0 ? 'bg-indigo-50/50' : '' }}">

                        <div class="relative">
                            <div
                                class="w-12 h-12 bg-indigo-100 text-indigo-600 rounded-full flex items-center justify-center font-bold text-lg">
                                {{ substr($user->name, 0, 1) }}
                            </div>
                            <!-- Unread Badge -->
                            <div id="badge-{{ $user->id }}"
                                class="absolute -top-1 -right-1 bg-red-500 text-white text-[10px] w-5 h-5 rounded-full flex items-center justify-center border-2 border-white {{ $user->unread_count > 0 ? '' : 'hidden' }}">
                                {{ $user->unread_count }}
                            </div>
                        </div>

                        <div class="ml-4 flex-1">
                            <div class="flex justify-between">
                                <h4
                                    class="font-medium {{ $user->unread_count > 0 ? 'text-indigo-900 font-bold' : 'text-gray-900' }}">
                                    {{ $user->name }}
                                </h4>
                                <span class="text-xs text-gray-400">Active</span>
                            </div>
                            <p id="last-msg-{{ $user->id }}"
                                class="text-sm {{ $user->unread_count > 0 ? 'text-indigo-600 font-medium' : 'text-gray-500' }} truncate">
                                {{ $user->unread_count > 0 ? 'New message received' : 'Click to start conversation' }}
                            </p>
                        </div>
                        <i class="fa-solid fa-chevron-right text-gray-300 ml-4"></i>
                    </a>
                @endforeach
            </div>
        </div>
    </div>

    <script type="module">
        window.Echo.private(`chat.${ {{ Auth::id() }} }`)
            .listen('MessageSent', (e) => {
                console.log("New message for dashboard update:", e);

                const userId = e.message.sender_id;
                const badge = document.getElementById(`badge-${userId}`);
                const lastMsg = document.getElementById(`last-msg-${userId}`);

                if (badge) {
                    // 1. Badge එක පෙන්වන්න
                    badge.classList.remove('hidden');

                    // 2. දැනට තියෙන count එකට 1ක් එකතු කරන්න
                    let currentCount = parseInt(badge.innerText) || 0;
                    badge.innerText = currentCount + 1;

                    // 3. UI එක Highlight කරන්න
                    lastMsg.innerText = "New message received";
                    lastMsg.classList.add('text-indigo-600', 'font-medium');
                }
            });
    </script>
@endsection
