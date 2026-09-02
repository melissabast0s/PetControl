Projeto PetControl, Sistema para adoção de animais.

Banco de dados MySql:

Nome do banco de dados: petcontrol

Tabela de Usuários: usuarios 

    id INT AUTO_INCREMENT PK
    nome VARCHAR(200) NOT NULL
    email VARCHAR(200) NOT NULL UNIQUE
    senha VARCHAR(255) NOT NULL
   

Tabela de Animais: animais 
    id INT AUTO_INCREMENT PK
    nome VARCHAR(100) NOT NULL
    especie VARCHAR(50) NOT NULL
    raca VARCHAR(50) NOT NULL
    idade DECIMAL(4,1) NOT NULL
    status ENUM('Disponível', 'Adotado', 'Tratamento') DEFAULT 'Disponível'
    foto VARCHAR(255) NULL 
    user_id  INT NOT NULL

*Executar “composer install” no terminal para instalar o vendor, pois não subimos para o GitHub.
