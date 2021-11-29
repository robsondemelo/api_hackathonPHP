//tabela do mysql
CREATE TABLE materia(
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(255) NOT NULL,
    dia INT,
    horario TIME,
    dataatualizacao TIMESTAMP DEFAULT CURRENT_TIMESTAMP on UPDATE CURRENT_TIMESTAMP
);

CREATE TABLE aluno(
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(255) NOT NULL,
    ra VARCHAR(255) NOT NULL
);

CREATE TABLE sala(
    idsala INT AUTO_INCREMENT PRIMARY KEY,
    descricao VARCHAR(255),
    dataatualizacao TIMESTAMP DEFAULT
CURRENT_TIMESTAMP on UPDATE CURRENT_TIMESTAMP
);


//comando pra acessar o bash
docker exec -it php-mysql bash

//comando pra remover o container
docker rm php-mysql -f

//criar um volume com o nome
docker volume create mysql

//criando o container mysql direcionado com o volume
docker run -v mysql:/var/lib/mysql --name mysql -e MYSQL_ROOT_PASSWORD=123456 -e MYSQL_DATABASE=alfa -d mysql:5.7.30



//comando final já com dns dessa app
docker run -d -p 8888:80 -v ${PWD}:/var/www/html --link mysql --name php-mysql --env-file ./.env php:alfa-8.0

 //docker run -d -p 8888:80 -v ${PWD}:/var/www/html --link mysql --name php-mysql --env-file ./.env php:alfa-8.0


 //biblioteca de orm falado na aula
 
 site typeorm.io

 site sequelize.org


 php-fig.org/psr/psr-7/

 docker build -t php:prova-8.0 .


docker volume create mysql

docker run -v mysql:/var/lib/mysql --name mysql -e MYSQL_ROOT_PASSWORD=123456 -e MYSQL_DATABASE=alfa -d mysql:5.7.30

docker run -d -p 8888:80 -v ${PWD}:/var/www/html --link mysql --name php-mysql --env-file ./.env php:alfa-8.0

docker exec -it mysql bash

mysql -u root -p

use alfa;

CREATE TABLE materia(
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(255) NOT NULL,
    dia INT,
    horario TIME,
    dataatualizacao TIMESTAMP DEFAULT CURRENT_TIMESTAMP on UPDATE CURRENT_TIMESTAMP
);

