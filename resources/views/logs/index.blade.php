<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Visualizador de Logs - CNF</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f5f5f5;
            color: #333;
        }
        
        .container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 20px;
        }
        
        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 20px;
            border-radius: 10px;
            margin-bottom: 20px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        
        .header h1 {
            margin-bottom: 10px;
        }
        
        .header .info {
            opacity: 0.9;
            font-size: 14px;
        }
        
        .filters {
            background: white;
            padding: 20px;
            border-radius: 10px;
            margin-bottom: 20px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        
        .filters h3 {
            margin-bottom: 15px;
            color: #333;
        }
        
        .filter-row {
            display: flex;
            gap: 15px;
            flex-wrap: wrap;
            align-items: end;
        }
        
        .filter-group {
            display: flex;
            flex-direction: column;
            min-width: 150px;
        }
        
        .filter-group label {
            font-weight: 500;
            margin-bottom: 5px;
            color: #555;
        }
        
        .filter-group input,
        .filter-group select {
            padding: 8px 12px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 14px;
        }
        
        .btn {
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 14px;
            font-weight: 500;
            text-decoration: none;
            display: inline-block;
            text-align: center;
            transition: all 0.3s ease;
        }
        
        .btn-primary {
            background: #667eea;
            color: white;
        }
        
        .btn-primary:hover {
            background: #5a6fd8;
            transform: translateY(-1px);
        }
        
        .btn-danger {
            background: #dc3545;
            color: white;
        }
        
        .btn-danger:hover {
            background: #c82333;
        }
        
        .btn-secondary {
            background: #6c757d;
            color: white;
        }
        
        .btn-secondary:hover {
            background: #5a6268;
        }
        
        .stats {
            display: flex;
            gap: 20px;
            margin-bottom: 20px;
            flex-wrap: wrap;
        }
        
        .stat-card {
            background: white;
            padding: 15px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            flex: 1;
            min-width: 200px;
        }
        
        .stat-card h4 {
            color: #666;
            font-size: 14px;
            margin-bottom: 5px;
        }
        
        .stat-card .value {
            font-size: 24px;
            font-weight: bold;
            color: #333;
        }
        
        .logs-container {
            background: white;
            border-radius: 10px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }
        
        .logs-header {
            background: #f8f9fa;
            padding: 15px 20px;
            border-bottom: 1px solid #dee2e6;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .logs-content {
            max-height: 600px;
            overflow-y: auto;
            font-family: 'Courier New', monospace;
            font-size: 13px;
            line-height: 1.4;
        }
        
        .log-line {
            padding: 8px 20px;
            border-bottom: 1px solid #f0f0f0;
            white-space: pre-wrap;
            word-break: break-all;
        }
        
        .log-line:hover {
            background-color: #f8f9fa;
        }
        
        .log-line.error {
            background-color: #fff5f5;
            border-left: 4px solid #dc3545;
        }
        
        .log-line.warning {
            background-color: #fffbf0;
            border-left: 4px solid #ffc107;
        }
        
        .log-line.info {
            background-color: #f0f8ff;
            border-left: 4px solid #17a2b8;
        }
        
        .log-line.debug {
            background-color: #f8f9fa;
            border-left: 4px solid #6c757d;
        }
        
        .actions {
            display: flex;
            gap: 10px;
            margin-bottom: 20px;
        }
        
        .loading {
            text-align: center;
            padding: 40px;
            color: #666;
        }
        
        .spinner {
            border: 4px solid #f3f3f3;
            border-top: 4px solid #667eea;
            border-radius: 50%;
            width: 40px;
            height: 40px;
            animation: spin 1s linear infinite;
            margin: 0 auto 20px;
        }
        
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
        
        .alert {
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
        }
        
        .alert-success {
            background-color: #d4edda;
            border: 1px solid #c3e6cb;
            color: #155724;
        }
        
        .alert-error {
            background-color: #f8d7da;
            border: 1px solid #f5c6cb;
            color: #721c24;
        }
        
        @media (max-width: 768px) {
            .filter-row {
                flex-direction: column;
            }
            
            .filter-group {
                min-width: 100%;
            }
            
            .stats {
                flex-direction: column;
            }
            
            .actions {
                flex-direction: column;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🔍 Visualizador de Logs do Sistema</h1>
            <div class="info">
                @if(Auth::user())
                    Usuário: {{ Auth::user()->email }} | 
                @endif
                Exibindo {{ $displayedLines }} de {{ $totalLines }} linhas totais
            </div>
        </div>

        <div id="alert-container"></div>

        <div class="filters">
            <h3>Filtros</h3>
            <form method="GET" action="{{ route('logs.index') }}">
                <div class="filter-row">
                    <div class="filter-group">
                        <label for="level">Nível:</label>
                        <select name="level" id="level">
                            <option value="all" {{ $currentFilters['level'] == 'all' ? 'selected' : '' }}>Todos</option>
                            <option value="error" {{ $currentFilters['level'] == 'error' ? 'selected' : '' }}>Erro</option>
                            <option value="warning" {{ $currentFilters['level'] == 'warning' ? 'selected' : '' }}>Aviso</option>
                            <option value="info" {{ $currentFilters['level'] == 'info' ? 'selected' : '' }}>Info</option>
                            <option value="debug" {{ $currentFilters['level'] == 'debug' ? 'selected' : '' }}>Debug</option>
                        </select>
                    </div>
                    
                    <div class="filter-group">
                        <label for="date">Data:</label>
                        <input type="date" name="date" id="date" value="{{ $currentFilters['date'] }}">
                    </div>
                    
                    <div class="filter-group">
                        <label for="search">Buscar:</label>
                        <input type="text" name="search" id="search" value="{{ $currentFilters['search'] }}" placeholder="Termo de busca...">
                    </div>
                    
                    <div class="filter-group">
                        <label for="lines">Linhas:</label>
                        <select name="lines" id="lines">
                            <option value="50" {{ $currentFilters['lines'] == 50 ? 'selected' : '' }}>50</option>
                            <option value="100" {{ $currentFilters['lines'] == 100 ? 'selected' : '' }}>100</option>
                            <option value="200" {{ $currentFilters['lines'] == 200 ? 'selected' : '' }}>200</option>
                            <option value="500" {{ $currentFilters['lines'] == 500 ? 'selected' : '' }}>500</option>
                            <option value="1000" {{ $currentFilters['lines'] == 1000 ? 'selected' : '' }}>1000</option>
                        </select>
                    </div>
                    
                    <button type="submit" class="btn btn-primary">Filtrar</button>
                </div>
            </form>
        </div>

        <div class="actions">
            <button onclick="loadStats()" class="btn btn-secondary">📊 Estatísticas</button>
            <a href="{{ route('logs.index', ['format' => 'json'] + $currentFilters) }}" class="btn btn-secondary">📋 JSON</a>
            <a href="{{ route('logs.index', ['format' => 'raw'] + $currentFilters) }}" class="btn btn-secondary">📄 Texto</a>
            @if(Auth::user() && Auth::user()->hasRole('admin'))
                <button onclick="clearLogs()" class="btn btn-danger">🗑️ Limpar Logs</button>
            @endif
            <button onclick="location.reload()" class="btn btn-secondary">🔄 Atualizar</button>
        </div>

        <div id="stats-container" class="stats" style="display: none;"></div>

        <div class="logs-container">
            <div class="logs-header">
                <h3>📋 Logs do Sistema</h3>
                <small>Última atualização: {{ date('d/m/Y H:i:s') }}</small>
            </div>
            <div class="logs-content">
                @if(count($logs) > 0)
                    @foreach($logs as $log)
                        <div class="log-line {{ 
                            strpos($log, '.ERROR:') !== false ? 'error' : 
                            (strpos($log, '.WARNING:') !== false ? 'warning' : 
                            (strpos($log, '.INFO:') !== false ? 'info' : 
                            (strpos($log, '.DEBUG:') !== false ? 'debug' : ''))) 
                        }}">{{ $log }}</div>
                    @endforeach
                @else
                    <div class="log-line">Nenhum log encontrado com os filtros aplicados.</div>
                @endif
            </div>
        </div>
    </div>

    <script>
        // Configurar CSRF token para requisições AJAX
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        function showAlert(message, type = 'success') {
            const alertContainer = document.getElementById('alert-container');
            const alertDiv = document.createElement('div');
            alertDiv.className = `alert alert-${type}`;
            alertDiv.textContent = message;
            
            alertContainer.innerHTML = '';
            alertContainer.appendChild(alertDiv);
            
            setTimeout(() => {
                alertDiv.remove();
            }, 5000);
        }

        function loadStats() {
            const statsContainer = document.getElementById('stats-container');
            
            // Mostrar loading
            statsContainer.innerHTML = '<div class="loading"><div class="spinner"></div>Carregando estatísticas...</div>';
            statsContainer.style.display = 'flex';
            
            fetch('/logs/stats')
                .then(response => response.json())
                .then(data => {
                    if (data.error) {
                        throw new Error(data.error);
                    }
                    
                    statsContainer.innerHTML = `
                        <div class="stat-card">
                            <h4>Total de Linhas</h4>
                            <div class="value">${data.total_lines.toLocaleString()}</div>
                        </div>
                        <div class="stat-card">
                            <h4>Tamanho do Arquivo</h4>
                            <div class="value">${data.file_size_human}</div>
                        </div>
                        <div class="stat-card">
                            <h4>Última Modificação</h4>
                            <div class="value" style="font-size: 16px;">${data.last_modified}</div>
                        </div>
                        <div class="stat-card">
                            <h4>Erros</h4>
                            <div class="value" style="color: #dc3545;">${data.levels.error}</div>
                        </div>
                        <div class="stat-card">
                            <h4>Avisos</h4>
                            <div class="value" style="color: #ffc107;">${data.levels.warning}</div>
                        </div>
                        <div class="stat-card">
                            <h4>Info</h4>
                            <div class="value" style="color: #17a2b8;">${data.levels.info}</div>
                        </div>
                    `;
                })
                .catch(error => {
                    statsContainer.innerHTML = `<div class="alert alert-error">Erro ao carregar estatísticas: ${error.message}</div>`;
                    console.error('Erro:', error);
                });
        }

        function clearLogs() {
            if (!confirm('Tem certeza que deseja limpar todos os logs? Esta ação não pode ser desfeita. Um backup será criado automaticamente.')) {
                return;
            }
            
            fetch('/logs/clear', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.error) {
                    throw new Error(data.error);
                }
                
                showAlert(`${data.message}. Backup criado: ${data.backup_created}`);
                setTimeout(() => {
                    location.reload();
                }, 2000);
            })
            .catch(error => {
                showAlert(`Erro ao limpar logs: ${error.message}`, 'error');
                console.error('Erro:', error);
            });
        }

        // Auto-scroll para o final dos logs
        document.addEventListener('DOMContentLoaded', function() {
            const logsContent = document.querySelector('.logs-content');
            logsContent.scrollTop = logsContent.scrollHeight;
        });

        // Auto-refresh a cada 30 segundos (opcional)
        // setInterval(() => {
        //     location.reload();
        // }, 30000);
    </script>
</body>
</html> 