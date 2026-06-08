@props(['attributes'])

@php
$hidden = $attributes->get('hidden');
$default = $attributes->get('default');
$sm = $attributes->get('sm');
$md = $attributes->get('md');
$lg = $attributes->get('lg');
$xl = $attributes->get('xl');
$twoXl = $attributes->get('twoXl');
$defaultStart = $attributes->get('defaultStart');
$smStart = $attributes->get('smStart');
$mdStart = $attributes->get('mdStart');
$lgStart = $attributes->get('lgStart');
$xlStart = $attributes->get('xlStart');
$twoXlStart = $attributes->get('twoXlStart');
@endphp

<div {{ $attributes->except([
    'hidden',
    'default', 'sm', 'md', 'lg', 'xl', 'twoXl',
    'defaultStart', 'smStart', 'mdStart', 'lgStart', 'xlStart', 'twoXlStart',
])->class([
    'hidden' => $hidden,
    'col-[var(--col-span-default)]' => $default && (! $hidden),
    'sm:col-[var(--col-span-sm)]' => $sm && (! $hidden),
    'md:col-[var(--col-span-md)]' => $md && (! $hidden),
    'lg:col-[var(--col-span-lg)]' => $lg && (! $hidden),
    'xl:col-[var(--col-span-xl)]' => $xl && (! $hidden),
    '2xl:col-[var(--col-span-2xl)]' => $twoXl && (! $hidden),
    'col-start-[var(--col-start-default)]' => $defaultStart && (! $hidden),
    'sm:col-start-[var(--col-start-sm)]' => $smStart && (! $hidden),
    'md:col-start-[var(--col-start-md)]' => $mdStart && (! $hidden),
    'lg:col-start-[var(--col-start-lg)]' => $lgStart && (! $hidden),
    'xl:col-start-[var(--col-start-xl)]' => $xlStart && (! $hidden),
    '2xl:col-start-[var(--col-start-2xl)]' => $twoXlStart && (! $hidden),
])->style([
    "--col-span-default: {$default}" => $default,
    "--col-span-sm: {$sm}" => $sm,
    "--col-span-md: {$md}" => $md,
    "--col-span-lg: {$lg}" => $lg,
    "--col-span-xl: {$xl}" => $xl,
    "--col-span-2xl: {$twoXl}" => $twoXl,
    "--col-start-default: {$defaultStart}" => $defaultStart,
    "--col-start-sm: {$smStart}" => $smStart,
    "--col-start-md: {$mdStart}" => $mdStart,
    "--col-start-lg: {$lgStart}" => $lgStart,
    "--col-start-xl: {$xlStart}" => $xlStart,
    "--col-start-2xl: {$twoXlStart}" => $twoXlStart,
]) }}>
    {{ $slot }}
</div>
