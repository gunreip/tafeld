<div>
    {{-- Dark/Light-Mode Toggle --}}
    <div x-data="{
        theme: localStorage.getItem('theme') || (window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light'),
        toggle() {
            this.theme = (this.theme === 'dark') ? 'light' : 'dark';
            localStorage.setItem('theme', this.theme);
            document.documentElement.classList.toggle('dark', this.theme === 'dark');
        }
    }" x-init="document.documentElement.classList.toggle('dark', theme === 'dark')" class="flex items-center">
        <button @click="toggle()" class="p-2 rounded-md text-gray-400 hover:text-white hover:bg-gray-700 transition"
            :aria-label="theme === 'dark' ? 'Switch to light mode' : 'Switch to dark mode'">
            <template x-if="theme === 'dark'">
                <ion-icon name="moon" class="w-5 h-5 text-blue-400"></ion-icon>
            </template>
            <template x-if="theme === 'light'">
                <ion-icon name="sunny" class="w-5 h-5 text-yellow-400"></ion-icon>
            </template>
        </button>
    </div>
</div>
