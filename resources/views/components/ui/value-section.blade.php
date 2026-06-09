<section class="max-w-7xl mx-auto px-6 py-10">
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">

        @php
            $values = [
                [
                    'icon' => 'percent',
                    'color' => 'bg-blue-50 text-blue-600',
                    'title' => 'Zéro commission',
                    'desc' => 'Pas de frais d\'agence cachés. Vous payez uniquement votre caution.',
                ],
                [
                    'icon' => 'file-check-2',
                    'color' => 'bg-green-50 text-green-600',
                    'title' => 'Dossiers simplifiés',
                    'desc' => 'Une démarche rapide et transparente, sans paperasse inutile.',
                ],
                [
                    'icon' => 'headphones',
                    'color' => 'bg-purple-50 text-purple-600',
                    'title' => 'Suivi personnalisé',
                    'desc' => 'Un interlocuteur dédié pour vous accompagner de la visite à la signature.',
                ],
            ];
        @endphp

        @foreach ($values as $v)
            <div class="flex items-start gap-4 bg-gray-50 rounded-2xl p-5 border border-gray-100">
                <div class="flex-shrink-0 w-10 h-10 {{ $v['color'] }} rounded-xl flex items-center justify-center">
                    <i data-lucide="{{ $v['icon'] }}" class="w-5 h-5"></i>
                </div>
                <div>
                    <h3 class="font-semibold text-gray-800 text-sm">{{ $v['title'] }}</h3>
                    <p class="text-gray-500 text-xs mt-1 leading-relaxed">{{ $v['desc'] }}</p>
                </div>
            </div>
        @endforeach

    </div>
</section>
