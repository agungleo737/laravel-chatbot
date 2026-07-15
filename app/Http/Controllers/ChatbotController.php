<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;
use App\Models\ChatSession;
use App\Models\ChatMessage;

class ChatbotController extends Controller
{
    public function ask(Request $request)
    {
        // Terima session_id
        $request->validate([
            'message' => 'required|string',
            'history' => 'nullable|array',
            'session_id' => 'required|exists:chat_sessions,id' 
        ]);

        $userQuestion = $request->input('message');
        $history = $request->input('history', []);
        $sessionId = $request->input('session_id');

        // Simpan pesan ke database
        ChatMessage::create([
            'chat_session_id' => $sessionId,
            'role' => 'user',
            'text' => $userQuestion
        ]);

        // judul sesi
        $session = ChatSession::find($sessionId);
        if ($session->title === 'New Chat') {
            $session->title = substr($userQuestion, 0, 20) . '...';
            $session->save();
        }

        // Baca profil.md
        $profilPath = base_path('chatbot/profil.md');
        $profilText = file_exists($profilPath) ? file_get_contents($profilPath) : '';

        $systemPrompt = "Kamu Twinkle, asisten AI milik Leo Agung Christian.
Aturan wajib:
1. Kamu HANYA menjawab pertanyaan tentang Leo Agung Christian berdasarkan Data Leo di bawah.
2. Untuk pertanyaan di luar Leo (matematika, coding, terjemahan, pengetahuan umum), tolak dengan sopan, jangan dijawab walaupun kamu tahu.
3. Kalau ditanya 'siapa kamu', perkenalkan diri sebagai Twinkle, asisten AI Leo.
4. Yang bertanya adalah pengunjung web, bukan Leo.
5. Jawab singkat (maksimal 3 kalimat), bahasa natural, tanpa format markdown.
6. Sekarang tahun 2026. Jangan mengarang; kalau tidak ada di data, katakan tidak tahu.
Data Leo:
" . $profilText;

        return response()->stream(function () use ($userQuestion, $history, $systemPrompt, $sessionId) {
            $messages = [
                ['role' => 'system', 'content' => $systemPrompt]
            ];

            foreach ($history as $msg) {
                $role = $msg['role'] === 'chatbot' ? 'assistant' : 'user';
                $messages[] = ['role' => $role, 'content' => $msg['text']];
            }

            $messages[] = ['role' => 'user', 'content' => $userQuestion];

            $payload = [
                'model' => 'ornith',
                'messages'=> $messages,
                'stream' => true,
                'keep_alive' => '30m',
                'options' => ['num_predict' => 250, 'temperature' => 0.2]
            ];

            $ch = curl_init('http://localhost:11434/api/chat');
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, false);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
            curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
            $fullBotText = "";

            curl_setopt($ch, CURLOPT_WRITEFUNCTION, function($curl, $data) use (&$fullBotText) {
                $lines = explode("\n", $data);
                foreach($lines as $line) {
                    if (trim($line) === '') continue;
                    $json = json_decode($line, true);

                    if (isset($json['message']['content'])) {
                        $content = $json['message']['content'];
                        echo $content;
                        $fullBotText .= $content;
                        ob_flush();
                        flush();
                    }
                }
                return strlen($data);
            });

            curl_exec($ch);
            curl_close($ch);

            // Simpan jawaban ke database
            if (!empty(trim($fullBotText))) {
                ChatMessage::create([
                    'chat_session_id' => $sessionId,
                    'role' => 'chatbot',
                    'text' => trim($fullBotText)
                ]);
            }

        }, 200, [
              'Cache-Control' => 'no-cache',
              'Content-Type' => 'text/event-stream',
              'X-Accel-Buffering' => 'no',
          ]);
    }
}
