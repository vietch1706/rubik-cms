<?php

namespace App\Http\Controllers\Logs;

use App\Http\Controllers\Controller;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Request;
use function file;
use function file_exists;
use function preg_match;
use function storage_path;
use function strtoupper;
use function view;

class LogsController extends Controller
{
    private const PAGE_LIMIT = 20;

    public function index()
    {

        $logs = $this->getLogs();
        $logs = $logs->sortByDesc('timestamp');
        $page = request()->get('page', 1);
        $paginator = new LengthAwarePaginator(
            $logs->forPage($page, self::PAGE_LIMIT),
            $logs->count(),
            self::PAGE_LIMIT,
            $page,
            ['path' => request()->url()]
        );
        return view('logs.list', [
            'logs' => $paginator
        ]);
    }

    protected function getLogs($id = null)
    {
        $currentLogId = null;
        $logPath = storage_path('logs/laravel.log');

        if (!file_exists($logPath)) {
            return view('logs', ['logs' => []]);
        }

        $logs = new Collection();
        $logContent = file($logPath);

        foreach ($logContent as $key => $line) {
            if (preg_match('/^\[([^\]]+)\] ([a-zA-Z]+)\.([a-zA-Z]+): (.*)$/', $line, $matches)) {
                $logs->push([
                    'timestamp' => $matches[1],
                    'environment' => strtoupper($matches[2]),
                    'level' => $matches[3],
                    'message' => $matches[4],
                    'context' => $this->parseContext($matches[4])
                ]);
            }
            if ($key != null && $key == $id) {
                $currentLogId = $key;
            }
        }
        if ($currentLogId != null) {
            return $logs[$id];
        }
        return $logs;
    }

    protected function parseContext($message)
    {
        $context = [];

        if (preg_match('/Stack trace:(.*?)(?:\n|$)/s', $message, $matches)) {
            $context['stack_trace'] = array_filter(array_map('trim', explode("\n", $matches[1])));
        }

        if (preg_match('/Exception: (.*?) in (.*?) on line (\d+)/', $message, $matches)) {
            $context['exception'] = [
                'message' => $matches[1],
                'file' => $matches[2],
                'line' => $matches[3]
            ];
        }

        return $context;
    }

    public function destroy(Request $request)
    {

    }

    public function preview($id)
    {
        $log = $this->getLogs($id);
        return view('logs.preview', [
            'id' => $id,
            'log' => $log
        ]);
    }
}
