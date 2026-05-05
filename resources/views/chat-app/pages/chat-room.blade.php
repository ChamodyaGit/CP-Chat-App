@extends('chat-app.layouts.app')

@section('content')
    <!-- UI එකේ වෙනසක් නැත -->
    <div class="max-w-4xl mx-auto px-4">
        <div class="bg-white rounded-xl shadow-lg flex flex-col h-[80vh]">
            <!-- Header -->
            <div class="p-4 border-b flex items-center bg-white rounded-t-xl">
                <a href="{{ route('dashboard') }}" class="mr-4 text-gray-500 hover:text-indigo-600">
                    <i class="fa-solid fa-arrow-left"></i>
                </a>
                <div class="w-10 h-10 bg-indigo-600 text-white rounded-full flex items-center justify-center font-bold">
                    {{ substr($receiver->name, 0, 1) }}
                </div>
                <div class="ml-3 text-sm">
                    <div class="font-bold text-gray-800">{{ $receiver->name }}</div>
                    <div id="status-indicator" class="text-green-500 flex items-center gap-1">
                        <span class="w-2 h-2 bg-green-500 rounded-full animate-pulse"></span> Online
                    </div>
                </div>
            </div>

            <!-- Messages Area -->
            <div class="flex-1 overflow-y-auto p-6 bg-[#f0f2f5] space-y-4" id="chat-window">
                @foreach ($messages as $msg)
                    <div class="flex {{ $msg->sender_id == Auth::id() ? 'justify-end' : 'justify-start' }}">
                        <div
                            class="max-w-[70%] rounded-2xl px-4 py-2 shadow-sm
                        {{ $msg->sender_id == Auth::id() ? 'bg-indigo-600 text-white rounded-tr-none' : 'bg-white text-gray-800 rounded-tl-none' }}">
                            <p class="text-sm">{{ $msg->message }}</p>
                            <div
                                class="flex items-center justify-end gap-1 mt-1 text-[10px] {{ $msg->sender_id == Auth::id() ? 'text-indigo-200' : 'text-gray-400' }}">
                                {{ $msg->created_at->format('g:i A') }}
                                @if ($msg->sender_id == Auth::id())
                                    <i
                                        class="fa-solid {{ $msg->is_read ? 'fa-check-double text-sky-300' : 'fa-check' }}"></i>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Input Area -->
            <div class="p-4 bg-white border-t rounded-b-xl">
                <form id="chat-form" class="flex gap-3">
                    @csrf
                    <input type="text" id="message-input" name="message" autocomplete="off"
                        placeholder="Write a message..."
                        class="flex-1 bg-gray-100 border-none rounded-full px-6 py-3 focus:ring-2 focus:ring-indigo-400 outline-none text-sm">
                    <button type="submit" id="send-btn"
                        class="bg-indigo-600 hover:bg-indigo-700 text-white w-12 h-12 rounded-full flex items-center justify-center transition shadow-md">
                        <i class="fa-solid fa-paper-plane"></i>
                    </button>
                </form>
            </div>
        </div>
    </div>

    <script type="module">
        // 1. UI Elements තෝරා ගැනීම
        const chatWindow = document.getElementById("chat-window");
        const chatForm = document.getElementById("chat-form");
        const messageInput = document.getElementById("message-input");
        const statusIndicator = document.getElementById("status-indicator");

        // Auth හා Receiver දත්ත (Blade හරහා ලබා ගනී)
        const currentUserId = {{ Auth::id() }};
        const receiverId = {{ $receiver->id }};
        const receiverName = "{{ $receiver->name }}";

        // Scroll to bottom function
        const scrollToBottom = () => {
            chatWindow.scrollTop = chatWindow.scrollHeight;
        };
        scrollToBottom();

        // පණිවිඩ කියවූ බව (Seen) අනෙක් පාර්ශවයට දැනුම් දෙන function එක
        const sendSeenSignal = () => {
            if (typeof window.Echo !== 'undefined') {
                window.Echo.private(`chat.${receiverId}`)
                    .whisper('seen', {
                        user_id: currentUserId
                    });
            }
        };

        // ---------------------------------------------------------
        // 2. Real-time Listeners (Laravel Echo)
        // ---------------------------------------------------------
        if (typeof window.Echo !== 'undefined') {

            // Chat එකට ඇතුළු වූ සැණින් සියලුම පණිවිඩ කියවූ බවට signal එකක් යවන්න
            setTimeout(sendSeenSignal, 1000);

            window.Echo.private(`chat.${currentUserId}`)
                // මට ලැබෙන පණිවිඩ සඳහා Listen කිරීම
                .listen('MessageSent', (e) => {
                    console.log("New message received:", e);
                    appendMessage(e.message, 'received');
                    sendSeenSignal(); // පණිවිඩය ලැබුණු සැණින් Seen signal එක යවන්න
                })
                // අනෙක් පාර්ශවය මගේ පණිවිඩ බැලූ බව දැනුම් දෙන විට මගේ Ticks update කිරීම
                .listenForWhisper('seen', (e) => {
                    console.log("Recipient seen my messages");
                    const unreadIcons = document.querySelectorAll('.fa-check');
                    unreadIcons.forEach(icon => {
                        icon.classList.remove('fa-check');
                        icon.classList.add('fa-check-double', 'text-sky-300');
                    });
                })
                // අනිත් කෙනා Type කරන විට ලැබෙන 'Whisper' එක අල්ලා ගැනීම
                .listenForWhisper('typing', (e) => {
                    statusIndicator.innerHTML = `
                    <span class="w-2 h-2 bg-green-500 rounded-full animate-ping"></span>
                    <span class="italic text-green-600">${receiverName} is typing...</span>
                `;

                    setTimeout(() => {
                        statusIndicator.innerHTML = `
                        <span class="w-2 h-2 bg-green-500 rounded-full"></span> Online
                    `;
                    }, 3000);
                });
        }

        // ---------------------------------------------------------
        // 3. Typing Indicator යැවීම (Whisper)
        // ---------------------------------------------------------
        messageInput.addEventListener('input', () => {
            window.Echo.private(`chat.${receiverId}`)
                .whisper('typing', {
                    typing: true
                });
        });

        // ---------------------------------------------------------
        // 4. AJAX මඟින් පණිවිඩ යැවීම (Send Message)
        // ---------------------------------------------------------
        chatForm.addEventListener('submit', async (e) => {
            e.preventDefault();

            const message = messageInput.value.trim();
            if (!message) return;

            messageInput.value = '';

            try {
                const response = await fetch("{{ route('chat.send', $receiver->id) }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        message: message
                    })
                });

                if (!response.ok) throw new Error('Network response was not ok');

                const data = await response.json();
                console.log("Message sent successfully:", data);

                appendMessage(data.message, 'sent');

            } catch (error) {
                console.error('AJAX Error:', error);
                alert('Something went wrong. Please try again.');
            }
        });

        // ---------------------------------------------------------
        // 5. UI එකට Message එක එකතු කරන Function එක
        // ---------------------------------------------------------
        function appendMessage(msg, type) {
            const isSent = type === 'sent';
            const msgHtml = `
            <div class="flex ${isSent ? 'justify-end' : 'justify-start'} animate-fade-in">
                <div class="max-w-[70%] rounded-2xl px-4 py-2 shadow-sm
                    ${isSent ? 'bg-indigo-600 text-white rounded-tr-none' : 'bg-white text-gray-800 rounded-tl-none'}">

                    <p class="text-sm">${msg.message}</p>

                    <div class="flex items-center justify-end gap-1 mt-1 text-[10px]
                        ${isSent ? 'text-indigo-200' : 'text-gray-400'}">
                        Just now
                        ${isSent ? `
                                    <i class="fa-solid ${msg.is_read ? 'fa-check-double text-sky-300' : 'fa-check'}"></i>
                                ` : ''}
                    </div>
                </div>
            </div>
        `;

            chatWindow.insertAdjacentHTML('beforeend', msgHtml);
            scrollToBottom();
        }
    </script>
@endsection
