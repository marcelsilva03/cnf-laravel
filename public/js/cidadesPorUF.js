const campoEstados = document.querySelector('#estados');
const campoCidades = document.querySelector('#cidades');
const campoCartorios = document.querySelector('#obito_cartorio');

const adicionarOpcaoAoFormulario = function (opcao, elementoPai) {
    const option = document.createElement('OPTION');
    option.value = opcao;
    option.textContent = opcao;
    elementoPai.appendChild(option);
}

const adicionarOpcaoNeutraAoFormulario = function (texto, elementoPai) {
    const option = document.createElement('OPTION');
    option.value = '';
    option.setAttribute('name', 'untouched');
    option.textContent = texto;
    elementoPai.appendChild(option);
}

const adicionarOpcaoComValorAoFormulario = function (texto, valor, elementoPai) {
    const option = document.createElement('OPTION');
    option.value = valor;
    option.textContent = texto;
    elementoPai.appendChild(option);
}

// criar adicionarOpcaoComValorAoFormulario
const buscarCidades = async function () {
    let data = [];
    campoCidades.disabled = true;
    campoCidades.innerHTML = '';
    const uf = campoEstados.value;
    console.log('Buscando cidades para UF:', uf);
    const response = await fetch(`/api/localidades/${uf}`);
    const json = await response.json();
    console.log('Cidades encontradas:', json);
    json.forEach(item => {
        data.push(item.nome);
    });
    adicionarOpcaoNeutraAoFormulario('Selecione a Cidade', campoCidades);
    data.forEach(item => { adicionarOpcaoAoFormulario(item, campoCidades) });
    campoCidades.disabled = false;
}

const buscarCartorios = async function (){
    let data = [];
    campoCartorios.disabled = true;
    campoCartorios.innerHTML = '';
    const uf = campoEstados.value;
    const cidade = encodeURI(campoCidades.value);
    console.log('Buscando cartórios para:', uf, cidade);
    
    try {
        const response = await fetch(`/api/cartorios/${uf}/${cidade}`);
        console.log('Response status:', response.status);
        
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        
        const json = await response.json();
        console.log('Cartórios encontrados:', json);
        
        if (json && json.length > 0) {
            json.forEach(item => {
                data.push({
                    texto: item.nome,
                    valor: item.id
                });
            });
            
            adicionarOpcaoNeutraAoFormulario('Selecione o Cartório', campoCartorios);
            data.sort((a,b) => {
                if (a.texto < b.texto) {
                    return -1;
                }
                if (a.texto > b.texto) {
                    return 1;
                }
                return 0;
            });
            data.forEach(item => { adicionarOpcaoComValorAoFormulario(item.texto, item.valor, campoCartorios) });
        } else {
            // Nenhum cartório encontrado
            adicionarOpcaoNeutraAoFormulario('Nenhum cartório encontrado para esta cidade', campoCartorios);
            console.warn('Nenhum cartório encontrado para:', uf, cidade);
        }
    } catch (error) {
        console.error('Erro ao buscar cartórios:', error);
        adicionarOpcaoNeutraAoFormulario('Erro ao carregar cartórios', campoCartorios);
    }
    
    campoCartorios.disabled = false;
}

if (campoEstados) {
    campoEstados.addEventListener('change', buscarCidades);
    if (campoEstados.value) {
        document.addEventListener('DOMContentLoaded', buscarCidades);
    }
}
if (campoCidades) {
    campoCidades.addEventListener('change', buscarCartorios);
}
