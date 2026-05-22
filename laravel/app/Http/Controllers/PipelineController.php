<?php

namespace App\Http\Controllers;

use App\Models\DevTask;
use App\Models\N8nRun;
use Illuminate\Http\Request;

class PipelineController extends Controller
{
    public function log(Request $request)
    {
        $key = $request->header('X-Internal-Key');
        abort_unless($key === config('reclamaia.internal_api_secret'), 403);

        $validated = $request->validate([
            'task_ref' => 'required|string|max:20',
            'result' => 'required|in:success,failure,skipped',
            'files_modified' => 'nullable|array',
            'error_message' => 'nullable|string',
        ]);

        $task = DevTask::where('task_ref', $validated['task_ref'])->first();

        N8nRun::create([
            'dev_task_id' => $task?->id,
            'result' => $validated['result'],
            'error_message' => $validated['error_message'] ?? null,
            'files_modified' => $validated['files_modified'] ?? [],
            'telegram_notified' => false,
            'started_at' => now()->subMinutes(5),
            'finished_at' => now(),
            'created_at' => now(),
        ]);

        if ($task && $validated['result'] === 'success') {
            $task->update(['status' => 'completed', 'completed_at' => now()]);
        } elseif ($task && $validated['result'] === 'failure') {
            $task->update(['status' => 'failed']);
        }

        return response()->json(['status' => 'logged']);
    }
}
