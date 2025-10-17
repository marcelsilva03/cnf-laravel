<x-filament-widgets::widget>
    <x-filament::section>
        <h2 class="text-lg font-bold">Documentação da API</h2>
        <ul class="mb-5">
            <li>Visão Geral: Esta documentação fornece informações sobre como utilizar a API. Abaixo estão os detalhes necessários para interagir com a API de forma eficaz.</li>
            <li>Autenticação: Para acessar a API, você deve incluir a chave da API no cabeçalho da requisição. A chave deve ser enviada como um token Bearer.</li>
            <li>Endpoints:</li>
            <ul>
                <li>Consultar Falecidos: <code>/api/falecidos/{id}</code> - Método: GET - Descrição: Recupera os dados de um falecido específico.</li>
                <li>Listar Falecidos: <code>/api/falecidos</code> - Método: GET - Descrição: Recupera uma lista de todos os falecidos.</li>
            </ul>
            <li>Respostas da API: A API retornará os dados no formato JSON. Certifique-se de que a chave da API é válida e que você possui as permissões necessárias para acessar os dados desejados.</li>
        </ul>
    </x-filament::section>
</x-filament-widgets::widget>