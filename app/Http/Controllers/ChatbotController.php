<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
class ChatbotController extends Controller
{
    public function ask(Request $request)
    {
        $request->validate([
            'message' => 'required|string',
        ]);
        $userQuestion = $request->input('message');
        $scriptPath = base_path('chatbot/tanya.sh');
        $command = "bash " . escapeshellarg($scriptPath) . " " . escapeshellarg($userQuestion) . " 2>&1";
        $output = shell_exec($command);
        if (empty(trim($output))) {
            return response()->json([
                'status' => 'error',
                'reply' => 'Maaf, chatbot sedang tidak bisa merespon.'
            ]);
        }

        return response()->json([
            'status' => 'success',
            'reply' => trim($output)
        ]);
    }
}
