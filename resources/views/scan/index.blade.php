@extends('layouts.dashboard')

@section('title', 'Scanner un Pass')

@section('content')
    <div class="max-w-4xl mx-auto">

        {{-- En-tête --}}
        <div class="flex items-center gap-3 mb-6">
            <div class="flex items-center justify-center w-10 h-10 rounded-lg bg-primary/10 dark:bg-primary-500/10">
                <i data-lucide="qr-code" class="w-5 h-5 text-primary dark:text-primary-400"></i>
            </div>
            <div>
                <h1 class="text-lg font-semibold text-gray-900 dark:text-white leading-tight">Scanner un QR Code</h1>
                <p class="text-sm text-gray-500 dark:text-gray-400">Utilisez votre caméra pour valider le pass d'un visiteur</p>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 border border-gray-100 dark:border-gray-700 rounded-xl shadow-sm dark:shadow-gray-900/50 overflow-hidden">
            <div class="p-6">

                {{-- Zone de scan avec overlay --}}
                <div class="mb-8">
                    <div class="relative rounded-xl overflow-hidden ring-1 ring-gray-900/5" style="height: 350px; width: 100%;">
                        <div id="reader" class="w-full h-full"></div>

                        {{-- Overlay avec indicateur de scan --}}
                        <div id="scan-overlay" class="absolute inset-0 pointer-events-none">
                            <div class="absolute top-0 left-0 w-10 h-10 border-t-4 border-l-4 border-primary rounded-tl-lg"></div>
                            <div class="absolute top-0 right-0 w-10 h-10 border-t-4 border-r-4 border-primary rounded-tr-lg"></div>
                            <div class="absolute bottom-0 left-0 w-10 h-10 border-b-4 border-l-4 border-primary rounded-bl-lg"></div>
                            <div class="absolute bottom-0 right-0 w-10 h-10 border-b-4 border-r-4 border-primary rounded-br-lg"></div>

                            <div id="scan-line" class="absolute left-12 right-12 h-0.5 bg-primary shadow-lg shadow-primary/50 animate-scan"></div>

                            <div id="scan-status" class="absolute bottom-4 left-0 right-0 text-center">
                                <span class="inline-flex items-center gap-2 px-3 py-1.5 bg-black/60 backdrop-blur-sm text-white text-xs rounded-full">
                                    <span id="scan-indicator" class="relative flex h-3 w-3">
                                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-green-400 opacity-75"></span>
                                        <span class="relative inline-flex rounded-full h-3 w-3 bg-green-500"></span>
                                    </span>
                                    <span id="scan-status-text">Caméra active - En attente de scan</span>
                                </span>
                            </div>
                        </div>

                        {{-- Overlay de succès/erreur --}}
                        <div id="scan-result-overlay" class="absolute inset-0 flex items-center justify-center bg-black/70 backdrop-blur-sm hidden transition-opacity duration-300 pointer-events-none">
                            <div id="scan-result-content" class="text-center text-white p-6 max-w-sm"></div>
                        </div>
                    </div>
                    <p class="flex items-center justify-center gap-1.5 text-center text-xs text-gray-400 dark:text-gray-500 mt-3">
                        <i data-lucide="scan-line" class="w-3.5 h-3.5"></i>
                        Positionnez le QR Code dans le cadre
                    </p>
                </div>

                {{-- Divider --}}
                <div class="relative my-8">
                    <div class="absolute inset-0 flex items-center">
                        <div class="w-full border-t border-gray-100 dark:border-gray-700"></div>
                    </div>
                    <div class="relative flex justify-center text-sm">
                        <span class="px-3 bg-white dark:bg-gray-800 text-gray-400 dark:text-gray-500">ou saisir manuellement</span>
                    </div>
                </div>

                {{-- Zone de saisie manuelle --}}
                <div>
                    <form id="manual-scan-form" class="flex flex-col sm:flex-row gap-3">
                        @csrf
                        <div class="relative flex-1">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i data-lucide="hash" class="w-4 h-4 text-gray-400"></i>
                            </div>
                            <input type="text" id="uuid" name="uuid" placeholder="Identifiant du pass (ex: abc123-def456-ghi789)"
                                class="w-full pl-10 pr-4 py-2.5 text-sm border border-gray-200 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary/40 focus:border-primary dark:bg-gray-700 dark:text-white transition placeholder-gray-400 outline-none">
                        </div>
                        <x-btn type="submit" class="sm:w-auto whitespace-nowrap">
                            <i data-lucide="circle-check" class="w-4 h-4"></i>
                            Valider
                        </x-btn>
                    </form>
                </div>

                {{-- Résultat du scan --}}
                <div id="scan-result" class="mt-6 hidden">
                    <div id="result-content" class="rounded-xl"></div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://unpkg.com/html5-qrcode@2.3.8/html5-qrcode.min.js"></script>

    <style>
        @keyframes scan {
            0% { top: 8%; }
            50% { top: 92%; }
            100% { top: 8%; }
        }
        .animate-scan {
            animation: scan 2.5s ease-in-out infinite;
        }
        #scan-overlay .border-primary {
            box-shadow: 0 0 10px rgba(var(--color-primary-rgb, 59, 130, 246), 0.5);
        }
        #scan-result-overlay {
            transition: opacity 0.3s ease-in-out;
        }
        #reader video {
            object-fit: cover !important;
            width: 100% !important;
            height: 100% !important;
        }
        #reader {
            background: #0a0a0a;
        }
    </style>

    <script>
        let html5QrCode;
        let isScanning = true;
        let isProcessing = false;

        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content || '{{ csrf_token() }}';
        const scanUrl = '{{ route('scan.process') }}';

        const scanStatusText = document.getElementById('scan-status-text');
        const scanIndicator = document.getElementById('scan-indicator');
        const scanLine = document.getElementById('scan-line');
        const resultOverlay = document.getElementById('scan-result-overlay');
        const resultContent = document.getElementById('scan-result-content');

        function refreshIcons() {
            if (window.lucide) window.lucide.createIcons();
        }

        function updateScanStatus(status, message) {
            const statusColors = {
                scanning: 'bg-green-500',
                processing: 'bg-yellow-500',
                success: 'bg-emerald-500',
                error: 'bg-red-500',
                idle: 'bg-gray-500'
            };

            const indicatorDot = scanIndicator.querySelector('.relative');
            if (indicatorDot) {
                indicatorDot.className = `relative inline-flex rounded-full h-3 w-3 ${statusColors[status] || 'bg-primary'}`;
            }
            if (scanStatusText) {
                scanStatusText.textContent = message;
            }
            if (scanLine) {
                const colors = {
                    scanning: 'bg-primary shadow-primary/50',
                    processing: 'bg-yellow-500 shadow-yellow-500/50',
                    success: 'bg-emerald-500 shadow-emerald-500/50',
                    error: 'bg-red-500 shadow-red-500/50',
                    idle: 'bg-gray-500 shadow-gray-500/50'
                };
                scanLine.className = `absolute left-12 right-12 h-0.5 ${colors[status] || 'bg-primary shadow-primary/50'} animate-scan`;
            }
        }

        function showResultOverlay(type, title, message, details = null) {
            const icons = {
                success: `<div class="w-16 h-16 mx-auto mb-4 bg-emerald-500/20 rounded-full flex items-center justify-center">
                            <i data-lucide="check-circle-2" class="w-8 h-8 text-emerald-400"></i>
                          </div>`,
                error: `<div class="w-16 h-16 mx-auto mb-4 bg-red-500/20 rounded-full flex items-center justify-center">
                            <i data-lucide="x-circle" class="w-8 h-8 text-red-400"></i>
                        </div>`,
                processing: `<div class="w-16 h-16 mx-auto mb-4 bg-yellow-500/20 rounded-full flex items-center justify-center">
                                <div class="animate-spin rounded-full h-8 w-8 border-4 border-yellow-400 border-t-transparent"></div>
                             </div>`
            };
            const colors = { success: 'text-emerald-400', error: 'text-red-400', processing: 'text-yellow-400' };

            resultContent.innerHTML = `
                ${icons[type] || ''}
                <h3 class="text-xl font-bold ${colors[type] || 'text-white'} mb-2">${title}</h3>
                <p class="text-gray-300 mb-2">${message}</p>
                ${details ? `<p class="text-sm text-gray-400">${details}</p>` : ''}
            `;

            resultOverlay.classList.remove('hidden');
            refreshIcons();
            setTimeout(() => resultOverlay.classList.add('hidden'), 3000);
        }

        function startScanner() {
            if (!isScanning || isProcessing) return;

            const readerElement = document.getElementById('reader');
            if (!readerElement) return;

            updateScanStatus('scanning', 'Caméra active - En attente de scan');

            html5QrCode = new Html5Qrcode("reader");

            const containerHeight = readerElement.clientHeight;
            const containerWidth = readerElement.clientWidth;
            const qrSize = Math.min(containerWidth * 0.6, containerHeight * 0.6, 300);

            const config = {
                fps: 10,
                qrbox: { width: qrSize, height: qrSize },
                aspectRatio: containerWidth / containerHeight
            };

            const qrCodeSuccessCallback = (decodedText) => {
                if (isScanning && decodedText && !isProcessing) {
                    isScanning = false;
                    isProcessing = true;

                    if (html5QrCode && html5QrCode.isScanning) {
                        html5QrCode.stop();
                    }

                    updateScanStatus('processing', 'Traitement en cours...');
                    showResultOverlay('processing', 'Traitement', 'Vérification du pass en cours...');
                    processScan(decodedText);
                }
            };

            html5QrCode.start({ facingMode: "environment" }, config, qrCodeSuccessCallback).catch(err => {
                console.error("Unable to start scanning:", err);
                updateScanStatus('error', 'Erreur caméra');
                showResultOverlay('error', 'Erreur', 'Impossible d\'accéder à la caméra. Vérifiez les permissions.');
                isScanning = true;
                isProcessing = false;
                setTimeout(() => startScanner(), 3000);
            });
        }

        function processScan(uuid) {
            const resultDiv = document.getElementById('scan-result');
            const contentDiv = document.getElementById('result-content');
            if (!resultDiv || !contentDiv) return;

            fetch(scanUrl, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ uuid: uuid })
            })
                .then(response => response.json())
                .then(data => {
                    isProcessing = false;

                    if (data.valid) {
                        updateScanStatus('success', 'Scan validé !');
                        showResultOverlay('success', 'Accès autorisé', data.message, `Titulaire: ${data.pass.holder_name}`);

                        const percentRemaining = (data.pass.remaining_visits / data.pass.allowed_visits) * 100;

                        contentDiv.innerHTML = `
                            <div class="bg-emerald-50 dark:bg-emerald-900/20 border border-emerald-200 dark:border-emerald-800 rounded-xl p-5">
                                <div class="flex items-start gap-4">
                                    <div class="flex-shrink-0 w-11 h-11 bg-emerald-100 dark:bg-emerald-900/50 rounded-full flex items-center justify-center">
                                        <i data-lucide="check-circle-2" class="w-5 h-5 text-emerald-600"></i>
                                    </div>
                                    <div class="flex-1">
                                        <h3 class="font-semibold text-base text-emerald-800 dark:text-emerald-300 mb-3">${data.message}</h3>
                                        <div class="grid grid-cols-2 gap-4 mb-4">
                                            <div>
                                                <p class="text-xs text-gray-400 uppercase tracking-wide">Titulaire</p>
                                                <p class="text-sm font-medium text-gray-900 dark:text-white">${data.pass.holder_name}</p>
                                            </div>
                                            <div>
                                                <p class="text-xs text-gray-400 uppercase tracking-wide">Téléphone</p>
                                                <p class="text-sm font-medium text-gray-900 dark:text-white">${data.pass.phone || 'Non renseigné'}</p>
                                            </div>
                                            <div>
                                                <p class="text-xs text-gray-400 uppercase tracking-wide">Expiration</p>
                                                <p class="text-sm font-medium text-gray-900 dark:text-white">${new Date(data.pass.expiration_date).toLocaleDateString('fr-FR')}</p>
                                            </div>
                                            <div>
                                                <p class="text-xs text-gray-400 uppercase tracking-wide">Statut</p>
                                                <span class="inline-flex items-center px-2 py-0.5 text-xs font-medium rounded-full bg-emerald-100 dark:bg-emerald-900/50 text-emerald-700 dark:text-emerald-300">
                                                    ${data.pass.status === 'actif' ? 'Actif' : 'Utilisé'}
                                                </span>
                                            </div>
                                        </div>
                                        <div class="pt-3 border-t border-emerald-200 dark:border-emerald-800">
                                            <div class="flex justify-between text-sm mb-2">
                                                <span class="text-gray-500 dark:text-gray-400">Visites restantes</span>
                                                <span class="font-semibold text-gray-900 dark:text-white">${data.pass.remaining_visits} / ${data.pass.allowed_visits}</span>
                                            </div>
                                            <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2">
                                                <div class="bg-emerald-500 rounded-full h-2 transition-all duration-500" style="width: ${percentRemaining}%"></div>
                                            </div>
                                        </div>
                                        <div class="mt-4 pt-3 border-t border-emerald-200 dark:border-emerald-800">
                                            <span class="inline-flex items-center gap-1.5 text-sm font-medium ${data.pass.remaining_visits > 0 ? 'text-emerald-600' : 'text-red-600'}">
                                                <i data-lucide="${data.pass.remaining_visits > 0 ? 'door-open' : 'door-closed'}" class="w-4 h-4"></i>
                                                ${data.pass.remaining_visits > 0 ? 'Accès autorisé' : 'Accès refusé - Plus aucune visite'}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        `;
                    } else {
                        updateScanStatus('error', 'Scan invalide');
                        showResultOverlay('error', 'Accès refusé', data.message, data.pass ? `Titulaire: ${data.pass.holder_name}` : 'Pass non trouvé');

                        contentDiv.innerHTML = `
                            <div class="bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-xl p-5">
                                <div class="flex items-start gap-4">
                                    <div class="flex-shrink-0 w-11 h-11 bg-red-100 dark:bg-red-900/50 rounded-full flex items-center justify-center">
                                        <i data-lucide="x-circle" class="w-5 h-5 text-red-600"></i>
                                    </div>
                                    <div class="flex-1">
                                        <h3 class="font-semibold text-base text-red-800 dark:text-red-300 mb-2">${data.message}</h3>
                                        ${data.pass ? `
                                            <p class="text-sm text-gray-600 dark:text-gray-400">Titulaire: ${data.pass.holder_name}</p>
                                            <p class="text-sm text-gray-500 dark:text-gray-500 mt-1">Motif: ${data.reason || 'Accès non autorisé'}</p>
                                        ` : '<p class="text-sm text-gray-500">Pass non trouvé dans le système</p>'}
                                    </div>
                                </div>
                            </div>
                        `;
                    }

                    resultDiv.classList.remove('hidden');
                    refreshIcons();

                    setTimeout(() => {
                        resultDiv.classList.add('hidden');
                        resultOverlay.classList.add('hidden');
                        isScanning = true;
                        isProcessing = false;
                        updateScanStatus('scanning', 'Caméra active - En attente de scan');
                        startScanner();
                    }, 4000);
                })
                .catch(error => {
                    console.error('Error:', error);
                    isProcessing = false;

                    updateScanStatus('error', 'Erreur serveur');
                    showResultOverlay('error', 'Erreur', 'Impossible de contacter le serveur. Veuillez réessayer.');

                    contentDiv.innerHTML = `
                        <div class="bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-xl p-5">
                            <div class="flex items-center gap-3">
                                <i data-lucide="alert-circle" class="w-5 h-5 flex-shrink-0 text-red-600"></i>
                                <div>
                                    <h3 class="font-semibold text-sm text-gray-900 dark:text-white">Erreur de communication</h3>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">Impossible de contacter le serveur. Veuillez réessayer.</p>
                                </div>
                            </div>
                        </div>
                    `;
                    resultDiv.classList.remove('hidden');
                    refreshIcons();

                    setTimeout(() => {
                        resultDiv.classList.add('hidden');
                        resultOverlay.classList.add('hidden');
                        isScanning = true;
                        updateScanStatus('scanning', 'Caméra active - En attente de scan');
                        startScanner();
                    }, 3000);
                });
        }

        document.getElementById('manual-scan-form').addEventListener('submit', function(e) {
            e.preventDefault();
            const uuidInput = document.getElementById('uuid');
            const uuid = uuidInput.value.trim();

            if (uuid) {
                if (html5QrCode && isScanning) {
                    isScanning = false;
                    if (html5QrCode.isScanning) html5QrCode.stop();
                }
                isProcessing = true;
                updateScanStatus('processing', 'Traitement manuel...');
                showResultOverlay('processing', 'Traitement', 'Vérification du pass en cours...');
                processScan(uuid);
                uuidInput.value = '';
            } else {
                showResultOverlay('error', 'Erreur', 'Veuillez saisir un identifiant valide');
                setTimeout(() => resultOverlay.classList.add('hidden'), 2000);
            }
        });

        startScanner();

        window.addEventListener('beforeunload', function() {
            if (html5QrCode && html5QrCode.isScanning) html5QrCode.stop();
        });

        let resizeTimeout;
        window.addEventListener('resize', function() {
            clearTimeout(resizeTimeout);
            resizeTimeout = setTimeout(() => {
                if (html5QrCode && isScanning && html5QrCode.isScanning) {
                    html5QrCode.stop();
                    setTimeout(() => {
                        if (isScanning && !isProcessing) startScanner();
                    }, 500);
                }
            }, 1000);
        });
    </script>
@endsection