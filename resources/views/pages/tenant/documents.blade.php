@extends('layouts.tenant')

@section('title', 'Mes Documents')

@section('content')
<div class="mb-6">
    <h1 class="text-2xl font-bold text-gray-800 dark:text-white">Mes Documents</h1>
    <p class="text-gray-500 dark:text-gray-400 mt-1">Contrats, quittances et documents associés.</p>
</div>

<div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="text-left text-xs text-gray-400 uppercase bg-gray-50 dark:bg-gray-900/30 border-b dark:border-gray-700">
                    <th class="px-5 py-3">Document</th>
                    <th class="px-5 py-3">Bien</th>
                    <th class="px-5 py-3">Taille</th>
                    <th class="px-5 py-3">Date</th>
                    <th class="px-5 py-3 text-center">Action</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50 dark:divide-gray-700">
                @forelse($documents as $doc)
                    @php
                        $catIcons = ['invoice' => 'receipt', 'receipt' => 'check-square', 'quote' => 'file-edit', 'inspection' => 'clipboard-check', 'lease_contract' => 'file-text', 'other' => 'paperclip'];
                        $catColors = ['invoice' => 'red', 'receipt' => 'emerald', 'quote' => 'blue', 'inspection' => 'purple', 'lease_contract' => 'indigo', 'other' => 'gray'];
                        $icon = $catIcons[$doc->category] ?? 'paperclip';
                        $color = $catColors[$doc->category] ?? 'gray';
                    @endphp
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/30 transition">
                        <td class="px-5 py-3">
                            <div class="flex items-center gap-2">
                                <div class="w-8 h-8 bg-{{ $color }}-100 dark:bg-{{ $color }}-900/30 rounded-lg flex items-center justify-center shrink-0">
                                    <i data-lucide="{{ $icon }}" class="w-4 h-4 text-{{ $color }}-600"></i>
                                </div>
                                <span class="font-medium text-gray-800 dark:text-white">{{ $doc->name }}</span>
                            </div>
                        </td>
                        <td class="px-5 py-3 text-gray-600 dark:text-gray-300">{{ $doc->property?->title ?? 'Général' }}</td>
                        <td class="px-5 py-3 text-gray-500 dark:text-gray-400">{{ number_format($doc->file_size / 1024, 1) }} Ko</td>
                        <td class="px-5 py-3 text-xs text-gray-500 dark:text-gray-400">{{ $doc->created_at->format('d/m/Y') }}</td>
                        <td class="px-5 py-3 text-center">
                            <a href="{{ route('tenant.documents.download', $doc) }}" class="inline-flex items-center gap-1 px-3 py-1 text-xs border border-gray-200 dark:border-gray-600 rounded-lg hover:text-emerald-600 hover:border-emerald-200 transition">
                                <i data-lucide="download" class="w-3 h-3"></i> Télécharger
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-5 py-8 text-center text-gray-400">Aucun document disponible.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($documents->hasPages())
        <div class="p-4 border-t border-gray-100 dark:border-gray-700">{{ $documents->links() }}</div>
    @endif
</div>
@endsection