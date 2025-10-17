<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Carbon\Carbon;

class LogController extends Controller
{
    /**
     * Dados sensíveis que devem ser mascarados nos logs
     */
    private $sensitivePatterns = [
        // Emails
        '/([a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,})/' => '***EMAIL***',
        // Senhas (password, senha, pass)
        '/(password|senha|pass)["\']?\s*[:=]\s*["\']?([^"\'\s,}]+)/' => '$1: ***SENHA***',
        // Tokens e chaves
        '/(token|key|secret|api_key)["\']?\s*[:=]\s*["\']?([^"\'\s,}]+)/' => '$1: ***TOKEN***',
        // CPFs (formato XXX.XXX.XXX-XX)
        '/(\d{3}\.?\d{3}\.?\d{3}-?\d{2})/' => '***CPF***',
        // IPs privados completos (manter apenas primeiros octetos)
        '/(\b(?:10|172|192)\.)(\d{1,3}\.)(\d{1,3}\.)(\d{1,3}\b)/' => '$1***.$3***',
        // Caminhos completos do sistema
        '/(\/[a-zA-Z0-9\/_-]*\/[a-zA-Z0-9\/_-]*\/[a-zA-Z0-9\/_-]*)(\/[a-zA-Z0-9\/_-]+)/' => '$1/***',
    ];

    /**
     * Construtor - sem middleware de autenticação para melhorar o fluxo
     */
    public function __construct()
    {
        // Removido middleware de autenticação para facilitar acesso aos logs
    }

    /**
     * Exibir logs com filtros e paginação
     */
    public function index(Request $request)
    {
        $logPath = storage_path('logs/laravel.log');
        
        if (!File::exists($logPath)) {
            return response()->json(['error' => 'Arquivo de log não encontrado'], 404);
        }

        // Parâmetros de filtro
        $level = $request->get('level', 'all'); // all, error, warning, info, debug
        $date = $request->get('date'); // YYYY-MM-DD
        $search = $request->get('search'); // termo de busca
        $lines = (int) $request->get('lines', 100); // número de linhas
        $format = $request->get('format', 'web'); // web, json, raw

        // Limitar número de linhas para evitar sobrecarga
        $lines = min($lines, 1000);

        try {
            $logContent = File::get($logPath);
            $logLines = explode("\n", $logContent);
            
            // Filtrar linhas vazias
            $logLines = array_filter($logLines, function($line) {
                return !empty(trim($line));
            });

            // Aplicar filtros
            $filteredLines = $this->filterLogs($logLines, $level, $date, $search);
            
            // Pegar as últimas N linhas
            $recentLines = array_slice($filteredLines, -$lines);

            // Limpar dados sensíveis
            $cleanLines = array_map([$this, 'sanitizeLine'], $recentLines);

            // Log da visualização (simplificado sem autenticação)
            Log::info('Logs visualizados', [
                'filters' => [
                    'level' => $level,
                    'date' => $date,
                    'search' => $search,
                    'lines' => $lines
                ],
                'ip' => $request->ip(),
                'user_agent' => $request->userAgent()
            ]);

            // Retornar baseado no formato solicitado
            switch ($format) {
                case 'json':
                    return response()->json([
                        'logs' => $cleanLines,
                        'total_lines' => count($filteredLines),
                        'displayed_lines' => count($cleanLines),
                        'filters_applied' => [
                            'level' => $level,
                            'date' => $date,
                            'search' => $search
                        ]
                    ]);

                case 'raw':
                    return response(implode("\n", $cleanLines), 200, [
            'Content-Type' => 'text/plain',
                        'Content-Disposition' => 'inline; filename="laravel_filtered.log"',
        ]);

                default: // web
                    return view('logs.index', [
                        'logs' => $cleanLines,
                        'totalLines' => count($filteredLines),
                        'displayedLines' => count($cleanLines),
                        'currentFilters' => [
                            'level' => $level,
                            'date' => $date,
                            'search' => $search,
                            'lines' => $lines
                        ]
                    ]);
            }

        } catch (\Exception $e) {
            Log::error('Erro ao ler logs', [
                'error' => $e->getMessage(),
                'ip' => $request->ip()
            ]);
            
            return response()->json(['error' => 'Erro interno do servidor'], 500);
        }
    }

    /**
     * Filtrar logs baseado nos parâmetros
     */
    private function filterLogs($lines, $level, $date, $search)
    {
        return array_filter($lines, function($line) use ($level, $date, $search) {
            // Filtro por nível
            if ($level !== 'all') {
                $levelPattern = '/\.' . strtoupper($level) . ':/';
                if (!preg_match($levelPattern, $line)) {
                    return false;
                }
            }

            // Filtro por data
            if ($date) {
                $datePattern = '/\[' . $date . '/';
                if (!preg_match($datePattern, $line)) {
                    return false;
                }
            }

            // Filtro por termo de busca (case insensitive)
            if ($search) {
                if (stripos($line, $search) === false) {
                    return false;
                }
            }

            return true;
        });
    }

    /**
     * Limpar dados sensíveis de uma linha de log
     */
    private function sanitizeLine($line)
    {
        foreach ($this->sensitivePatterns as $pattern => $replacement) {
            $line = preg_replace($pattern, $replacement, $line);
        }
        
        return $line;
    }

    /**
     * Endpoint para estatísticas dos logs
     */
    public function stats(Request $request)
    {
        $logPath = storage_path('logs/laravel.log');
        
        if (!File::exists($logPath)) {
            return response()->json(['error' => 'Arquivo de log não encontrado'], 404);
        }

        try {
            $logContent = File::get($logPath);
            $logLines = explode("\n", $logContent);
            
            $stats = [
                'total_lines' => count($logLines),
                'file_size' => File::size($logPath),
                'file_size_human' => $this->formatBytes(File::size($logPath)),
                'last_modified' => Carbon::createFromTimestamp(File::lastModified($logPath))->format('Y-m-d H:i:s'),
                'levels' => [
                    'error' => 0,
                    'warning' => 0,
                    'info' => 0,
                    'debug' => 0
                ]
            ];

            // Contar por nível
            foreach ($logLines as $line) {
                if (strpos($line, '.ERROR:') !== false) {
                    $stats['levels']['error']++;
                } elseif (strpos($line, '.WARNING:') !== false) {
                    $stats['levels']['warning']++;
                } elseif (strpos($line, '.INFO:') !== false) {
                    $stats['levels']['info']++;
                } elseif (strpos($line, '.DEBUG:') !== false) {
                    $stats['levels']['debug']++;
                }
            }

            return response()->json($stats);

        } catch (\Exception $e) {
            return response()->json(['error' => 'Erro ao processar estatísticas'], 500);
        }
    }

    /**
     * Limpar logs antigos (sem verificação de permissão)
     */
    public function clear(Request $request)
    {
        $logPath = storage_path('logs/laravel.log');
        
        try {
            // Fazer backup antes de limpar
            $backupPath = storage_path('logs/laravel_backup_' . date('Y-m-d_H-i-s') . '.log');
            File::copy($logPath, $backupPath);
            
            // Limpar o arquivo
            File::put($logPath, '');
            
            Log::info('Logs limpos', [
                'backup_created' => $backupPath,
                'ip' => $request->ip()
            ]);

            return response()->json([
                'message' => 'Logs limpos com sucesso',
                'backup_created' => basename($backupPath)
            ]);

        } catch (\Exception $e) {
            Log::error('Erro ao limpar logs', [
                'error' => $e->getMessage(),
                'ip' => $request->ip()
            ]);
            
            return response()->json(['error' => 'Erro ao limpar logs'], 500);
        }
    }

    /**
     * Converter bytes para formato legível
     */
    private function formatBytes($size, $precision = 2)
    {
        $units = array('B', 'KB', 'MB', 'GB', 'TB');
        
        for ($i = 0; $size > 1024 && $i < count($units) - 1; $i++) {
            $size /= 1024;
        }
        
        return round($size, $precision) . ' ' . $units[$i];
    }
}
