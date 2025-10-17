# 📋 Sistema de Visualização de Logs - CNF

## 🎯 Visão Geral

Este sistema permite visualizar, filtrar e gerenciar os logs do servidor de forma segura através do navegador, sem expor dados sensíveis.

## 🔐 Segurança Implementada

### Acesso Simplificado
- **Acesso livre**: Sem necessidade de autenticação para melhorar o fluxo
- **Log de atividade**: Visualizações são registradas com IP e filtros aplicados

### Filtragem de Dados Sensíveis
O sistema automaticamente mascara:
- ✉️ **Emails**: `admin@email.com` → `***EMAIL***`
- 🔑 **Senhas**: `password: "123456"` → `password: ***SENHA***`
- 🎫 **Tokens**: `api_key: "abc123"` → `api_key: ***TOKEN***`
- 📄 **CPFs**: `123.456.789-00` → `***CPF***`
- 🌐 **IPs privados**: `192.168.1.100` → `192.***.***.***`
- 📁 **Caminhos completos**: `/var/www/html/storage/logs/file.log` → `/var/www/html/***`

## 🚀 Como Usar

### 1. Acesso
```
URL: https://seudominio.com/logs
```

### 2. Filtros Disponíveis

#### Por Nível de Log
- **Todos**: Exibe todos os tipos de log
- **Erro**: Apenas logs de erro (ERROR)
- **Aviso**: Apenas logs de aviso (WARNING)  
- **Info**: Apenas logs informativos (INFO)
- **Debug**: Apenas logs de debug (DEBUG)

#### Por Data
- Filtra logs de uma data específica (formato: YYYY-MM-DD)
- Exemplo: `2025-01-20`

#### Por Termo de Busca
- Busca case-insensitive em todo o conteúdo
- Exemplo: `login`, `Dashboard`, `error`

#### Quantidade de Linhas
- 50, 100, 200, 500 ou 1000 linhas
- Limitado a 1000 para performance

### 3. Formatos de Export

#### Interface Web (Padrão)
- Visualização colorida por tipo de log
- Interface responsiva e amigável
- Scroll automático para logs mais recentes

#### JSON
```
URL: /logs?format=json&level=error&lines=100
```
Retorna:
```json
{
    "logs": ["[2025-01-20 10:30:00] local.ERROR: ..."],
    "total_lines": 1500,
    "displayed_lines": 100,
    "filters_applied": {
        "level": "error",
        "date": null,
        "search": null
    }
}
```

#### Texto Simples
```
URL: /logs?format=raw&level=error&lines=100
```
Retorna arquivo de texto para download

### 4. Estatísticas
Clique em "📊 Estatísticas" para ver:
- Total de linhas no arquivo
- Tamanho do arquivo
- Última modificação
- Contagem por nível (erro, aviso, info, debug)

### 5. Limpeza de Logs
- Botão "🗑️ Limpar Logs" disponível para todos os usuários
- Cria backup automático antes de limpar
- Confirma ação antes de executar

## 🔧 Endpoints da API

### GET /logs
**Parâmetros:**
- `level`: all|error|warning|info|debug
- `date`: YYYY-MM-DD
- `search`: termo de busca
- `lines`: 50|100|200|500|1000
- `format`: web|json|raw

### GET /logs/stats
Retorna estatísticas do arquivo de log

### POST /logs/clear
Limpa logs - cria backup automaticamente

## 🛡️ Logs de Atividade

Toda visualização de logs é registrada com:
- Filtros aplicados
- IP de origem
- User Agent

Exemplo:
```
[2025-01-20 10:30:00] local.INFO: Logs visualizados {
    "filters": {
        "level": "error",
        "date": "2025-01-20",
        "search": "login",
        "lines": 100
    },
    "ip": "192.168.1.100",
    "user_agent": "Mozilla/5.0..."
}
```

## 🎨 Interface

### Cores por Tipo de Log
- 🔴 **Erro**: Fundo vermelho claro
- 🟡 **Aviso**: Fundo amarelo claro  
- 🔵 **Info**: Fundo azul claro
- ⚫ **Debug**: Fundo cinza claro

### Funcionalidades da Interface
- 🔄 **Auto-refresh**: Botão para atualizar manualmente
- 📱 **Responsiva**: Funciona em dispositivos móveis
- 🎯 **Auto-scroll**: Rola automaticamente para logs mais recentes
- ⚡ **Loading**: Indicadores de carregamento

## 🚨 Alertas e Notificações

O sistema exibe alertas para:
- ✅ Logs limpos com sucesso
- ❌ Erros ao carregar dados
- ℹ️ Informações sobre filtros aplicados

## 📊 Performance

### Otimizações Implementadas
- Limite máximo de 1000 linhas por visualização
- Cache de estatísticas
- Processamento eficiente de filtros
- Sanitização otimizada de dados sensíveis

### Recomendações
- Use filtros específicos para melhor performance
- Para análises extensas, use o format JSON ou raw
- Limpe logs regularmente para manter performance

## 🔍 Exemplos de Uso

### Investigar Erros de Login
```
Filtros: level=error, search=login, lines=200
```

### Verificar Atividade de um Usuário
```
Filtros: search=user_id:123, lines=500
```

### Análise de Erros de Hoje
```
Filtros: level=error, date=2025-01-20, lines=1000
```

### Export para Análise Externa
```
URL: /logs?format=json&level=error&date=2025-01-20
```

## 🛠️ Troubleshooting

### Problema: "Arquivo de log não encontrado"
- Verifique se o arquivo `storage/logs/laravel.log` existe
- Verifique permissões de leitura

### Problema: "Acesso negado"
- Problema removido - não há mais verificação de autenticação

### Problema: Interface não carrega
- Verifique se JavaScript está habilitado
- Verifique console do navegador para erros

### Problema: Estatísticas não carregam
- Verifique conectividade de rede
- Verifique logs do servidor para erros

## 📝 Notas Importantes

1. **Backup Automático**: Ao limpar logs, um backup é criado automaticamente
2. **Dados Sensíveis**: São mascarados automaticamente - não é possível desabilitar
3. **Performance**: Arquivos de log muito grandes podem afetar a performance
4. **Atividade**: Todas as ações são logadas para controle
5. **Acesso Livre**: Qualquer pessoa pode visualizar e limpar logs

## 🔄 Atualizações Futuras

Funcionalidades planejadas:
- [ ] Filtro por IP
- [ ] Filtro por User Agent
- [ ] Export para PDF
- [ ] Alertas em tempo real
- [ ] Dashboard de métricas
- [ ] Integração com sistemas de monitoramento 