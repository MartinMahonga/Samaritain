@extends('layouts.dashboard')

@section('title', 'Scanner un Pass')

@section('content')
    <div class="max-w-4xl mx-auto">
        <!-- En-tête -->
        <div class="mb-6">
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Scanner un QR Code</h1>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Utilisez votre caméra pour scanner le QR Code du pass
            </p>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 overflow-hidden">
            <div class="p-6">
                <!-- Zone de scan avec overlay - Pleine hauteur -->
                <div class="mb-8">
                    <div class="relative" style="height: 350px; width: 100%;">
                        <div id="reader" class="w-full h-full rounded-lg overflow-hidden"></div>
                        
                        <!-- Overlay avec indicateur de scan -->
                        <div id="scan-overlay" class="absolute inset-0 pointer-events-none">
                            <!-- Coin supérieur gauche -->
                            <div class="absolute top-0 left-0 w-10 h-10 border-t-4 border-l-4 border-blue-500 rounded-tl-lg"></div>
                            <!-- Coin supérieur droit -->
                            <div class="absolute top-0 right-0 w-10 h-10 border-t-4 border-r-4 border-blue-500 rounded-tr-lg"></div>
                            <!-- Coin inférieur gauche -->
                            <div class="absolute bottom-0 left-0 w-10 h-10 border-b-4 border-l-4 border-blue-500 rounded-bl-lg"></div>
                            <!-- Coin inférieur droit -->
                            <div class="absolute bottom-0 right-0 w-10 h-10 border-b-4 border-r-4 border-blue-500 rounded-br-lg"></div>
                            
                            <!-- Ligne de scan animée -->
                            <div id="scan-line" class="absolute left-12 right-12 h-0.5 bg-blue-500 shadow-lg shadow-blue-500/50 animate-scan"></div>
                            
                            <!-- Zone de focus pour le QR Code -->
                            <div class="absolute inset-0 flex items-center justify-center pointer-events-none">
                            
                            </div>
                            
                            <!-- Statut du scan -->
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

                        <!-- Overlay de succès/erreur -->
                        <div id="scan-result-overlay" class="absolute inset-0 flex items-center justify-center bg-black/70 backdrop-blur-sm rounded-lg hidden transition-opacity duration-300 pointer-events-none">
                            <div id="scan-result-content" class="text-center text-white p-6 max-w-sm">
                                <!-- Contenu dynamique -->
                            </div>
                        </div>
                    </div>
                    <p class="text-center text-xs text-gray-400 dark:text-gray-500 mt-3">
                        <svg class="w-3 h-3 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" />
                            <circle cx="12" cy="13" r="3" />
                        </svg>
                        Positionnez le QR Code dans le cadre
                    </p>
                </div>

                <!-- Divider -->
                <div class="relative my-8">
                    <div class="absolute inset-0 flex items-center">
                        <div class="w-full border-t border-gray-200 dark:border-gray-700"></div>
                    </div>
                    <div class="relative flex justify-center text-sm">
                        <span class="px-3 bg-white dark:bg-gray-800 text-gray-500 dark:text-gray-400">ou</span>
                    </div>
                </div>

                <!-- Zone de saisie manuelle -->
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path d="M12 6v6l4 2" stroke="currentColor" stroke-width="2" />
                            <circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="2" />
                        </svg>
                        Saisissez l'UUID manuellement
                    </h3>
                    <form id="manual-scan-form" class="space-y-4">
                        @csrf
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <rect x="3" y="3" width="18" height="18" rx="2" stroke="currentColor" stroke-width="2" />
                                    <line x1="3" y1="9" x2="21" y2="9" stroke="currentColor" stroke-width="2" />
                                    <line x1="9" y1="21" x2="9" y2="9" stroke="currentColor" stroke-width="2" />
                                </svg>
                            </div>
                            <input type="text" id="uuid" name="uuid" placeholder="ex: abc123-def456-ghi789"
                                class="w-full pl-10 pr-4 py-3 text-sm border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white transition placeholder-gray-400">
                        </div>
                        <x-btn class="w-full" type="submit">
                            <x-slot:prefix>
                                <i data-lucide="circle-check"></i>
                            </x-slot:prefix>
                            Valider le scan
                        </x-btn>
                    </form>
                </div>

                <!-- Résultat du scan (zone de notification) -->
                <div id="scan-result" class="mt-6 hidden">
                    <div id="result-content" class="rounded-lg"></div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://unpkg.com/html5-qrcode@2.3.8/html5-qrcode.min.js"></script>
    
    <style>
        /* Animation de la ligne de scan */
        @keyframes scan {
            0% { top: 8%; }
            50% { top: 92%; }
            100% { top: 8%; }
        }
        
        .animate-scan {
            animation: scan 2.5s ease-in-out infinite;
        }
        
        /* Effet de glow pour les coins */
        #scan-overlay .border-blue-500 {
            box-shadow: 0 0 10px rgba(59, 130, 246, 0.5);
        }
        
        /* Transition pour les résultats */
        #scan-result-overlay {
            transition: opacity 0.3s ease-in-out;
        }
        
        /* Ajustement pour le lecteur */
        #reader video {
            object-fit: cover !important;
            width: 100% !important;
            height: 100% !important;
        }
        
        #reader {
            background: #000;
        }
        
        /* Zone de focus plus visible */
        .w-64.h-64.border-2 {
            box-shadow: 0 0 30px rgba(59, 130, 246, 0.2);
        }
    </style>

    <script>
        let html5QrCode;
        let isScanning = true;
        let isProcessing = false;

        // Configuration CSRF
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content || '{{ csrf_token() }}';
        const scanUrl = '{{ route('scan.process') }}';

        // Éléments DOM
        const scanOverlay = document.getElementById('scan-overlay');
        const scanStatus = document.getElementById('scan-status');
        const scanStatusText = document.getElementById('scan-status-text');
        const scanIndicator = document.getElementById('scan-indicator');
        const scanLine = document.getElementById('scan-line');
        const resultOverlay = document.getElementById('scan-result-overlay');
        const resultContent = document.getElementById('scan-result-content');

        function updateScanStatus(status, message) {
            const statusColors = {
                scanning: 'bg-green-500',
                processing: 'bg-yellow-500',
                success: 'bg-emerald-500',
                error: 'bg-red-500',
                idle: 'bg-gray-500'
            };
            
            const indicatorDot = scanIndicator.querySelector('.relative');
            const pingDot = scanIndicator.querySelector('.animate-ping');
            
            if (indicatorDot) {
                indicatorDot.className = `relative inline-flex rounded-full h-3 w-3 ${statusColors[status] || 'bg-blue-500'}`;
            }
            
            if (scanStatusText) {
                scanStatusText.textContent = message;
            }
            
            // Changer la couleur de la ligne de scan
            if (scanLine) {
                const colors = {
                    scanning: 'bg-blue-500 shadow-blue-500/50',
                    processing: 'bg-yellow-500 shadow-yellow-500/50',
                    success: 'bg-emerald-500 shadow-emerald-500/50',
                    error: 'bg-red-500 shadow-red-500/50',
                    idle: 'bg-gray-500 shadow-gray-500/50'
                };
                scanLine.className = `absolute left-12 right-12 h-0.5 ${colors[status] || 'bg-blue-500 shadow-blue-500/50'} animate-scan`;
            }
        }

        function showResultOverlay(type, title, message, details = null) {
            const icons = {
                success: `
                    <div class="w-16 h-16 mx-auto mb-4 bg-emerald-500/20 rounded-full flex items-center justify-center">
                        <svg class="w-8 h-8 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" stroke="currentColor" stroke-width="2"/>
                        </svg>
                    </div>
                `,
                error: `
                    <div class="w-16 h-16 mx-auto mb-4 bg-red-500/20 rounded-full flex items-center justify-center">
                        <svg class="w-8 h-8 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="2"/>
                            <line x1="18" y1="6" x2="6" y2="18" stroke="currentColor" stroke-width="2"/>
                        </svg>
                    </div>
                `,
                processing: `
                    <div class="w-16 h-16 mx-auto mb-4 bg-yellow-500/20 rounded-full flex items-center justify-center">
                        <div class="animate-spin rounded-full h-8 w-8 border-4 border-yellow-400 border-t-transparent"></div>
                    </div>
                `
            };

            const colors = {
                success: 'text-emerald-400',
                error: 'text-red-400',
                processing: 'text-yellow-400'
            };

            resultContent.innerHTML = `
                ${icons[type] || ''}
                <h3 class="text-xl font-bold ${colors[type] || 'text-white'} mb-2">${title}</h3>
                <p class="text-gray-300 mb-2">${message}</p>
                ${details ? `<p class="text-sm text-gray-400">${details}</p>` : ''}
            `;

            resultOverlay.classList.remove('hidden');
            setTimeout(() => {
                resultOverlay.classList.add('hidden');
            }, 3000);
        }

        function startScanner() {
            if (!isScanning || isProcessing) return;

            const readerElement = document.getElementById('reader');
            if (!readerElement) return;

            updateScanStatus('scanning', 'Caméra active - En attente de scan');

            html5QrCode = new Html5Qrcode("reader");
            
            // Configuration avec dimensions adaptées à la hauteur du conteneur
            const containerHeight = readerElement.clientHeight;
            const containerWidth = readerElement.clientWidth;
            const qrSize = Math.min(containerWidth * 0.6, containerHeight * 0.6, 300);
            
            const config = {
                fps: 10,
                qrbox: {
                    width: qrSize,
                    height: qrSize
                },
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

            html5QrCode.start({
                facingMode: "environment"
            }, config, qrCodeSuccessCallback).catch(err => {
                console.error("Unable to start scanning:", err);
                updateScanStatus('error', 'Erreur caméra');
                showResultOverlay('error', 'Erreur', 'Impossible d\'accéder à la caméra. Vérifiez les permissions.');
                isScanning = true;
                isProcessing = false;
                
                setTimeout(() => {
                    startScanner();
                }, 3000);
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
                    body: JSON.stringify({
                        uuid: uuid
                    })
                })
                .then(response => response.json())
                .then(data => {
                    isProcessing = false;
                    
                    if (data.valid) {
                        // Pass valide
                        updateScanStatus('success', 'Scan validé !');
                        showResultOverlay('success', 'Accès autorisé', data.message, 
                            `Titulaire: ${data.pass.holder_name}`);
                        
                        const percentRemaining = (data.pass.remaining_visits / data.pass.allowed_visits) * 100;
                        const statusColor = data.pass.remaining_visits > 0 ? 'emerald' : 'red';

                        contentDiv.innerHTML = `
                            <div class="bg-emerald-50 dark:bg-emerald-900/20 border border-emerald-200 dark:border-emerald-800 rounded-lg p-5">
                                <div class="flex items-start gap-4">
                                    <div class="flex-shrink-0 w-12 h-12 bg-emerald-100 dark:bg-emerald-900/50 rounded-full flex items-center justify-center">
                                        <svg class="w-6 h-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" stroke="currentColor" stroke-width="2"/>
                                        </svg>
                                    </div>
                                    <div class="flex-1">
                                        <h3 class="font-bold text-lg text-emerald-800 dark:text-emerald-300 mb-3">✓ ${data.message}</h3>
                                        <div class="grid grid-cols-2 gap-4 mb-4">
                                            <div>
                                                <p class="text-xs text-gray-500 uppercase tracking-wider">Titulaire</p>
                                                <p class="font-medium text-gray-900 dark:text-white">${data.pass.holder_name}</p>
                                            </div>
                                            <div>
                                                <p class="text-xs text-gray-500 uppercase tracking-wider">Téléphone</p>
                                                <p class="font-medium text-gray-900 dark:text-white">${data.pass.phone || 'Non renseigné'}</p>
                                            </div>
                                            <div>
                                                <p class="text-xs text-gray-500 uppercase tracking-wider">Expiration</p>
                                                <p class="font-medium text-gray-900 dark:text-white">${new Date(data.pass.expiration_date).toLocaleDateString('fr-FR')}</p>
                                            </div>
                                            <div>
                                                <p class="text-xs text-gray-500 uppercase tracking-wider">Statut</p>
                                                <span class="inline-flex items-center gap-1 px-2 py-1 text-xs font-medium rounded-full bg-emerald-100 dark:bg-emerald-900/50 text-emerald-700 dark:text-emerald-300">
                                                    ${data.pass.status === 'actif' ? 'Actif' : 'Utilisé'}
                                                </span>
                                            </div>
                                        </div>
                                        <div class="pt-3 border-t border-emerald-200 dark:border-emerald-800">
                                            <div class="flex justify-between text-sm mb-2">
                                                <span class="text-gray-600">Visites restantes</span>
                                                <span class="font-bold">${data.pass.remaining_visits} / ${data.pass.allowed_visits}</span>
                                            </div>
                                            <div class="w-full bg-gray-200 rounded-full h-2">
                                                <div class="bg-emerald-500 rounded-full h-2 transition-all duration-500" style="width: ${percentRemaining}%"></div>
                                            </div>
                                        </div>
                                        <div class="mt-4 pt-3 border-t border-emerald-200 dark:border-emerald-800">
                                            <span class="text-sm font-medium ${data.pass.remaining_visits > 0 ? 'text-emerald-600' : 'text-red-600'}">
                                                ${data.pass.remaining_visits > 0 ? 'Accès autorisé' : 'Accès refusé - Plus aucune visite'}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        `;
                    } else {
                        // Pass invalide
                        updateScanStatus('error', '✗ Scan invalide');
                        showResultOverlay('error', '✗ Accès refusé', data.message,
                            data.pass ? `Titulaire: ${data.pass.holder_name}` : 'Pass non trouvé');

                        contentDiv.innerHTML = `
                            <div class="bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg p-5">
                                <div class="flex items-start gap-4">
                                    <div class="flex-shrink-0 w-12 h-12 bg-red-100 dark:bg-red-900/50 rounded-full flex items-center justify-center">
                                        <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="2"/>
                                            <line x1="18" y1="6" x2="6" y2="18" stroke="currentColor" stroke-width="2"/>
                                        </svg>
                                    </div>
                                    <div class="flex-1">
                                        <h3 class="font-bold text-lg text-red-800 dark:text-red-300 mb-2">✗ ${data.message}</h3>
                                        ${data.pass ? `
                                            <p class="text-sm text-gray-600">Titulaire: ${data.pass.holder_name}</p>
                                            <p class="text-sm text-gray-500 mt-1">Motif: ${data.reason || 'Accès non autorisé'}</p>
                                        ` : '<p class="text-sm">Pass non trouvé dans le système</p>'}
                                    </div>
                                </div>
                            </div>
                        `;
                    }

                    resultDiv.classList.remove('hidden');
                    
                    // Réinitialiser après 4 secondes
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
                        <div class="bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg p-5">
                            <div class="flex items-center gap-3">
                                <svg class="w-5 h-5 flex-shrink-0 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="2"/>
                                    <line x1="12" y1="8" x2="12" y2="12" stroke="currentColor" stroke-width="2"/>
                                    <line x1="12" y1="16" x2="12.01" y2="16" stroke="currentColor" stroke-width="2"/>
                                </svg>
                                <div>
                                    <h3 class="font-semibold">Erreur de communication</h3>
                                    <p class="text-sm">Impossible de contacter le serveur. Veuillez réessayer.</p>
                                </div>
                            </div>
                        </div>
                    `;
                    resultDiv.classList.remove('hidden');

                    setTimeout(() => {
                        resultDiv.classList.add('hidden');
                        resultOverlay.classList.add('hidden');
                        isScanning = true;
                        updateScanStatus('scanning', 'Caméra active - En attente de scan');
                        startScanner();
                    }, 3000);
                });
        }

        // Formulaire manuel
        document.getElementById('manual-scan-form').addEventListener('submit', function(e) {
            e.preventDefault();
            const uuidInput = document.getElementById('uuid');
            const uuid = uuidInput.value.trim();
            
            if (uuid) {
                if (html5QrCode && isScanning) {
                    isScanning = false;
                    if (html5QrCode.isScanning) {
                        html5QrCode.stop();
                    }
                }
                isProcessing = true;
                updateScanStatus('processing', 'Traitement manuel...');
                showResultOverlay('processing', 'Traitement', 'Vérification du pass en cours...');
                processScan(uuid);
                uuidInput.value = '';
            } else {
                showResultOverlay('error', 'Erreur', 'Veuillez saisir un UUID valide');
                setTimeout(() => {
                    resultOverlay.classList.add('hidden');
                }, 2000);
            }
        });

        // Démarrer le scanner
        startScanner();

        // Nettoyage
        window.addEventListener('beforeunload', function() {
            if (html5QrCode && html5QrCode.isScanning) {
                html5QrCode.stop();
            }
        });

        // Redémarrer le scanner si la fenêtre est redimensionnée
        let resizeTimeout;
        window.addEventListener('resize', function() {
            clearTimeout(resizeTimeout);
            resizeTimeout = setTimeout(() => {
                if (html5QrCode && isScanning) {
                    // Recalculer la taille du QR box
                    const readerElement = document.getElementById('reader');
                    if (readerElement) {
                        const containerHeight = readerElement.clientHeight;
                        const containerWidth = readerElement.clientWidth;
                        const qrSize = Math.min(containerWidth * 0.6, containerHeight * 0.6, 300);
                        
                        // Mettre à jour la configuration
                        // Note: html5-qrcode ne permet pas de mettre à jour facilement, on redémarre
                        if (html5QrCode.isScanning) {
                            html5QrCode.stop();
                            setTimeout(() => {
                                if (isScanning && !isProcessing) {
                                    startScanner();
                                }
                            }, 500);
                        }
                    }
                }
            }, 1000);
        });
    </script>
@endsection