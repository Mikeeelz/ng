<?php

namespace App\Controller;

use App\DB;
use App\Model\User;

class MigrateController
{
    public function index(): void
    {
        $this->createTable();
        $this->fillTable();

        echo 'og';
    }

    private function createTable(): void
    {
        $connection = DB::getConnection();

        $connection->exec('
            create table user
            (
                id int unsigned primary key auto_increment,
                name varchar(255),
                partner_id int,
                hash varchar(255)
            );
        ');
    }

    private function fillTable(): void
    {
        $names = [
            'Рыбак', 'Владик', 'Жендос', 'Максим', 'Мясной ком', 'Поля', 'Семен',
        ];

        foreach ($names as $name) {
            $user = new User($name);
            $user->save();
        }
    }
}