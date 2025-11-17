@props([
    'fields' => [],
    'filters' => [],
    'table' => '#applicationsTable'
])

<div x-data="filterBar({
        fields: @js($fields),
        filters: @js($filters),
        table: '{{ $table }}'
    })"
     class="flex items-center gap-3"
>

    {{-- SEARCH INPUT --}}
    <div class="relative">
        <input
            type="text"
            placeholder="Pencarian"
            x-model="search"
            @input.debounce.250ms="apply()"
            class="pl-10 pr-4 py-2 rounded-full border border-gray-300 text-sm focus:ring-blue-500 focus:outline-none"
        />
        <svg class="w-5 h-5 absolute left-3 top-1/2 -translate-y-1/2 text-gray-500"
             fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M21 21l-4.35-4.35M10 18a8 8 0 100-16 8 8 0 000 16z"/>
        </svg>
    </div>

    {{-- FILTER BUTTON --}}
    <div x-data="{ open:false }" class="relative">
        <button @click="open = !open" class="p-2 rounded-full border bg-white">
            <!-- icon -->
            <svg class="w-5 h-5 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M3 6h18M7 12h10M11 18h2"/>
            </svg>
        </button>

        <div x-show="open" @click.outside="open = false" x-transition
             class="absolute right-0 mt-3 w-80 bg-white/95 backdrop-blur-md border rounded-2xl p-4 shadow-lg z-20">
            <p class="text-sm font-semibold text-gray-700 mb-3">Filter</p>

            <template x-for="section in filters" :key="section.key">
                <div class="mb-4" x-data="{ openSec: section.open ?? false }">
                    <button @click="openSec = !openSec"
                            class="flex items-center justify-between w-full text-left p-2 rounded-xl hover:bg-gray-50">
                        <span class="text-sm font-medium" x-text="section.label"></span>
                        <svg class="w-4 h-4 text-gray-500" :class="openSec && 'rotate-180'" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>

                    <div x-show="openSec" x-transition class="mt-2 ml-3">
                        {{-- TEXT --}}
                        <template x-if="section.type === 'text'">
                            <input type="text"
                                   class="w-full border rounded-lg px-2 py-1 text-sm"
                                   x-model="values[section.key]"
                                   @input.debounce.200ms="apply()" />
                        </template>

                        {{-- SELECT --}}
                        <template x-if="section.type === 'select'">
                            <select class="w-full border rounded-lg px-2 py-1 text-sm"
                                    x-model="values[section.key]"
                                    @change="apply()">
                                <option value="">Semua</option>
                                <template x-for="opt in section.options" :key="opt.value">
                                    <option :value="opt.value" x-text="opt.label"></option>
                                </template>
                            </select>
                        </template>

                        {{-- MULTI CHECK (status list) --}}
                        <template x-if="section.type === 'checkbox-list'">
                            <div class="space-y-2" >
                                <template x-for="opt in section.options" :key="opt.value">
                                    <label class="flex items-center gap-3 p-1 rounded hover:bg-gray-50 cursor-pointer">
                                        <input type="checkbox"
                                               :value="section.key + ':' + opt.value"
                                               x-model="multi"
                                               @change="apply()"
                                               class="rounded border-gray-300">
                                        <span class="text-sm" x-text="opt.label"></span>
                                    </label>
                                </template>
                            </div>
                        </template>

                        {{-- DATE RANGE --}}
                        <template x-if="section.type === 'date-range'">
                            <div class="space-y-2">
                                <select class="w-full border rounded-lg px-2 py-1 text-sm" x-model="values[section.key].preset" @change="apply()">
                                    <option value="">Semua</option>
                                    <option value="today">Hari Ini</option>
                                    <option value="7">7 Hari</option>
                                    <option value="30">30 Hari</option>
                                    <option value="custom">Custom</option>
                                </select>

                                <div x-show="values[section.key].preset === 'custom'" class="mt-2 space-y-2">
                                    <input type="date" x-model="values[section.key].from" @change="apply()" class="w-full border rounded-lg p-2 text-sm" />
                                    <input type="date" x-model="values[section.key].to" @change="apply()" class="w-full border rounded-lg p-2 text-sm" />
                                </div>
                            </div>
                        </template>
                    </div>
                </div>
            </template>

            {{-- Reset --}}
            <div class="pt-2 border-t mt-2">
                <button @click="reset()" class="text-sm text-red-600">Reset Filter</button>
            </div>
        </div>
    </div>
</div>
