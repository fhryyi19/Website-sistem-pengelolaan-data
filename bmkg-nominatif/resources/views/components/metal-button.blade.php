@props([
    'variant' => 'default',
    'type' => 'button',
])

@php
$colorVariants = [
    'default' => [
        'outer' => 'bg-gradient-to-b from-[#000] to-[#A0A0A0]',
        'inner' => 'bg-gradient-to-b from-[#FAFAFA] via-[#3E3E3E] to-[#E5E5E5]',
        'button' => 'bg-gradient-to-b from-[#B9B9B9] to-[#969696]',
        'textColor' => 'text-white',
        'textShadow' => '[text-shadow:_0_-1px_0_rgb(80_80_80_/_100%)]',
    ],
    'primary' => [
        'outer' => 'bg-gradient-to-b from-[#000] to-[#A0A0A0]',
        'inner' => 'bg-gradient-to-b from-primary via-secondary to-muted',
        'button' => 'bg-gradient-to-b from-primary to-primary/40',
        'textColor' => 'text-white',
        'textShadow' => '[text-shadow:_0_-1px_0_rgb(30_58_138_/_100%)]',
    ],
    'success' => [
        'outer' => 'bg-gradient-to-b from-[#005A43] to-[#7CCB9B]',
        'inner' => 'bg-gradient-to-b from-[#E5F8F0] via-[#00352F] to-[#D1F0E6]',
        'button' => 'bg-gradient-to-b from-[#9ADBC8] to-[#3E8F7C]',
        'textColor' => 'text-[#FFF7F0]',
        'textShadow' => '[text-shadow:_0_-1px_0_rgb(6_78_59_/_100%)]',
    ],
    'error' => [
        'outer' => 'bg-gradient-to-b from-[#5A0000] to-[#FFAEB0]',
        'inner' => 'bg-gradient-to-b from-[#FFDEDE] via-[#680002] to-[#FFE9E9]',
        'button' => 'bg-gradient-to-b from-[#F08D8F] to-[#A45253]',
        'textColor' => 'text-[#FFF7F0]',
        'textShadow' => '[text-shadow:_0_-1px_0_rgb(146_64_14_/_100%)]',
    ],
    'gold' => [
        'outer' => 'bg-gradient-to-b from-[#917100] to-[#EAD98F]',
        'inner' => 'bg-gradient-to-b from-[#FFFDDD] via-[#856807] to-[#FFF1B3]',
        'button' => 'bg-gradient-to-b from-[#FFEBA1] to-[#9B873F]',
        'textColor' => 'text-[#FFFDE5]',
        'textShadow' => '[text-shadow:_0_-1px_0_rgb(178_140_2_/_100%)]',
    ],
    'bronze' => [
        'outer' => 'bg-gradient-to-b from-[#864813] to-[#E9B486]',
        'inner' => 'bg-gradient-to-b from-[#EDC5A1] via-[#5F2D01] to-[#FFDEC1]',
        'button' => 'bg-gradient-to-b from-[#FFE3C9] to-[#A36F3D]',
        'textColor' => 'text-[#FFF7F0]',
        'textShadow' => '[text-shadow:_0_-1px_0_rgb(124_45_18_/_100%)]',
    ],
];

$colors = $colorVariants[$variant] ?? $colorVariants['default'];
@endphp

<div x-data="{ isPressed: false, isHovered: false }"
     class="relative inline-flex transform-gpu rounded-md p-[1.25px] will-change-transform {{ $colors['outer'] }}"
     :style="isPressed ? 'transform: translateY(2.5px) scale(0.99); box-shadow: 0 1px 2px rgba(0,0,0,0.15); transition: all 250ms cubic-bezier(0.1, 0.4, 0.2, 1); transform-origin: center center;' : (isHovered ? 'transform: translateY(0) scale(1); box-shadow: 0 4px 12px rgba(0,0,0,0.12); transition: all 250ms cubic-bezier(0.1, 0.4, 0.2, 1); transform-origin: center center;' : 'transform: translateY(0) scale(1); box-shadow: 0 3px 8px rgba(0,0,0,0.08); transition: all 250ms cubic-bezier(0.1, 0.4, 0.2, 1); transform-origin: center center;')"
>
    <div class="absolute inset-[1px] transform-gpu rounded-lg will-change-transform {{ $colors['inner'] }}"
         :style="'transition: all 250ms cubic-bezier(0.1, 0.4, 0.2, 1); transform-origin: center center; filter: ' + (isHovered && !isPressed ? 'brightness(1.05)' : 'none')"
    ></div>

    <button {{ $attributes->merge(['type' => $type]) }}
            class="relative z-10 m-[1px] rounded-md inline-flex h-11 transform-gpu cursor-pointer items-center justify-center overflow-hidden px-6 py-2 text-sm leading-none font-semibold will-change-transform outline-none {{ $colors['button'] }} {{ $colors['textColor'] }} {{ $colors['textShadow'] }} {{ $attributes->get('class') }}"
            :style="'transition: all 250ms cubic-bezier(0.1, 0.4, 0.2, 1); transform-origin: center center; transform: ' + (isPressed ? 'scale(0.97)' : 'scale(1)') + '; filter: ' + (isHovered && !isPressed ? 'brightness(1.02)' : 'none')"
            @mousedown="isPressed = true"
            @mouseup="isPressed = false"
            @mouseleave="isPressed = false; isHovered = false"
            @mouseenter="isHovered = true"
            @touchstart="isPressed = true"
            @touchend="isPressed = false"
            @touchcancel="isPressed = false"
    >
        <!-- Shine effect overlay -->
        <div class="pointer-events-none absolute inset-0 z-20 overflow-hidden transition-opacity duration-300"
             :class="isPressed ? 'opacity-20' : 'opacity-0'"
        >
            <div class="absolute inset-0 rounded-md bg-gradient-to-r from-transparent via-neutral-100 to-transparent"></div>
        </div>

        <span class="relative z-10 inline-flex items-center gap-2">
            {{ $slot }}
        </span>

        <div x-show="isHovered && !isPressed" class="pointer-events-none absolute inset-0 bg-gradient-to-t rounded-lg from-transparent to-white/5"></div>
    </button>
</div>
