<template>
    <div class="text-right">
        <Menu as="div" class="relative inline-block text-left">
            <div>
                <MenuButton class="
                    inline-flex
                    items-center
                    px-2
                    py-1
                    bg-gray-800
                    border border-transparent
                    rounded-md
                    font-semibold
                    text-xs text-white
                    uppercase
                    tracking-widest
                    hover:bg-gray-700
                    active:bg-gray-900
                    focus:outline-none focus:border-gray-900 focus:ring focus:ring-gray-300
                    disabled:opacity-25
                    shadow-sm
                    hover:shadow-md
                    focus:shadow-lg
                    active:shadow-outline
                    transition">
                    Options
                    <ChevronUpIcon class="ml-2 -mr-1 h-5 w-5 text-slate-200 hover:text-slate-100" aria-hidden="true" />
                </MenuButton>
            </div>

            <transition enter-active-class="transition duration-100 ease-out"
                enter-from-class="transform scale-95 opacity-0" enter-to-class="transform scale-100 opacity-100"
                leave-active-class="transition duration-75 ease-in" leave-from-class="transform scale-100 opacity-100"
                leave-to-class="transform scale-95 opacity-0">
                <MenuItems
                    class="-top-2 transform -translate-y-full absolute left-0 w-fit origin-top-right bg-white divide-y divide-gray-100 rounded-md shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none">
                    <div class="px-1 py-1">
                        <MenuItem v-slot="{ active }">
                        <button :class="[
                            active ? 'bg-slate-500 text-white' : 'text-gray-900',
                            'group flex w-full items-center rounded-md px-2 py-2 text-sm',
                        ]">
                            <EditIcon :active="active" class="mr-2 h-5 w-5 text-slate-400" aria-hidden="true" />
                            Edit
                        </button>
                        </MenuItem>
                        <MenuItem v-slot="{ active }">
                        <button :class="[
                            active ? 'bg-slate-500 text-white' : 'text-gray-900',
                            'group flex w-full items-center rounded-md px-2 py-2 text-sm',
                        ]">
                            <DuplicateIcon :active="active" class="mr-2 h-5 w-5 text-slate-400" aria-hidden="true" />
                            Duplicate
                        </button>
                        </MenuItem>
                    </div>
                    <div class="px-1 py-1">
                        <MenuItem v-slot="{ active }">
                        <button :class="[
                            active ? 'bg-slate-500 text-white' : 'text-gray-900',
                            'group flex w-full items-center rounded-md px-2 py-2 text-sm',
                        ]">
                            <ArchiveIcon :active="active" class="mr-2 h-5 w-5 text-slate-400" aria-hidden="true" />
                            Archive
                        </button>
                        </MenuItem>
                        <MenuItem v-slot="{ active }">
                        <button :class="[
                            active ? 'bg-slate-500 text-white' : 'text-gray-900',
                            'group flex w-full items-center rounded-md px-2 py-2 text-sm',
                        ]">
                            <MoveIcon :active="active" class="mr-2 h-5 w-5 text-slate-400" aria-hidden="true" />
                            Move
                        </button>
                        </MenuItem>
                    </div>

                    <div class="px-1 py-1">
                        <MenuItem v-slot="{ active }">
                        <button :class="[
                            active ? 'bg-slate-500 text-white' : 'text-gray-900',
                            'group flex w-full items-center rounded-md px-2 py-2 text-sm',
                        ]">
                            <DeleteIcon :active="active" class="mr-2 h-5 w-5 text-slate-400" aria-hidden="true" />
                            Delete
                        </button>
                        </MenuItem>
                    </div>
                </MenuItems>
            </transition>
        </Menu>
    </div>
</template>

<script setup>
import { Menu, MenuButton, MenuItems, MenuItem } from '@headlessui/vue'
import { ChevronUpIcon } from '@heroicons/vue/solid'
</script>
