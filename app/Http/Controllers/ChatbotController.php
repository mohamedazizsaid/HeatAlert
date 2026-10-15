<?php

namespace App\Http\Controllers;

use App\Services\GeminiChatbotService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ChatbotController extends Controller
{
    protected GeminiChatbotService $chatbotService;

    public function __construct(GeminiChatbotService $chatbotService)
    {
        $this->chatbotService = $chatbotService;
    }

    /**
     * Traite un message utilisateur envoyé au Chatbot IA.
     */
    public function ask(Request $request): JsonResponse
    {
        $request->validate([
            'message' => 'required|string|min:1|max:1500',
            'history' => 'nullable|array',
            'history.*.role' => 'nullable|string|in:user,bot,model,assistant',
            'history.*.text' => 'nullable|string|max:2000',
            'history.*.content' => 'nullable|string|max:2000',
        ]);

        $message = trim((string) $request->input('message'));
        $history = (array) $request->input('history', []);
        $user    = $request->user();

        try {
            $response = $this->chatbotService->chat($message, $history, $user);

            return response()->json([
                'success'     => true,
                'reply'       => $response['reply'],
                'chart'       => $response['chart'],
                'suggestions' => $response['suggestions'],
                'timestamp'   => now()->format('H:i'),
            ]);
        } catch (\Throwable $e) {
            Log::error('Chatbot Controller Error: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
            ]);

            return response()->json([
                'success'     => false,
                'reply'       => "Désolé, une erreur temporaire est survenue lors du traitement de votre demande. Nos services techniques restent à votre écoute.",
                'chart'       => null,
                'suggestions' => ['Statistiques globales', 'Points fraîcheur', 'Conseils canicule'],
                'timestamp'   => now()->format('H:i'),
            ], 500);
        }
    }

    /**
     * Fournit les statistiques et indicateurs rapides pour alimenter l'interface du chatbot.
     */
    public function quickStats(): JsonResponse
    {
        try {
            $knowledge = $this->chatbotService->gatherPlatformKnowledge(auth()->user());

            return response()->json([
                'success' => true,
                'stats'   => $knowledge['stats'],
                'date'    => $knowledge['date'],
            ]);
        } catch (\Throwable $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }
}
