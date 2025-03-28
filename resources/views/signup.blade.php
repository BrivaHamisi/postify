<x-layout>
    <div class="w-full max-w-md bg-white dark:bg-[#161615] rounded-lg shadow-[0px_0px_1px_0px_rgba(0,0,0,0.03),0px_1px_2px_0px_rgba(0,0,0,0.06)] p-6 lg:p-8 mb-6 transition-all duration-300 transform starting:translate-y-4 starting:opacity-0 translate-y-0 opacity-100">
        <div class="flex flex-col items-center mb-6">
            <div class="w-14 h-14 mb-4 flex items-center justify-center bg-[#fff2f2] dark:bg-[#1D0002] rounded-full">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6 text-[#f53003] dark:text-[#FF4433]">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <h1 class="text-2xl font-medium text-[#1b1b18] dark:text-[#EDEDEC] mb-2">Create an account</h1>
            <p class="text-[#706f6c] dark:text-[#A1A09A] text-sm text-center">Join our community and start your journey with us.</p>
        </div>
        
        <form action="/register" method="POST" class="space-y-4">
            @csrf
            <div class="flex flex-col space-y-1">
                <label for="name" class="text-sm font-medium text-[#1b1b18] dark:text-[#EDEDEC]">Name</label>
                <input 
                    type="text" 
                    id="name" 
                    name="name" 
                    required 
                    class="px-4 py-2 border border-[#e3e3e0] dark:border-[#3E3E3A] rounded-sm bg-[#FDFDFC] dark:bg-[#0a0a0a] text-[#1b1b18] dark:text-[#EDEDEC] focus:outline-none focus:ring-1 focus:ring-[#f53003] dark:focus:ring-[#FF4433] transition-all"
                    placeholder="Enter your full name"
                >
            </div>
            
            <div class="flex flex-col space-y-1">
                <label for="email" class="text-sm font-medium text-[#1b1b18] dark:text-[#EDEDEC]">Email</label>
                <input 
                    type="email" 
                    id="email" 
                    name="email" 
                    required 
                    class="px-4 py-2 border border-[#e3e3e0] dark:border-[#3E3E3A] rounded-sm bg-[#FDFDFC] dark:bg-[#0a0a0a] text-[#1b1b18] dark:text-[#EDEDEC] focus:outline-none focus:ring-1 focus:ring-[#f53003] dark:focus:ring-[#FF4433] transition-all"
                    placeholder="your.email@example.com"
                >
            </div>
            
            <div class="flex flex-col space-y-1">
                <label for="password" class="text-sm font-medium text-[#1b1b18] dark:text-[#EDEDEC]">Password</label>
                <input 
                    type="password" 
                    id="password" 
                    name="password" 
                    required 
                    class="px-4 py-2 border border-[#e3e3e0] dark:border-[#3E3E3A] rounded-sm bg-[#FDFDFC] dark:bg-[#0a0a0a] text-[#1b1b18] dark:text-[#EDEDEC] focus:outline-none focus:ring-1 focus:ring-[#f53003] dark:focus:ring-[#FF4433] transition-all"
                    placeholder="Create a strong password"
                >
                <p class="text-xs text-[#706f6c] dark:text-[#A1A09A] mt-1">Password must be at least 8 characters long</p>
            </div>
            
            <div class="flex items-center mt-4">
                <input 
                    type="checkbox" 
                    id="terms" 
                    name="terms" 
                    required 
                    class="h-4 w-4 border border-[#e3e3e0] dark:border-[#3E3E3A] rounded accent-[#f53003] dark:accent-[#FF4433]"
                >
                <label for="terms" class="ml-2 text-sm text-[#706f6c] dark:text-[#A1A09A]">
                    I agree to the <a href="#" class="text-[#f53003] dark:text-[#FF4433] hover:underline">Terms of Service</a> and <a href="#" class="text-[#f53003] dark:text-[#FF4433] hover:underline">Privacy Policy</a>
                </label>
            </div>
            
            <button 
                type="submit" 
                class="w-full py-3 mt-4 bg-[#1b1b18] dark:bg-[#EDEDEC] text-white dark:text-[#1C1C1A] rounded-sm font-medium hover:bg-black dark:hover:bg-white transition-all shadow-[0px_0px_1px_0px_rgba(0,0,0,0.03),0px_1px_2px_0px_rgba(0,0,0,0.06)]"
            >
                Create Account
            </button>
        </form>

        <div class="mt-6 text-center">
            <span class="text-sm text-[#706f6c] dark:text-[#A1A09A]">Already have an account?</span>
            <a href="/login" class="text-sm text-[#f53003] dark:text-[#FF4433] hover:underline ml-1">Log in</a>
        </div>
        
        <div class="mt-8 relative">
            <div class="absolute inset-0 flex items-center">
                <div class="w-full border-t border-[#e3e3e0] dark:border-[#3E3E3A]"></div>
            </div>
            <div class="relative flex justify-center">
                <span class="px-2 bg-white dark:bg-[#161615] text-sm text-[#706f6c] dark:text-[#A1A09A]">Or continue with</span>
            </div>
        </div>
        
        <div class="mt-6 flex flex-row gap-4 justify-center">
            <button class="w-full py-2 border border-[#e3e3e0] dark:border-[#3E3E3A] rounded-sm flex items-center justify-center gap-2 hover:border-[#1915014a] dark:hover:border-[#62605b] transition-all">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="currentColor" class="text-[#1b1b18] dark:text-[#EDEDEC]">
                    <path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" fill="#4285F4"/>
                    <path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" fill="#34A853"/>
                    <path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z" fill="#FBBC05"/>
                    <path d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" fill="#EA4335"/>
                </svg>
                <span class="text-sm text-[#1b1b18] dark:text-[#EDEDEC]">Google</span>
            </button>
            <button class="w-full py-2 border border-[#e3e3e0] dark:border-[#3E3E3A] rounded-sm flex items-center justify-center gap-2 hover:border-[#1915014a] dark:hover:border-[#62605b] transition-all">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="currentColor" class="text-[#1b1b18] dark:text-[#EDEDEC]">
                    <path d="M22.675 0H1.325C.593 0 0 .593 0 1.325v21.351C0 23.407.593 24 1.325 24H12.82v-9.294H9.692v-3.622h3.128V8.413c0-3.1 1.893-4.788 4.659-4.788 1.325 0 2.463.099 2.795.143v3.24l-1.918.001c-1.504 0-1.795.715-1.795 1.763v2.313h3.587l-.467 3.622h-3.12V24h6.116c.73 0 1.323-.593 1.323-1.325V1.325C24 .593 23.407 0 22.675 0z"/>
                </svg>
                <span class="text-sm text-[#1b1b18] dark:text-[#EDEDEC]">Facebook</span>
            </button>
            <button class="w-full py-2 border border-[#e3e3e0] dark:border-[#3E3E3A] rounded-sm flex items-center justify-center gap-2 hover:border-[#1915014a] dark:hover:border-[#62605b] transition-all">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="currentColor" class="text-[#1b1b18] dark:text-[#EDEDEC]">
                    <path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/>
                </svg>
                <span class="text-sm text-[#1b1b18] dark:text-[#EDEDEC]">Twitter</span>
            </button>
    </div>
</x-layout>