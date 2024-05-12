## Techs utilizadas

- Docker
- Docker Compose
- Mysql 8.4
- PHP 8.3
- Laravel 10
- NGINX
- Composer
- Redis
- Git
- PHP Unit

## Patterns Utilizados

- MVC
- Repository Pattern
- Service Pattern
- Factory Pattern
- Singleton Pattern
- Circuit Breaker Pattern
- Return Early Pattern

## Conceitos Utilizados

- SOLID
- PSR
- Clean Code
- Conventional commits

## Pontos de Melhoria

- Criar um repository base, algumas funcoes estao muito repetidas dando para abstrair
- Utilizar mais o pattern CQRS diminuindo a carga no banco
- Nao utilizaria enum para tratar os status da transaction, utilizaria banco + cache
- Definiria melhor as regras de transacoes, inicialmente carteira simplificada é algo muito simples mas é nescessario um estudo melhor de como tratar o status das transacoes quando uma treansacao pode ser revertida rejeitada ou cancelada
- Melhorar tipagem de dados, como está sendo trabalho com separacao de responsabilidades nao faz sentido retornar um model do repository teria de utilizar collections
- Utilizaria a arquitetura hexagonal ao inves do MVC, quando se trata de financas as regras de negocio tem muito mais importancia e requisicoes do que modelos refatorar em um momento oportuno
## Pontos de Criacao
Depositos:<br>
[ ] Depositos tem de ser tratados como algo diferente de transacoes, pois um deposito nao vai poder ser revertido, entao precisara de uma tabela nova para esse tipo de operacao na carteira
Extrato:<br>
[ ]Criar uma tabela por exemplo como wallet log, para registrar o extrato do usuario e ter uma melhor visualizacao<br>
[ ]Endpoint para fazer operacoes basicas na carteira criar editar remover<br>
[ ]Endpoint para operacoes basicas de usuario, criar deletar transfomar<br>
[ ]Endpoint para listar transacoes de usuarios
