<div class="flex gap-3 mb-4 {{ $depth > 0 ? 'ml-' . ($depth * 8) : '' }}">
    <div class="flex-shrink-0">
        <div class="w-{{ $depth > 0 ? 8 : 10 }} h-{{ $depth > 0 ? 8 : 10 }} flex items-center justify-center bg-gradient-to-br from-[#FFF5F5] to-[#FFE8E8] dark:from-[#2A0001] dark:to-[#1D0002] rounded-full shadow-md">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"
                 class="w-{{ $depth > 0 ? 4 : 6 }} h-{{ $depth > 0 ? 4 : 6 }} text-[#F53003] dark:text-[#FF4433]">
                <path stroke-linecap="round" stroke-linejoin="round"
                      d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" />
            </svg>
        </div>
    </div>
    <div class="flex-1">
        <div class="flex items-center justify-between">
            <span class="text-sm font-semibold text-gray-900 dark:text-[#F5F5F4]">{{ $comment->user->name }}</span>
            <span class="text-xs text-gray-500 dark:text-[#B0AFA9]">{{ $comment->created_at->diffForHumans() }}</span>
        </div>
        <p class="text-gray-700 dark:text-[#D1D1CF] text-{{ $depth > 0 ? 'sm' : 'base' }} mt-1">{{ $comment->content }}</p>
        <div class="flex items-center gap-3 mt-2">
            @auth
                <form action="{{ route('comments.like', $comment->id) }}" method="POST" class="inline">
                    @csrf
                    <button type="submit" onclick="console.log('Liking comment {{ $comment->id }}')"
                            class="flex items-center gap-1 text-sm {{ $comment->isLikedByUser(auth()->id()) ? 'text-red-600 dark:text-red-400' : 'text-gray-500 dark:text-[#B0AFA9]' }} hover:text-red-600 dark:hover:text-red-400 transition-colors duration-200">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6.633 10.5c.806 0 1.533-.446 2.031-1.08a9.041 9.041 0 012.861-2.4c.723-.384 1.35-.956 1.653-1.715a4.498 4.498 0 00.322-1.672V3a.75.75 0 01.75-.75A2.25 2.25 0 0116.5 4.5c0 1.152-.26 2.243-.723 3.218-.266.558.107 1.282.725 1.282h3.126c1.026 0 1.945.694 2.054 1.715.045.422.068.85.068 1.285a11.95 11.95 0 01-2.649 7.521c-.388.482-.987.729-1.605.729H13.48c-.483 0-.964-.078-1.423-.23l-3.114-1.04a4.501 4.501 0 00-1.423-.23H5.904M14.25 9h2.25M5.904 18.75c.083.205.173.405.27.602.197.4-.078.898-.523.898h-.908c-.889 0-1.713-.518-1.972-1.368a12 12 0 01-.521-3.507c0-1.553.295-3.036.831-4.398C3.387 10.203 4.167 9.75 5 9.75h1.053c.472 0 .745.556.5.96a8.958 8.958 0 00-1.302 4.665c0 1.194.232 2.333.654 3.375z" />
                        </svg>
                        {{ $comment->isLikedByUser(auth()->id()) ? 'Unlike' : 'Like' }} ({{ $comment->likes->count() }})
                    </button>
                </form>
                <button type="button"
                        class="text-sm text-gray-500 dark:text-[#B0AFA9] hover:text-[#F53003] dark:hover:text-[#FF4433] transition-colors duration-200"
                        onclick="document.getElementById('reply-form-{{ $comment->id }}').classList.toggle('hidden')">
                    Reply
                </button>
            @endauth
        </div>
        <!-- Reply Form -->
        @auth
            <form id="reply-form-{{ $comment->id }}" action="{{ route('posts.comment', $post->id) }}" method="POST" class="mt-2 hidden">
                @csrf
                <input type="hidden" name="parent_id" value="{{ $comment->id }}">
                <div class="flex gap-2">
                    <textarea name="content" placeholder="Reply to {{ $comment->user->name }}..." rows="1"
                              class="flex-1 py-2 px-4 bg-[#F9FAFB] dark:bg-[#1A1A19] text-gray-900 dark:text-[#F5F5F4] rounded-lg border border-gray-200 dark:border-[#333331] focus:outline-none focus:ring-2 focus:ring-[#F53003] dark:focus:ring-[#FF4433] transition-all duration-200 resize-none shadow-sm"
                              required></textarea>
                    <button type="submit"
                            class="px-3 py-2 bg-gradient-to-r from-[#F53003] to-[#FF4433] text-white rounded-full font-semibold hover:from-[#E02A00] hover:to-[#EE3D2D] transition-all duration-200 shadow-md hover:scale-105">
                        Reply
                    </button>
                </div>
            </form>
        @endauth
        <!-- Nested Replies -->
        @if ($comment->replies && $comment->replies->isNotEmpty())
            {{-- @dump($comment->replies) <!-- Debug here --> --}}
            <div class="mt-4 space-y-4 border-l-2 border-gray-200 dark:border-[#333331] pl-4">
                @foreach ($comment->replies as $reply)
                    <x-comment :comment="$reply" :post="$post" :depth="$depth + 1" />
                @endforeach
            </div>
        @endif
    </div>
</div>