
let filmes = [
    {
        nome: "A rede Social",
        capa: "./img/images.png",
        link: "https://www.youtube.com/watch?v=gyjpKOFX4Bw&t=4010s"
    },
    {
        nome: "A rede Social",
        capa: "./img/images.png",
        link: "https://www.youtube.com/watch?v=gyjpKOFX4Bw&t=4010s"
    },{
        nome: "A rede Social",
        capa: "./img/images.png",
        link: "https://www.youtube.com/watch?v=gyjpKOFX4Bw&t=4010s"
    },{
        nome: "A rede Social",
        capa: "./img/images.png",
        link: "https://www.youtube.com/watch?v=gyjpKOFX4Bw&t=4010s"
    },{
        nome: "A rede Social",
        capa: "./img/images.png",
        link: "https://www.youtube.com/watch?v=gyjpKOFX4Bw&t=4010s"
    },{
        nome: "A rede Social",
        capa: "./img/images.png",
        link: "https://www.youtube.com/watch?v=gyjpKOFX4Bw&t=4010s"
    },{
        nome: "A rede Social",
        capa: "./img/images.png",
        link: "https://www.youtube.com/watch?v=gyjpKOFX4Bw&t=4010s"
    },
   
    // Adicione mais filmes conforme necessário
];

  // Função para preencher os dados do filme na lista existente
  function preencherFilmes() {
    // Selecionar todos os itens da lista
    let itens = document.querySelectorAll('#filmes-container li');

    // Iterar sobre a lista de filmes e preencher os dados nos itens existentes
    filmes.forEach((filme, index) => {
        if (itens[index]) {
            let linkElement = itens[index].querySelector('a');
            let imgElement = itens[index].querySelector('img');

            // Definir os atributos do link e da imagem
            linkElement.href = filme.link;
            imgElement.src = filme.capa;
            imgElement.alt = filme.nome;
        }
    });
}

// Chamar a função para preencher os dados dos filmes
preencherFilmes();