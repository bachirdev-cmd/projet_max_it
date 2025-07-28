<?php

namespace App\Service;

class CniApiService {
    private const API_BASE_URL = 'https://app-daff-project.onrender.com';
    private static ?CniApiService $instance = null;
    private bool $mockMode = false;

    public static function getInstance(): self {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Active le mode simulation pour les tests
     */
    public function enableMockMode(): void {
        $this->mockMode = true;
    }

    /**
     * Données de test pour le mode simulation
     */
    private function getMockData(string $cni): array|false {
        $mockData = [
            '1234567890123' => [
                'id' => 1,
                'nom' => 'DIALLO',
                'prenom' => 'Amadou',
                'date_naissance' => '1990-01-15',
                'lieu_naissance' => 'Dakar',
                'cni' => '1234567890123',
                'cni_recto_url' => 'https://via.placeholder.com/400x250/orange/white?text=CNI+RECTO',
                'cni_verso_url' => 'https://via.placeholder.com/400x250/gray/white?text=CNI+VERSO',
                'created_at' => '2024-01-01T00:00:00Z'
            ],
            '9876543210123' => [
                'id' => 2,
                'nom' => 'FALL',
                'prenom' => 'Fatou',
                'date_naissance' => '1985-06-22',
                'lieu_naissance' => 'Saint-Louis',
                'cni' => '9876543210123',
                'cni_recto_url' => 'https://via.placeholder.com/400x250/blue/white?text=CNI+RECTO',
                'cni_verso_url' => 'https://via.placeholder.com/400x250/green/white?text=CNI+VERSO',
                'created_at' => '2024-01-01T00:00:00Z'
            ]
        ];

        return $mockData[$cni] ?? false;
    }

    /**
     * Vérifie l'authenticité d'une CNI via l'API
     * @param string $cni Le numéro de CNI à vérifier
     * @return array|false Données du citoyen ou false si non trouvé
     */
    public function verifyCni(string $cni): array|false {
        // Mode simulation pour les tests
        if ($this->mockMode) {
            error_log("Mode simulation activé pour CNI: $cni");
            $mockData = $this->getMockData($cni);
            if ($mockData) {
                error_log("CNI simulée trouvée: " . print_r($mockData, true));
            } else {
                error_log("CNI simulée non trouvée");
            }
            return $mockData;
        }

        try {
            $url = self::API_BASE_URL . '/citoyen/' . urlencode($cni);
            
            error_log("Appel API CNI: $url");
            
            // Utilisation de cURL pour plus de contrôle
            $ch = curl_init();
            curl_setopt_array($ch, [
                CURLOPT_URL => $url,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_TIMEOUT => 10,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTPHEADER => [
                    'Content-Type: application/json',
                    'Accept: application/json',
                    'User-Agent: MaxIT-App/1.0'
                ],
                CURLOPT_SSL_VERIFYPEER => true,
                CURLOPT_SSL_VERIFYHOST => 2
            ]);

            $response = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            $error = curl_error($ch);
            curl_close($ch);

            if ($response === false || !empty($error)) {
                error_log("Erreur cURL: $error");
                return false;
            }

            error_log("Code HTTP: $httpCode");
            error_log("Réponse brute: " . substr($response, 0, 200) . "...");

            if ($httpCode === 404) {
                error_log("CNI non trouvée: $cni");
                return false;
            }
            
            // Vérifier si la réponse contient du HTML (erreur)
            if (stripos($response, '<html') !== false || stripos($response, 'Fatal error') !== false) {
                error_log("Réponse HTML détectée - erreur serveur");
                return false;
            }

            // Décodage de la réponse JSON
            $responseData = json_decode($response, true);
            
            if (json_last_error() !== JSON_ERROR_NONE) {
                error_log("Erreur de décodage JSON: " . json_last_error_msg());
                error_log("Réponse complète: $response");
                return false;
            }

            // Vérifier la structure de réponse de votre API
            if (isset($responseData['status']) && $responseData['status'] === 'ERROR') {
                error_log("CNI non trouvée selon l'API: " . ($responseData['message'] ?? 'Erreur inconnue'));
                return false;
            }

            // L'API retourne 200/201 même pour les erreurs, donc on vérifie d'abord le status
            if (!in_array($httpCode, [200, 201])) {
                error_log("Code de réponse HTTP inattendu: $httpCode");
                return false;
            }

            // Extraire les données du citoyen
            $data = $responseData['data'] ?? $responseData;
            
            if (!$data || !isset($data['cni'])) {
                error_log("Données CNI manquantes dans la réponse");
                return false;
            }

            error_log("CNI vérifiée avec succès: " . print_r($data, true));
            return $data;

        } catch (\Exception $e) {
            error_log("Exception lors de la vérification CNI: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Extrait le code de réponse HTTP des headers
     */
    private function getHttpResponseCode(array $headers): int {
        $status_line = $headers[0] ?? '';
        if (preg_match('/HTTP\/\d\.\d\s+(\d+)/', $status_line, $matches)) {
            return (int) $matches[1];
        }
        return 0;
    }

    /**
     * Télécharge et sauvegarde une image depuis une URL
     * @param string $imageUrl URL de l'image
     * @param string $filename Nom du fichier de destination
     * @return string|false Le chemin relatif du fichier sauvegardé ou false
     */
    public function downloadImage(string $imageUrl, string $filename): string|false {
        try {
            $uploadDir = __DIR__ . '/../../public/uploads/cni/';
            
            // Créer le dossier s'il n'existe pas
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }

            $imagePath = $uploadDir . $filename;
            
            // Télécharger l'image
            $imageData = file_get_contents($imageUrl);
            
            if ($imageData === false) {
                return false;
            }

            // Sauvegarder l'image
            if (file_put_contents($imagePath, $imageData) === false) {
                return false;
            }

            return 'uploads/cni/' . $filename;
            
        } catch (\Exception $e) {
            error_log("Erreur lors du téléchargement d'image: " . $e->getMessage());
            return false;
        }
    }
}
