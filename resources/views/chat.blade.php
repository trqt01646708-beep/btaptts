<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Premium Realtime Chat</title>
    
    <!-- Modern Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Outfit', 'sans-serif'],
                    },
                    colors: {
                        brand: {
                            50: '#f0f7ff',
                            100: '#e0effe',
                            200: '#bae0fd',
                            300: '#7cc7fb',
                            400: '#38a9f8',
                            500: '#0e8ce8',
                            600: '#026fc5',
                            700: '#0358a0',
                            800: '#074c84',
                            900: '#0b406e',
                        },
                    },
                    animation: {
                        'fade-in-up': 'fadeInUp 0.3s ease-out forwards',
                        'slide-in-right': 'slideInRight 0.3s ease-out forwards',
                    },
                    keyframes: {
                        fadeInUp: {
                            '0%': { opacity: '0', transform: 'translateY(10px)' },
                            '100%': { opacity: '1', transform: 'translateY(0)' },
                        },
                        slideInRight: {
                            '0%': { opacity: '0', transform: 'translateX(20px)' },
                            '100%': { opacity: '1', transform: 'translateX(0)' },
                        }
                    }
                }
            }
        }
    </script>
    
    <style>
        body {
            background: radial-gradient(circle at top right, #f8fafc, #e2e8f0);
            font-family: 'Outfit', sans-serif;
        }
        .glass {
            background: rgba(255, 255, 255, 0.7);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.3);
        }
        .message-bubble {
            transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .message-bubble:hover {
            transform: scale(1.01);
        }
        /* Custom Scrollbar */
        ::-webkit-scrollbar { width: 6px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
        ::-webkit-scrollbar-thumb:hover { background: #94a3b8; }
        
        .user-item.active {
            background: linear-gradient(to right, #eff6ff, #dbeafe);
            border-left: 4px solid #3b82f6;
        }
    </style>

    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script>
        axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
        document.addEventListener('DOMContentLoaded', () => {
            let token = document.head.querySelector('meta[name="csrf-token"]');
            if (token) {
                axios.defaults.headers.common['X-CSRF-TOKEN'] = token.content;
            }
        });
    </script>
    <script src="https://js.pusher.com/8.0.1/pusher.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/laravel-echo@1.15.3/dist/echo.iife.js"></script>
    <script>
        window.Pusher = Pusher;
    </script>
</head>

<body class="h-screen flex flex-col p-4 md:p-8 space-y-4">
    <!-- Header -->
    <header class="glass rounded-3xl p-4 px-8 shadow-sm flex justify-between items-center animate-fade-in-up">
        <div class="flex items-center space-x-4">
            <div class="bg-brand-600 p-2 rounded-xl shadow-brand-200 shadow-xl">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                </svg>
            </div>
            <div>
                <h1 class="text-xl font-bold text-slate-800">SwiftReach <span class="text-xs font-normal text-slate-400">v2.0</span></h1>
                <div class="flex items-center space-x-2">
                    <div id="connection-dot" class="w-2 h-2 rounded-full bg-amber-400 animate-pulse"></div>
                    <span id="connection-status" class="text-[10px] uppercase tracking-wider font-bold text-slate-500">Connecting...</span>
                </div>
            </div>
        </div>
        
        <div class="flex items-center space-x-6">
            <div class="text-right hidden sm:block">
                <div class="text-[10px] uppercase tracking-widest text-slate-400 font-bold">Authenticated User</div>
                <div class="text-sm font-semibold text-slate-700">{{ Auth::user()->name }}</div>
            </div>
            <div class="h-10 w-10 rounded-full bg-slate-200 border-2 border-white shadow-sm overflow-hidden">
                <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&background=0e8ce8&color=fff" alt="Avatar">
            </div>
            <a href="{{ route('logout') }}" class="bg-red-50 text-red-600 hover:bg-red-100 px-4 py-2 rounded-xl text-sm font-bold transition-all duration-300">
                Logout
            </a>
        </div>
    </header>

    <main class="flex-1 flex overflow-hidden space-x-4">
        <!-- Sidebar -->
        <aside class="w-80 glass rounded-3xl overflow-hidden flex flex-col shadow-sm animate-fade-in-up" style="animation-delay: 0.1s;">
            <div class="p-6 border-b border-slate-100/50">
                <h2 class="text-lg font-bold text-slate-800">Messages</h2>
                <p class="text-xs text-slate-500">Recent conversations</p>
            </div>
            <div class="flex-1 overflow-y-auto p-2 space-y-1">
                @foreach($users as $user)
                <div class="user-item p-4 rounded-2xl cursor-pointer hover:bg-white/50 transition-all duration-200 group relative"
                    data-id="{{ $user->id }}" data-name="{{ $user->name }}">
                    <div class="flex items-center space-x-4">
                        <div class="relative">
                            <img src="https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&background=random" 
                                 class="w-12 h-12 rounded-2xl shadow-sm border-2 border-white group-hover:scale-110 transition-transform" alt="">
                            <div class="absolute -bottom-1 -right-1 w-4 h-4 rounded-full bg-slate-300 border-2 border-white"></div>
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="flex justify-between items-baseline">
                                <h3 class="font-bold text-slate-800 truncate">{{ $user->name }}</h3>
                            </div>
                            <p class="text-xs text-slate-400 capitalize">{{ $user->role }}</p>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </aside>

        <!-- Chat Area -->
        <section class="flex-1 glass rounded-3xl flex flex-col shadow-sm animate-fade-in-up relative overflow-hidden" style="animation-delay: 0.2s;">
            <!-- Chat Header -->
            <div id="chat-header-container" class="p-6 border-b border-slate-100/50 flex items-center justify-between bg-white/30">
                <div id="chat-header-info" class="flex items-center space-x-4 opacity-50">
                    <div class="w-10 h-10 rounded-xl bg-slate-100 flex items-center justify-center">
                        <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                    </div>
                    <div>
                        <h2 id="current-chat-name" class="font-bold text-slate-800 leading-tight">Select a contact</h2>
                        <p id="current-chat-status" class="text-xs text-slate-400">Click a user on the left to start</p>
                    </div>
                </div>
                <div class="flex space-x-2">
                    <button class="p-2 text-slate-400 hover:text-brand-500 hover:bg-white/50 rounded-xl transition">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg>
                    </button>
                    <button class="p-2 text-slate-400 hover:text-brand-500 hover:bg-white/50 rounded-xl transition">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path></svg>
                    </button>
                </div>
            </div>

            <!-- Messages List -->
            <div id="messages" class="flex-1 p-8 overflow-y-auto space-y-6 scroll-smooth">
                <div class="h-full flex items-center justify-center flex-col opacity-20">
                    <div class="w-24 h-24 mb-4 border-4 border-slate-200 border-dashed rounded-full flex items-center justify-center">
                        <svg class="w-12 h-12 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8h2a2 2 0 012 2v6a2 2 0 01-2 2h-2v4l-4-4H9a1.994 1.994 0 01-1.414-.586m0 0L11 14h4a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2v4l.586-.586z"></path>
                        </svg>
                    </div>
                    <p class="font-medium">No conversation selected</p>
                </div>
            </div>

            <!-- Input Area -->
            <div class="p-6 bg-white/30 border-t border-slate-100/50">
                <form id="chat-form" class="relative group">
                    <input type="hidden" id="receiver-id" value="">
                    
                    <div class="flex items-center bg-white rounded-2xl shadow-sm border border-slate-200 focus-within:border-brand-500 focus-within:ring-4 focus-within:ring-brand-100 transition-all duration-300">
                        <button type="button" class="p-4 text-slate-400 hover:text-brand-500 transition">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"></path></svg>
                        </button>
                        
                        <input type="text" id="message-input"
                            class="flex-1 py-4 text-slate-700 bg-transparent focus:outline-none disabled:cursor-not-allowed text-sm"
                            placeholder="Type your message..."
                            disabled autocomplete="off">
                        
                        <button type="submit" id="send-btn"
                            class="m-2 bg-brand-600 hover:bg-brand-700 disabled:bg-slate-300 text-white p-3 rounded-xl transition-all duration-300 shadow-lg shadow-brand-200 disabled:shadow-none scale-90 group-hover:scale-100 hover:rotate-12"
                            disabled>
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
                            </svg>
                        </button>
                    </div>
                </form>
            </div>
        </section>
    </main>

    <script>
        const userId = {{ Auth::id() }};
        const messagesContainer = document.getElementById('messages');
        const chatForm = document.getElementById('chat-form');
        const messageInput = document.getElementById('message-input');
        const receiverInput = document.getElementById('receiver-id');
        const currentChatName = document.getElementById('current-chat-name');
        const currentChatStatus = document.getElementById('current-chat-status');
        const chatHeaderContainer = document.getElementById('chat-header-info');
        const sendBtn = document.getElementById('send-btn');
        const statusEl = document.getElementById('connection-status');
        const statusDot = document.getElementById('connection-dot');

        // Setup Echo
        window.Echo = new Echo({
            broadcaster: 'pusher',
            key: '{{ config('broadcasting.connections.reverb.key') }}',
            wsHost: '{{ config('broadcasting.connections.reverb.options.host') }}',
            wsPort: {{ config('broadcasting.connections.reverb.options.port') }},
            wssPort: {{ config('broadcasting.connections.reverb.options.port') }},
            forceTLS: false,
            cluster: 'mt1',
            disableStats: true,
            enabledTransports: ['ws', 'wss'],
            authEndpoint: '/broadcasting/auth',
            auth: {
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            }
        });

        const conn = window.Echo.connector.pusher.connection;
        
        conn.bind('connected', () => {
            statusEl.innerText = 'Online';
            statusEl.className = 'text-[10px] uppercase tracking-wider font-bold text-green-500';
            statusDot.className = 'w-2 h-2 rounded-full bg-green-500 shadow-[0_0_10px_rgba(34,197,94,0.6)]';
        });

        conn.bind('disconnected', () => {
            statusEl.innerText = 'Offline';
            statusEl.className = 'text-[10px] uppercase tracking-wider font-bold text-slate-400';
            statusDot.className = 'w-2 h-2 rounded-full bg-slate-400';
        });

        conn.bind('error', (err) => {
            console.error('Connection error:', err);
            statusEl.innerText = 'Port Error';
            statusEl.className = 'text-[10px] uppercase tracking-wider font-bold text-red-500';
            statusDot.className = 'w-2 h-2 rounded-full bg-red-500 shadow-lg';
        });

        window.Echo.private(`chat.${userId}`)
            .listen('MessageSent', (e) => {
                if (parseInt(receiverInput.value) === e.message.sender_id) {
                    appendMessage(e.message, 'received');
                } else {
                    // Modern Notification
                    showNotification(e.message.sender.name, e.message.message);
                }
            });

        function showNotification(sender, text) {
            const notif = document.createElement('div');
            notif.className = 'fixed top-8 right-8 bg-white/80 backdrop-blur-xl border border-white p-4 rounded-2xl shadow-2xl flex items-center space-x-4 animate-slide-in-right z-50 max-w-sm';
            notif.innerHTML = `
                <div class="flex-shrink-0 w-10 h-10 rounded-xl bg-brand-500 flex items-center justify-center text-white">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"></path></svg>
                </div>
                <div>
                    <h4 class="text-xs font-bold text-slate-400 uppercase tracking-widest">New Message</h4>
                    <p class="text-sm font-semibold text-slate-800">${sender}: <span class="font-normal text-slate-600">${text.substring(0, 30)}${text.length > 30 ? '...' : ''}</span></p>
                </div>
            `;
            document.body.appendChild(notif);
            setTimeout(() => {
                notif.style.opacity = '0';
                notif.style.transform = 'translateX(20px)';
                setTimeout(() => notif.remove(), 300);
            }, 3000);
        }

        document.querySelectorAll('.user-item').forEach(item => {
            item.addEventListener('click', function() {
                const id = this.getAttribute('data-id');
                const name = this.getAttribute('data-name');

                receiverInput.value = id;
                currentChatName.innerText = name;
                currentChatStatus.innerText = 'Active conversation';
                chatHeaderContainer.classList.remove('opacity-50');
                
                messageInput.disabled = false;
                sendBtn.disabled = false;
                messageInput.placeholder = 'Message ' + name + '...';
                messageInput.focus();

                document.querySelectorAll('.user-item').forEach(i => i.classList.remove('active'));
                this.classList.add('active');

                fetchMessages(id);
            });
        });

        function fetchMessages(receiverId) {
            messagesContainer.innerHTML = `
                <div class="h-full flex items-center justify-center">
                    <div class="w-8 h-8 border-4 border-brand-500 border-t-transparent rounded-full animate-spin"></div>
                </div>
            `;
            axios.get(`/messages/${receiverId}`)
                .then(response => {
                    messagesContainer.innerHTML = '';
                    if (response.data.length === 0) {
                        messagesContainer.innerHTML = `
                            <div class="h-full flex items-center justify-center flex-col opacity-20">
                                <p class="font-medium italic">No messages yet. Say hello!</p>
                            </div>
                        `;
                    }
                    response.data.forEach(msg => {
                        const type = msg.sender_id === userId ? 'sent' : 'received';
                        appendMessage(msg, type);
                    });
                    scrollToBottom();
                });
        }

        function appendMessage(msg, type) {
            // Remove empty placeholder if it exists
            const placeholder = messagesContainer.querySelector('.opacity-20');
            if (placeholder) placeholder.remove();

            const div = document.createElement('div');
            div.className = `flex ${type === 'sent' ? 'justify-end' : 'justify-start'} animate-fade-in-up items-end space-x-2`;
            div.setAttribute('data-msg-id', msg.id);

            const time = new Date(msg.created_at).toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });

            const content = type === 'sent' 
                ? `
                    <span class="text-[10px] text-slate-400 mb-1">${time}</span>
                    <div class="max-w-xs md:max-w-md px-5 py-3 rounded-3xl bg-brand-600 text-white shadow-lg shadow-brand-100/50 rounded-br-none message-bubble">
                        <p class="text-sm leading-relaxed">${msg.message}</p>
                    </div>
                `
                : `
                    <div class="max-w-xs md:max-w-md px-5 py-3 rounded-3xl bg-white text-slate-800 shadow-sm border border-slate-100 rounded-bl-none message-bubble">
                        <p class="text-sm leading-relaxed">${msg.message}</p>
                    </div>
                    <span class="text-[10px] text-slate-400 mb-1">${time}</span>
                `;

            div.innerHTML = content;
            messagesContainer.appendChild(div);
            scrollToBottom();
        }

        function scrollToBottom() {
            messagesContainer.scrollTo({
                top: messagesContainer.scrollHeight,
                behavior: 'smooth'
            });
        }

        chatForm.addEventListener('submit', function(e) {
            e.preventDefault();
            const message = messageInput.value.trim();
            const receiverId = receiverInput.value;

            if (!message || !receiverId) return;

            messageInput.disabled = true;
            sendBtn.disabled = true;

            axios.post('/messages', {
                receiver_id: receiverId,
                message: message
            }).then(response => {
                messageInput.value = '';
                appendMessage(response.data.message, 'sent');
            }).catch(err => {
                console.error('Send error:', err);
                alert('Connection breakdown. Is Reverb running?');
            }).finally(() => {
                messageInput.disabled = false;
                sendBtn.disabled = false;
                messageInput.focus();
            });
        });
    </script>
</body>

</html>
