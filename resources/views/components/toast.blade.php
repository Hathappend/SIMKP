<div
    x-data="{
        showSuccess: {{ session()->has('success') ? 'true' : 'false' }},
        showError: {{ session()->has('error') ? 'true' : 'false' }},
        message: ''
    }"
    x-init="
        @if(session()->has('success'))
            message = '{{ session('success') }}';
            setTimeout(() => showSuccess = false, 5000);
        @elseif(session()->has('error'))
            message = '{{ session('error') }}';
            setTimeout(() => showError = false, 5000);
        @endif
    "
    class="fixed top-5 right-5 z-[100] w-full max-w-xs space-y-3"
>
    <div
        x-show="showSuccess"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 transform translate-x-10"
        x-transition:enter-end="opacity-100 transform translate-x-0"
        x-transition:leave="transition ease-in duration-300"
        x-transition:leave-start="opacity-100 transform translate-x-0"
        x-transition:leave-end="opacity-0 transform translate-x-10"
        class="p-4 bg-white border border-green-300 rounded-lg shadow-lg flex items-start"
        style="display: none;"
    >
        <div class="flex-shrink-0">
            <svg class="h-6 w-6 text-green-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
        </div>
        <div class="ml-3 w-0 flex-1 pt-0.5">
            <p class="text-sm font-medium text-gray-900" x-text="message"></p>
        </div>
        <div class="ml-4 flex-shrink-0 flex">
            <button @click="showSuccess = false" class="inline-flex text-gray-400 hover:text-gray-500">
                <span class="sr-only">Tutup</span>
                <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                </svg>
            </button>
        </div>
    </div>

    <div
        x-show="showError"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 transform translate-x-10"
        x-transition:enter-end="opacity-100 transform translate-x-0"
        x-transition:leave="transition ease-in duration-300"
        x-transition:leave-start="opacity-100 transform translate-x-0"
        x-transition:leave-end="opacity-0 transform translate-x-10"
        class="p-4 bg-white border border-red-300 rounded-lg shadow-lg flex items-start"
        style="display: none;"
    >
        <div class="flex-shrink-0">
            <svg class="h-6 w-6 text-red-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
        </div>
        <div class="ml-3 w-0 flex-1 pt-0.5">
            <p class="text-sm font-medium text-gray-900" x-text="message"></p>
        </div>
        <div class="ml-4 flex-shrink-0 flex">
            <button @click="showError = false" class="inline-flex text-gray-400 hover:text-gray-500">
                <span class="sr-only">Tutup</span>
                <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                </svg>
            </button>
        </div>
    </div>
</div>
