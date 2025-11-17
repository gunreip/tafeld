/home/gunreip/code/tafeld/resources/views/livewire/layout/partials/header.blade.php
  25,57:             <x-heroicon-o-moon class="w-5 h-5" x-show="!isDark" />
  26,55:             <x-heroicon-o-sun class="w-5 h-5" x-show="isDark" />

/home/gunreip/code/tafeld/resources/views/livewire/layout/app.blade.php
  31,17:                 isDark: false,
  35,26:                     this.isDark = saved === 'true';
  36,76:                     document.documentElement.classList.toggle('dark', this.isDark);
  40,26:                     this.isDark = !this.isDark;
  40,41:                     this.isDark = !this.isDark;
  41,60:                     localStorage.setItem('dark-mode', this.isDark);
  42,76:                     document.documentElement.classList.toggle('dark', this.isDark);

/home/gunreip/code/tafeld/resources/views/livewire/layout/guest.blade.php
  17,17:                 isDark: false,
  21,26:                     this.isDark = saved === 'true';
  22,76:                     document.documentElement.classList.toggle('dark', this.isDark);
  26,26:                     this.isDark = !this.isDark;
  26,41:                     this.isDark = !this.isDark;
  27,60:                     localStorage.setItem('dark-mode', this.isDark);
  28,76:                     document.documentElement.classList.toggle('dark', this.isDark);
  57,65:                     <x-heroicon-o-moon class="w-5 h-5" x-show="!isDark" />
  58,63:                     <x-heroicon-o-sun class="w-5 h-5" x-show="isDark" />

/home/gunreip/code/tafeld/storage/framework/views/0c44ad988bbabf0131ff435b801b79b3.blade.php
  1,14: <svg x-show="isDark" class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true" data-slot="icon">

/home/gunreip/code/tafeld/storage/framework/views/22b14467d9a73ee6186b86d42310b20f.blade.php
  1,15: <svg x-show="!isDark" class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true" data-slot="icon">