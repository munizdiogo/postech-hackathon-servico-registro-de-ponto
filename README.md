# Hackaton - Sistema de Registros de Ponto - Serviço: Registro de Ponto

Esta documentação tem o intuito de orientar sobre a configuração e utilização correta do sistema para o serviço: registro de ponto, que é responsável por registrar o ponto, obter registros e geração de espelho de ponto (envio por e-mail).


## Infraestrutura
Toda a infraestrutura (cluster, banco de dados, imagem, etc) está vinculada aos serviços AWS, e é criada através dos workflows com o nome "pipeline-registro-de-ponto" dentro do Github Actions dos seguintes repositórios: 

**Infraestrutura da Aplicação:**  
https://github.com/munizdiogo/postech-hackathon-infra-kubernetes-terraform

**Infraestrutura do Banco de Dados:**  
https://github.com/munizdiogo/postech-hackathon-infra-database-terraform

Dessa forma, basta executar o workflow que todo a infraestrutura será gerada automaticamente (o build é realizado apenas na primeira vez, após isso é necessário comentar no workflow o job build e deixar apenas o job deploy ativo).

**IMPORTANTE:** Verificar se valor das SECRETS estão de acordo com os valores da AWS. 


## Como acessar

**Aplicação:**  
É necessário a criação de uma API no AWS API Gateway, e realização das configurações de rota, ao final da configuração será disponibilizado um endpoint para que seja realizada as requisições. 

**Banco de dados:**  
Acesse o painel da Amazon RDS ao clicar no banco de dados desejado você visualizará um endpoint para que possa usar como host no momento da conexão com o banco de dados.

## Endpoints

Após a criação da infraestrutura, funções lambda e configuração no AWS API Gateway, você conseguirá realizar as requisições HTTP conforme a documentação:
[Requisições HTTP - Exemplos](https://documenter.getpostman.com/view/14275027/2sA35A95nc)


## Documentação

// PENDENTE - ATUALIZAR
[Fluxograma - XXXXXXXXX](https://google.com)

[Requisições HTTP - Exemplos](https://documenter.getpostman.com/view/14275027/2sA35A95nc)
