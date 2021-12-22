<?php

namespace App\Model;

use App\DB;

class User
{
    public ?int $id = null;
    public string $name;
    public ?int $partnerId = null;
    public string $hash;

    public function __construct(string $name)
    {
        $this->name = $name;
        $this->hash = substr(md5($name), 0, 10);
    }

    public function save(): void
    {
        $sql = 'insert into user (name, hash) values (:name, :hash)';
        $connection = DB::getConnection();

        $query = $connection->prepare($sql);
        $query->bindParam('name', $this->name);
        $query->bindParam('hash', $this->hash);
        $query->execute();
    }

    public static function findByHash(string $hash): ?User
    {
        $connection = DB::getConnection();
        $sql = ('select id, name, partner_id, hash from user where hash = :hash');
        $query = $connection->prepare($sql);
        $query->bindParam('hash', $hash);
        $query->execute();

        $result = $query->fetchAll(\PDO::FETCH_ASSOC);

        if (empty($result)) {
            return null;
        }

        $user = new User($result[0]['name']);
        $user->id = $result[0]['id'];
        $user->partnerId = $result[0]['partner_id'];
        $user->hash = $result[0]['hash'];

        return $user;
    }

    public function getPartnerName(): string
    {
        $connection = DB::getConnection();
        $sql = ('select name from user where id = :id');
        $query = $connection->prepare($sql);
        $query->bindParam('id', $this->partnerId);
        $query->execute();

        $result = $query->fetchAll(\PDO::FETCH_ASSOC);

        return $result[0]['name'];
    }

    public function choosePartner(): void
    {
        $connection = DB::getConnection();
        $sql = '
            select id from user
            where id not in (
                select partner_id from user where partner_id is not null
                )
            and id != :id
        ';

        $query = $connection->prepare($sql);
        $query->bindParam('id', $this->id);
        $query->execute();

        $result = $query->fetchAll(\PDO::FETCH_ASSOC);

        $ids = [];
        foreach ($result as $item) {
            $ids[] = (int)$item['id'];
        }

        $this->partnerId = $this->getPartnerId($ids);

        $sql = 'update user set partner_id = :partnerId where id = :id';
        $query = $connection->prepare($sql);
        $query->bindParam('partnerId', $this->partnerId);
        $query->bindParam('id', $this->id);
        $query->execute();
    }

    private function getPartnerId(array $ids): int
    {
        if (count($ids) !== 2) {
            return $ids[rand(0, count($ids) - 1)];
        }

        $connection = DB::getConnection();

        $sql = 'select partner_id from user where id = :id';
        $query = $connection->prepare($sql);
        $query->bindParam('id', $ids[0]);
        $query->execute();

        $result = $query->fetchAll(\PDO::FETCH_ASSOC);

        return $result[0]['partner_id'] === null ? $ids[0] : $ids[1];
    }
}
