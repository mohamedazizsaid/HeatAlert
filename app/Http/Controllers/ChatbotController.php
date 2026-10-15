<?php

namespace App\Http\Controllers;

use App\Models\ChatbotConversation;
use App\Models\ChatbotMessage;
use App\Services\GeminiChatbotService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class ChatbotController extends Controller
{
    protected GeminiChatbotService $chatbotService;

    public function __construct(GeminiChatbotService $chatbotService)
    {
        $this->chatbotService = $chatbotService;
    }

    /**
     * Obtenir la requête de base pour les conversations de l'utilisateur ou de la session.
     */
    protected function getUserConversationQuery(Request $request)
    {
        $user = $request->user();
        if ($user) {
            return ChatbotConversation::where('user_id', $user->id);
        }

        $sessionId = $request->session()->getId();
        return ChatbotConversation::where('session_id', $sessionId)->whereNull('user_id');
    }

    /**
     * Traite un message utilisateur envoyé au Chatbot IA et l'enregistre dans la discussion.
     */
    public function ask(Request $request): JsonResponse
    {
        $request->validate([
            'message'         => 'required|string|min:1|max:1500',
            'conversation_id' => 'nullable|integer',
            'voice_lang'      => 'nullable|string|in:fr-FR,ar-TN,ar-SA',
            'history'         => 'nullable|array',
            'history.*.role'  => 'nullable|string|in:user,bot,model,assistant',
            'history.*.text'  => 'nullable|string|max:2000',
            'history.*.content'=> 'nullable|string|max:2000',
        ]);

        $message        = trim((string) $request->input('message'));
        $history        = (array) $request->input('history', []);
        $voiceLang      = (string) $request->input('voice_lang', 'fr-FR');
        $user           = $request->user();
        $conversationId = $request->input('conversation_id');
        $sessionId      = $request->session()->getId();

        // Récupérer ou créer la conversation propre à l'utilisateur
        $conversation = null;
        if ($conversationId) {
            $conversation = $this->getUserConversationQuery($request)->find($conversationId);
        }

        if (!$conversation) {
            // Créer une nouvelle discussion avec un titre dérivé du premier message
            $title = Str::limit($message, 42, '...');
            $conversation = ChatbotConversation::create([
                'user_id'    => $user?->id,
                'session_id' => $sessionId,
                'title'      => $title,
            ]);
        } elseif ($conversation->title === 'Nouvelle discussion') {
            $conversation->update([
                'title' => Str::limit($message, 42, '...'),
            ]);
        }

        // Enregistrer le message de l'utilisateur dans l'historique de la discussion
        ChatbotMessage::create([
            'chatbot_conversation_id' => $conversation->id,
            'role'                    => 'user',
            'content'                 => $message,
        ]);

        try {
            $response = $this->chatbotService->chat($message, $history, $user, $voiceLang);

            // Enregistrer la réponse du bot dans l'historique
            ChatbotMessage::create([
                'chatbot_conversation_id' => $conversation->id,
                'role'                    => 'bot',
                'content'                 => $response['reply'],
                'chart_data'              => $response['chart'],
            ]);

            $conversation->touch();

            return response()->json([
                'success'            => true,
                'reply'              => $response['reply'],
                'chart'              => $response['chart'],
                'suggestions'        => $response['suggestions'],
                'timestamp'          => now()->format('H:i'),
                'conversation_id'    => $conversation->id,
                'conversation_title' => $conversation->title,
            ]);
        } catch (\Throwable $e) {
            Log::error('Chatbot Controller Error: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
            ]);

            return response()->json([
                'success'            => false,
                'reply'              => "Désolé, une erreur temporaire est survenue lors du traitement de votre demande. Nos services techniques restent à votre écoute.",
                'chart'              => null,
                'suggestions'        => ['Statistiques globales', 'Points fraîcheur', 'Conseils canicule'],
                'timestamp'          => now()->format('H:i'),
                'conversation_id'    => $conversation->id,
                'conversation_title' => $conversation->title,
            ], 500);
        }
    }

    /**
     * Récupère la liste des discussions de l'utilisateur connecté (ou de sa session).
     */
    public function conversations(Request $request): JsonResponse
    {
        try {
            $conversations = $this->getUserConversationQuery($request)
                ->withCount('messages')
                ->with(['latestMessage'])
                ->orderBy('updated_at', 'desc')
                ->take(30)
                ->get()
                ->map(function (ChatbotConversation $conv) {
                    return [
                        'id'             => $conv->id,
                        'title'          => $conv->title,
                        'messages_count' => $conv->messages_count,
                        'last_message'   => $conv->latestMessage ? Str::limit(strip_tags($conv->latestMessage->content), 55) : '',
                        'updated_at'     => $conv->updated_at->diffForHumans(),
                        'date'           => $conv->updated_at->format('d/m/Y H:i'),
                    ];
                });

            return response()->json([
                'success'       => true,
                'conversations' => $conversations,
            ]);
        } catch (\Throwable $e) {
            Log::error('Chatbot conversations list error: ' . $e->getMessage());
            return response()->json(['success' => false, 'conversations' => []], 500);
        }
    }

    /**
     * Récupère tous les messages d'une discussion spécifique pour la recharger.
     */
    public function getConversation(Request $request, int $id): JsonResponse
    {
        $conversation = $this->getUserConversationQuery($request)
            ->with(['messages'])
            ->find($id);

        if (!$conversation) {
            return response()->json([
                'success' => false,
                'message' => 'Discussion introuvable ou non autorisée.',
            ], 404);
        }

        $messages = $conversation->messages->map(function (ChatbotMessage $msg) {
            return [
                'id'         => $msg->id,
                'role'       => $msg->role,
                'content'    => $msg->content,
                'chart'      => $msg->chart_data,
                'time'       => $msg->created_at->format('H:i'),
                'date'       => $msg->created_at->format('d/m/Y'),
            ];
        });

        return response()->json([
            'success'      => true,
            'conversation' => [
                'id'    => $conversation->id,
                'title' => $conversation->title,
            ],
            'messages'     => $messages,
        ]);
    }

    /**
     * Démarre explicitement une nouvelle discussion.
     */
    public function newConversation(Request $request): JsonResponse
    {
        $user      = $request->user();
        $sessionId = $request->session()->getId();

        $conversation = ChatbotConversation::create([
            'user_id'    => $user?->id,
            'session_id' => $sessionId,
            'title'      => 'Nouvelle discussion',
        ]);

        return response()->json([
            'success'      => true,
            'conversation' => [
                'id'    => $conversation->id,
                'title' => $conversation->title,
            ],
        ]);
    }

    /**
     * Supprime une discussion spécifique de l'utilisateur.
     */
    public function deleteConversation(Request $request, int $id): JsonResponse
    {
        $conversation = $this->getUserConversationQuery($request)->find($id);

        if (!$conversation) {
            return response()->json([
                'success' => false,
                'message' => 'Discussion introuvable.',
            ], 404);
        }

        $conversation->delete();

        return response()->json([
            'success' => true,
            'message' => 'Discussion supprimée avec succès.',
        ]);
    }

    /**
     * Efface toutes les discussions de l'utilisateur.
     */
    public function clearAllConversations(Request $request): JsonResponse
    {
        $this->getUserConversationQuery($request)->delete();

        return response()->json([
            'success' => true,
            'message' => 'Toutes les discussions ont été effacées.',
        ]);
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

    /**
     * Proxy TTS — Google Translate Text-to-Speech (gratuit, supporte l'arabe).
     * Retourne un flux audio MP3 pour le texte fourni dans la langue demandée.
     *
     * Langues supportées : fr (Français), ar (Arabe), ar-SA, ar-EG, ar-TN (→ ar)
     */
    public function tts(Request $request)
    {
        $request->validate([
            'text' => 'required|string|max:500',
            'lang' => 'nullable|string|max:10',
        ]);

        $text = trim((string) $request->input('text', ''));
        $lang = (string) $request->input('lang', 'fr');

        if (empty($text)) {
            return response('', 400);
        }

        // Normaliser la langue pour Google Translate TTS
        // ar-TN et ar-SA → 'ar' (Google Translate ne supporte que 'ar')
        $googleLang = match (true) {
            str_starts_with($lang, 'ar') => 'ar',
            str_starts_with($lang, 'fr') => 'fr',
            default                      => 'fr',
        };

        // Google Translate TTS : max ~200 caractères par requête
        $text = mb_substr($text, 0, 200);

        $url = 'https://translate.google.com/translate_tts?' . http_build_query([
            'ie'       => 'UTF-8',
            'q'        => $text,
            'tl'       => $googleLang,
            'client'   => 'tw-ob',
            'ttsspeed' => '0.9',
        ]);

        try {
            $response = Http::withHeaders([
                'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36',
                'Referer'    => 'https://translate.google.com/',
                'Accept'     => 'audio/mpeg, audio/*, */*',
            ])
            ->timeout(10)
            ->get($url);

            if (!$response->successful()) {
                Log::warning('HeatBot TTS: Google Translate returned status ' . $response->status());
                return response('TTS unavailable', 503);
            }

            return response($response->body(), 200)
                ->header('Content-Type', 'audio/mpeg')
                ->header('Cache-Control', 'public, max-age=3600')
                ->header('Access-Control-Allow-Origin', '*');

        } catch (\Throwable $e) {
            Log::error('HeatBot TTS proxy error: ' . $e->getMessage());
            return response('TTS error', 500);
        }
    }
}
