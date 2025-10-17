<div class="space-y-4">
    <div>
        <h3 class="text-lg font-medium">Assunto</h3>
        <p class="mt-1 text-sm text-gray-500">{{ $template->parseSubject($data) }}</p>
    </div>

    <div>
        <h3 class="text-lg font-medium">Conteúdo</h3>
        <div class="mt-1 prose prose-sm max-w-none">
            @if($template->is_html)
                {!! $template->parseContent($data) !!}
            @else
                <p class="text-sm text-gray-500">{{ $template->parseContent($data) }}</p>
            @endif
        </div>
    </div>

    @if($template->action_text && $template->action_url)
        <div>
            <h3 class="text-lg font-medium">Botão de Ação</h3>
            <div class="mt-1">
                <span class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-primary-600 hover:bg-primary-700">
                    {{ $template->action_text }}
                </span>
                <p class="mt-1 text-sm text-gray-500">URL: {{ $template->parseActionUrl($data) }}</p>
            </div>
        </div>
    @endif

    @if($template->variables)
        <div>
            <h3 class="text-lg font-medium">Variáveis Disponíveis</h3>
            <div class="mt-1">
                <ul class="list-disc list-inside text-sm text-gray-500">
                    @foreach($template->variables as $key => $description)
                        <li><code>@{{ $key }}</code> - {{ $description }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    @endif
</div> 