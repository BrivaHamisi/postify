<x-layout>
    <div class="w-full max-w-3xl mx-auto">
        <!-- Logged In Header Card -->
        <div class="bg-white dark:bg-[#161615] rounded-xl shadow-[0_4px_15px_rgba(0,0,0,0.05)] p-4 mb-6 sticky top-0 z-20 transition-all duration-300">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 flex items-center justify-center bg-[#fff2f2] dark:bg-[#1D0002] rounded-full">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 text-[#f53003] dark:text-[#FF4433]">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div>
                        <h1 class="text-lg font-semibold text-[#1b1b18] dark:text-[#EDEDEC]">Logged In</h1>
                        <p class="text-xs text-[#706f6c] dark:text-[#A1A09A]">Authentication Successful</p>
                    </div>
                </div>
                <form action="/logout" method="POST">
                    @csrf
                    <button 
                        type="submit" 
                        class="px-3 py-1.5 bg-[#1b1b18] dark:bg-[#EDEDEC] text-white dark:text-[#1C1C1A] rounded-full text-sm font-medium hover:bg-black dark:hover:bg-white transition-all duration-300"
                    >
                        Log Out
                    </button>
                </form>
            </div>
        </div>

        @auth
        <!-- Post Form Card -->
        <div class="bg-white dark:bg-[#161615] rounded-xl shadow-[0_4px_15px_rgba(0,0,0,0.05)] p-6 transition-all duration-300 hover:shadow-[0_6px_20px_rgba(0,0,0,0.08)]">
            <form action="/submit-post" method="POST" class="space-y-6">
                @csrf
                
                <!-- Icon and Title -->
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 flex items-center justify-center bg-[#fff2f2] dark:bg-[#1D0002] rounded-full">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6 text-[#f53003] dark:text-[#FF4433]">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v6m3-3H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <h1 class="text-2xl font-semibold text-[#1b1b18] dark:text-[#EDEDEC]">Create a New Post</h1>
                </div>

                <!-- Title Input -->
                <div class="flex flex-col">
                    <label for="title" class="text-[#1b1b18] dark:text-[#EDEDEC] font-medium mb-2">Post Title</label>
                    <input 
                        type="text" 
                        id="title" 
                        name="title" 
                        placeholder="Enter your post title" 
                        class="w-full py-3 px-4 bg-white dark:bg-[#1C1C1A] text-[#1b1b18] dark:text-[#EDEDEC] rounded-lg border border-[#e0e0e0] dark:border-[#2A2A28] focus:outline-none focus:ring-2 focus:ring-[#f53003] dark:focus:ring-[#FF4433] transition-all"
                        required
                    >
                </div>

                <!-- Body Content Input -->
                <div class="flex flex-col">
                    <label for="body" class="text-[#1b1b18] dark:text-[#EDEDEC] font-medium mb-2">Post Content</label>
                    <textarea 
                        id="body" 
                        name="content"
                        placeholder="Write your post content here..." 
                        rows="6" 
                        class="w-full py-3 px-4 bg-white dark:bg-[#1C1C1A] text-[#1b1b18] dark:text-[#EDEDEC] rounded-lg border border-[#e0e0e0] dark:border-[#2A2A28] focus:outline-none focus:ring-2 focus:ring-[#f53003] dark:focus:ring-[#FF4433] transition-all resize-y"
                        required
                    ></textarea>
                </div>

                <!-- Buttons -->
                <div class="flex gap-4 justify-end">
                    <a 
                        href="/posts" 
                        class="px-4 py-2 bg-gray-200 dark:bg-[#2A2A28] text-[#1b1b18] dark:text-[#EDEDEC] rounded-full font-medium hover:bg-gray-300 dark:hover:bg-[#333331] transition-all duration-300"
                    >
                        Back to Posts
                    </a>
                    <button 
                        type="submit" 
                        class="px-4 py-2 bg-gradient-to-r from-[#f53003] to-[#FF4433] text-white rounded-full font-medium hover:from-[#e02a00] hover:to-[#ee3d2d] transition-all duration-300"
                    >
                        Submit Post
                    </button>
                </div>
            </form>
        </div>
        @else
        <div class="text-center text-[#706f6c] dark:text-[#A1A09A]">
            Please log in to create a post.
        </div>
        @endauth
    </div>
</x-layout>