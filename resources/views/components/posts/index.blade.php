<x-layout>
    <div class="w-full max-w-7xl mx-auto flex gap-8 px-4 py-8">
        <!-- Main Content -->
        <div class="flex-1 space-y-8">
            <!-- Create Post Button (Fixed Position) -->
            <div class="fixed bottom-6 right-6 z-20">
                <a href="/posts/create" class="group flex items-center gap-2 px-5 py-3 bg-gradient-to-r from-[#F53003] to-[#FF4433] text-white rounded-full font-semibold shadow-lg hover:from-[#E02A00] hover:to-[#EE3D2D] transition-all duration-300 transform hover:scale-105 shadow-[0_4px_15px_rgba(245,48,3,0.3)] hover:shadow-[0_6px_20px_rgba(245,48,3,0.4)]">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5 group-hover:rotate-90 transition-transform duration-300">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                    </svg>
                    <span class="hidden sm:inline">Create Post</span>
                </a>
            </div>
            @auth
            <!-- Welcome Card -->
            <div class="bg-white dark:bg-[#1A1A19] rounded-2xl shadow-lg p-6 transition-all duration-300 hover:shadow-xl border border-gray-100 dark:border-[#2A2A28]">
                <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 flex items-center justify-center bg-gradient-to-br from-[#FFF5F5] to-[#FFE8E8] dark:from-[#2A0001] dark:to-[#1D0002] rounded-full shadow-md">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-6 h-6 text-[#F53003] dark:text-[#FF4433]">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div>
                            <h1 class="text-xl font-bold text-gray-900 dark:text-[#F5F5F4] tracking-tight">Welcome back, {{ auth()->user()->name }}</h1>
                            <p class="text-sm text-gray-500 dark:text-[#B0AFA9] mt-1 font-medium">Ready to share your thoughts?</p>
                        </div>
                    </div>
                    <form action="/logout" method="POST">
                        @csrf
                        <button type="submit" class="w-full sm:w-auto px-4 py-2 bg-gray-900 dark:bg-[#F5F5F4] text-white dark:text-gray-900 rounded-full font-semibold hover:bg-black dark:hover:bg-white transition-all duration-200 shadow-md hover:scale-105">
                            Log Out
                        </button>
                    </form>
                </div>
            </div>
            @endauth
            <!-- Posts Section -->
            @forelse($posts as $post)
            <div class="bg-white dark:bg-[#1A1A19] rounded-2xl shadow-lg overflow-hidden transition-all duration-300 hover:shadow-xl border border-gray-100 dark:border-[#2A2A28]">
                <div class="p-6">
                    <div class="flex flex-col sm:flex-row gap-4">
                        <!-- Author Avatar -->
                        <div class="flex-shrink-0 mx-auto sm:mx-0">
                            <div class="w-12 h-12 flex items-center justify-center bg-gradient-to-br from-[#FFF5F5] to-[#FFE8E8] dark:from-[#2A0001] dark:to-[#1D0002] rounded-full shadow-md">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-6 h-6 text-[#F53003] dark:text-[#FF4433]">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" />
                                </svg>
                            </div>
                        </div>
                        
                        <!-- Post Content -->
                        <div class="flex-1 min-w-0">
                            <div class="flex flex-col sm:flex-row justify-between items-start mb-4 gap-4">
                                <div>
                                    <h1 class="text-xl font-bold text-gray-900 dark:text-[#F5F5F4] mb-1 break-words tracking-tight">{{ $post->title }}</h1>
                                    <div class="flex flex-wrap items-center gap-2 text-sm text-gray-500 dark:text-[#B0AFA9] font-medium">
                                        <span>Posted by {{ $post->user->name }}</span>
                                        <span class="w-1 h-1 bg-gray-400 rounded-full hidden sm:inline-block"></span>
                                        <span>{{ $post->created_at->diffForHumans() }}</span>
                                    </div>
                                </div>
                                
                                @if(auth()->id() === $post->user_id)
                                <div class="flex gap-2">
                                    <a href="/posts/{{ $post->id }}/edit" class="p-2 text-gray-400 hover:text-[#F53003] dark:hover:text-[#FF4433] transition-colors duration-200">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L6.832 19.82a4.5 4.5 0 01-1.897 1.13l-2.685.8.8-2.685a4.5 4.5 0 011.13-1.897L16.863 4.487zm0 0L19.5 7.125" />
                                        </svg>
                                    </a>
                                    <form action="/posts/{{ $post->id }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-2 text-gray-400 hover:text-[#F53003] dark:hover:text-[#FF4433] transition-colors duration-200" onclick="return confirm('Are you sure you want to delete this post?')">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                                @endif
                            </div>
                            
                            <p class="text-gray-700 dark:text-[#D1D1CF] text-base leading-relaxed mb-6 whitespace-pre-line">
                                {{ $post->content }}
                            </p>
                            
                            <!-- Like and Comment Buttons -->
                            <div class="flex flex-wrap items-center gap-3 mb-6">
                                <form action="{{ route('posts.like', $post->id) }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit" class="flex items-center gap-1.5 px-4 py-2 bg-gray-100 dark:bg-[#232322] text-gray-700 dark:text-[#D1D1CF] rounded-full text-sm font-semibold hover:bg-gray-200 dark:hover:bg-[#2A2A28] transition-all duration-200 shadow-sm hover:shadow-md">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M6.633 10.5c.806 0 1.533-.446 2.031-1.08a9.041 9.041 0 012.861-2.4c.723-.384 1.35-.956 1.653-1.715a4.498 4.498 0 00.322-1.672V3a.75.75 0 01.75-.75A2.25 2.25 0 0116.5 4.5c0 1.152-.26 2.243-.723 3.218-.266.558.107 1.282.725 1.282h3.126c1.026 0 1.945.694 2.054 1.715.045.422.068.85.068 1.285a11.95 11.95 0 01-2.649 7.521c-.388.482-.987.729-1.605.729H13.48c-.483 0-.964-.078-1.423-.23l-3.114-1.04a4.501 4.501 0 00-1.423-.23H5.904M14.25 9h2.25M5.904 18.75c.083.205.173.405.27.602.197.4-.078.898-.523.898h-.908c-.889 0-1.713-.518-1.972-1.368a12 12 0 01-.521-3.507c0-1.553.295-3.036.831-4.398C3.387 10.203 4.167 9.75 5 9.75h1.053c.472 0 .745.556.5.96a8.958 8.958 0 00-1.302 4.665c0 1.194.232 2.333.654 3.375z" />
                                        </svg>
                                        {{ $post->isLikedByUser(auth()->id()) ? 'Unlike' : 'Like' }} ({{ $post->likes_count }})
                                    </button>
                                </form>
                            </div>

                            <!-- Comments Section -->
                            <div class="border-t border-gray-100 dark:border-[#2A2A28] pt-6">
                                <h3 class="text-lg font-semibold text-gray-900 dark:text-[#F5F5F4] mb-4">Comments</h3>
                                @forelse($post->comments as $comment)
                                <div class="flex gap-3 mb-4">
                                    <div class="w-10 h-10 flex items-center justify-center bg-gradient-to-br from-[#FFF5F5] to-[#FFE8E8] dark:from-[#2A0001] dark:to-[#1D0002] rounded-full shadow-md">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5 text-[#F53003] dark:text-[#FF4433]">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" />
                                        </svg>
                                    </div>
                                    <div class="flex-1">
                                        <div class="flex items-center justify-between">
                                            <span class="text-sm font-semibold text-gray-900 dark:text-[#F5F5F4]">{{ $comment->user->name }}</span>
                                            <span class="text-xs text-gray-500 dark:text-[#B0AFA9]">{{ $comment->created_at->diffForHumans() }}</span>
                                        </div>
                                        <p class="text-gray-700 dark:text-[#D1D1CF] text-sm mt-1">{{ $comment->content }}</p>
                                    </div>
                                </div>
                                @empty
                                <div class="text-center text-gray-500 dark:text-[#B0AFA9] py-4">
                                    <p class="font-medium">No Comments Yet</p>
                                    <p class="text-sm mt-1">Be the first to comment!</p>
                                </div>
                                @endforelse

                                <!-- Comment Form -->
                                @auth
                                <form action="{{ route('posts.comment', $post->id) }}" method="POST" class="mt-6">
                                    @csrf
                                    <div class="flex gap-3">
                                        <textarea 
                                            name="content" 
                                            placeholder="Add a comment..." 
                                            rows="2" 
                                            class="flex-1 py-2 px-4 bg-[#F9FAFB] dark:bg-[#232322] text-gray-900 dark:text-[#F5F5F4] rounded-lg border border-gray-200 dark:border-[#333331] focus:outline-none focus:ring-2 focus:ring-[#F53003] dark:focus:ring-[#FF4433] transition-all duration-200 resize-none shadow-sm"
                                            required
                                        ></textarea>
                                        <button 
                                            type="submit" 
                                            class="px-4 py-2 bg-gradient-to-r from-[#F53003] to-[#FF4433] text-white rounded-full font-semibold hover:from-[#E02A00] hover:to-[#EE3D2D] transition-all duration-200 shadow-md hover:scale-105"
                                        >
                                            Post
                                        </button>
                                    </div>
                                </form>
                                @endauth
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @empty
            <div class="bg-white dark:bg-[#1A1A19] rounded-2xl shadow-lg p-8 text-center border border-gray-100 dark:border-[#2A2A28]">
                <div class="mx-auto max-w-md">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-12 h-12 mx-auto text-gray-400 dark:text-[#B0AFA9] mb-4">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" />
                    </svg>
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-[#F5F5F4] mb-2">No posts yet</h3>
                    <p class="text-gray-500 dark:text-[#B0AFA9] mb-6 font-medium">Be the first to share your thoughts with the community!</p>
                    <a href="/posts/create" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-[#F53003] to-[#FF4433] text-white rounded-full font-semibold shadow-md hover:from-[#E02A00] hover:to-[#EE3D2D] transition-all duration-200">
                        Create your first post
                    </a>
                </div>
            </div>
            @endforelse
            <div class="mt-8">
                {{ $posts->links() }}
            </div>
        </div>

        <!-- Sidebar: Top Posts -->
        <div class="w-96 hidden lg:block">
            <div class="bg-white dark:bg-[#1A1A19] rounded-2xl shadow-lg p-6 sticky top-4">
                <h2 class="text-2xl font-bold text-gray-900 dark:text-[#F5F5F4] mb-6 tracking-tight">Top Posts</h2>
                @foreach($topPosts as $topPost)
                <div class="mb-6 pb-6 border-b border-gray-100 dark:border-[#2A2A28] last:border-b-0 group">
                    <a href="/posts/{{ $topPost->id }}" class="block">
                        <h3 class="text-base font-semibold text-gray-900 dark:text-[#F5F5F4] line-clamp-2 group-hover:text-[#F53003] dark:group-hover:text-[#FF4433] transition-colors duration-200">{{ $topPost->title }}</h3>
                        <p class="text-sm text-gray-500 dark:text-[#B0AFA9] mt-2 font-medium">By: {{ $topPost->user->name }}</p>
                        <div class="flex items-center gap-4 mt-2 text-sm text-gray-500 dark:text-[#B0AFA9] font-medium">
                            <span>{{ $topPost->likes_count + $topPost->comments_count }} Engagements</span>
                            <span class="flex items-center gap-1">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6.633 10.5c.806 0 1.533-.446 2.031-1.08a9.041 9.041 0 012.861-2.4c.723-.384 1.35-.956 1.653-1.715a4.498 4.498 0 00.322-1.672V3a.75.75 0 01.75-.75A2.25 2.25 0 0116.5 4.5c0 1.152-.26 2.243-.723 3.218-.266.558.107 1.282.725 1.282h3.126c1.026 0 1.945.694 2.054 1.715.045.422.068.85.068 1.285a11.95 11.95 0 01-2.649 7.521c-.388.482-.987.729-1.605.729H13.48c-.483 0-.964-.078-1.423-.23l-3.114-1.04a4.501 4.501 0 00-1.423-.23H5.904M14.25 9h2.25M5.904 18.75c.083.205.173.405.27.602.197.4-.078.898-.523.898h-.908c-.889 0-1.713-.518-1.972-1.368a12 12 0 01-.521-3.507c0-1.553.295-3.036.831-4.398C3.387 10.203 4.167 9.75 5 9.75h1.053c.472 0 .745.556.5.96a8.958 8.958 0 00-1.302 4.665c0 1.194.232 2.333.654 3.375z" />
                                </svg>
                                {{ $topPost->likes_count }}
                            </span>
                            <span class="flex items-center gap-1">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 20.25c4.97 0 9-3.694 9-8.25s-4.03-8.25-9-8.25S3 7.444 3 12c0 2.104.859 4.023 2.273 5.48.432.447.74 1.04.586 1.641a4.483 4.483 0 01-.923 1.785A5.969 5.969 0 006 21c1.282 0 2.47-.402 3.445-1.087.81.22 1.668.337 2.555.337z" />
                                </svg>
                                {{ $topPost->comments_count }}
                            </span>
                        </div>
                    </a>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</x-layout>