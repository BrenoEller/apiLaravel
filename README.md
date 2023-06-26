# Projeto de consumo de api utilizando Laravel.<br><br>

<div>
    <h2>📁 Requisitos para uso :</h2>
    <ul>
        <li>
             Postman para fazer as requisições (ou algum outro aplicativo para gerar a collection).
        </li>
        <li>
            PHP instalado na máquina.
        </li>
    </ul>
</div>

<div>
    <h2>🔨 Instruções para uso : </h1>
    <ul>
        <li>
            Inicie o projeto utilizando o comando "php artisan serve"
        </li>
        <li>
            Todas as rotas estão declaradas no arquivo web.php, sendo referenciadas pelo controller de cada.
        </li>
    </ul>
</div>

<div>
    <h1>Todas as rotas possuem 3 formas de serem chamadas.</h1>
        
    Primeira sendo: /agrupamento-remetente (mostra todos os remetentes).

    Segunda: /agrupamento-remetente?cnpj=algumCnpjEspecífico (mostra somente o cnpj apontado na url)

    Terceira: /agrupamento-remetente?cpnj=111111 (Retorna mensagem erro por não encontrar o cnpj. Status 404).
</div>

