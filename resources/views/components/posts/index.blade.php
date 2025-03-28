<div class="w-full max-w-2xl mx-auto space-y-6">
    <!-- Create Post Button (Fixed Position) -->
    <div class="fixed bottom-6 right-6 z-10">
        {{-- <button 
            type="button"  href="/posts"
            class="group flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-[#f53003] to-[#FF4433] text-white rounded-full font-medium shadow-lg hover:from-[#e02a00] hover:to-[#ee3d2d] transition-all duration-300 transform hover:scale-105"
        >
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5 group-hover:rotate-90 transition-transform duration-300">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
            </svg>
            Create Post
        </button> --}}
        <a href="/posts/create" class="group flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-[#f53003] to-[#FF4433] text-white rounded-full font-medium shadow-lg hover:from-[#e02a00] hover:to-[#ee3d2d] transition-all duration-300 transform hover:scale-105">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5 group-hover:rotate-90 transition-transform duration-300">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
            </svg>
            Create Post
        </a>
    </div>

    <!-- Logged In Card -->
    <div class="bg-white dark:bg-[#161615] rounded-xl shadow-[0_4px_15px_rgba(0,0,0,0.05)] p-6 transition-all duration-300 hover:shadow-[0_6px_20px_rgba(0,0,0,0.08)]">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 flex items-center justify-center bg-[#fff2f2] dark:bg-[#1D0002] rounded-full">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6 text-[#f53003] dark:text-[#FF4433]">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <div>
                    <h1 class="text-xl font-semibold text-[#1b1b18] dark:text-[#EDEDEC]">Logged In</h1>
                    <p class="text-sm text-[#706f6c] dark:text-[#A1A09A]">Authentication Successful</p>
                </div>
            </div>
            <form action="/logout" method="POST">
                @csrf
                <button 
                    type="submit" 
                    class="px-4 py-2 bg-[#1b1b18] dark:bg-[#EDEDEC] text-white dark:text-[#1C1C1A] rounded-full font-medium hover:bg-black dark:hover:bg-white transition-all duration-300"
                >
                    Log Out
                </button>
            </form>
        </div>
    </div>

    <!-- Post Card -->
    <div class="bg-white dark:bg-[#161615] rounded-xl shadow-[0_4px_15px_rgba(0,0,0,0.05)] p-6 transition-all duration-300 hover:shadow-[0_6px_20px_rgba(0,0,0,0.08)]">
        <div class="flex gap-4">
            <div class="w-12 h-12 flex items-center justify-center bg-[#fff2f2] dark:bg-[#1D0002] rounded-full flex-shrink-0">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6 text-[#f53003] dark:text-[#FF4433]">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v6m3-3H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <div class="flex-1">
                <h1 class="text-xl font-semibold text-[#1b1b18] dark:text-[#EDEDEC] mb-2">Your Post Title Here</h1>
                <p class="text-[#706f6c] dark:text-[#A1A09A] text-base leading-relaxed mb-4">
                    This is the body content of your post. You can write anything here, and it will look clean and appealing. The design adapts to both light and dark modes, maintaining readability and style consistency.
                </p>
                <button 
                    type="button" 
                    class="px-4 py-2 bg-gradient-to-r from-[#f53003] to-[#FF4433] text-white rounded-full font-medium hover:from-[#e02a00] hover:to-[#ee3d2d] transition-all duration-300"
                >
                    Read More
                </button>
            </div>
        </div>
    </div>
</div>