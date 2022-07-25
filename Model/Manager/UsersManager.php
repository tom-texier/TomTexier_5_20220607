<?php

namespace App\Model\Manager;

use App\Model\Entity\User;
use PDO;
use Texier\Framework\Model;

class UsersManager extends Model
{
    public function getList(): array
    {

    }

    public function get($id): User
    {
        $sql = "SELECT * FROM users WHERE id = :id OR username = :id OR email = :id";
        
        $user = $this->executeRequest($sql, [
            ':id'    => $id
        ]);

        if($user->rowCount() == 1) {
            $datas = $user->fetch(PDO::FETCH_ASSOC);
            $user = new User($datas);
            return $user;
        }
        else {
            return false;
        }
    }

    public function add(User $user)
    {
        $verif = $this->get($user->getUsername());

        if($verif) {
            return ['error' => 'Un utilisateur existe déjà avec ce nom d\'utilisateur.'];
        }

        if($this->get($user->getEmail())) {
            return ['error' => 'Un utilisateur existe déjà avec cette adresse email.'];
        }

        $sql = "INSERT INTO users (username, email, password, role, createdAt) VALUES (?, ?, ?, ?, ?)";

        $this->executeRequest($sql, [
            $user->getUsername(),
            $user->getEmail(),
            $user->getPassword(),
            $user->getRole(),
            $user->getCreatedAt()->format('Y-m-d H:i:s')
        ]);

        return ['success' => 'Votre inscription a bien été prise en compte. Veuillez vous connecter.'];
    }

    public function delete(int $id)
    {

    }

    public function update(User $user)
    {
        
    }
}