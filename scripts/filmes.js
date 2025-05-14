function preencherConteudo(endpoint, containerId) {
    fetch(endpoint)
        .then(res => {
            if (!res.ok) {
                throw new Error(`Erro HTTP: ${res.status}`);
            }
            return res.json();
        })
        .then(dados => {
            const container = document.querySelectorAll(containerId + ' li');
            dados.forEach((item, index) => {
                if (container[index]) {
                    const link = container[index].querySelector('a');
                    const img = container[index].querySelector('img');
                    
                    // Verificar se os elementos existem antes de atualizar
                    if (link && img) {
                        link.href = item.link || '#';  // Garantir que o link seja válido
                        img.src = item.capa || '';     // Garantir que a capa tenha um valor válido
                        img.alt = item.nome || '';     // Garantir que o nome tenha um valor válido
                    }
                }
            });
        })
        .catch(err => console.error('Erro ao carregar:', err));
}
