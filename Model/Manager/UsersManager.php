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

    /**
     * @param int|string $id id, email or username of User
     * @return User|false
     */
    public function get($id)
    {
        $sql = "SELECT * FROM users WHERE id = :id OR username = :username OR email = :email";
        
        $user = $this->executeRequest($sql, [
            ':id'       => $id,
            ':username' => $id,
            ':email'    => $id,
        ]);

        if($user->rowCount() == 1) {
            $datas = $user->fetch(PDO::FETCH_ASSOC);
            $user = new User($datas);

            return $user;
        }

        return false;
    }

    public function add(User $user)
    {
        if($this->get($user->getUsername())) {
            return ['error' => 'Un utilisateur existe déjà avec ce nom d\'utilisateur.'];
        }

        if($this->get($user->getEmail())) {
            return ['error' => 'Un utilisateur existe déjà avec cette adresse email.'];
        }

        $sql = "INSERT INTO users (username, email, password, role, createdAt) VALUES (:username, :email, :password, :role, :createdAt)";

        $this->executeRequest($sql, [
            ':username'     => $user->getUsername(),
            ':email'        => $user->getEmail(),
            ':password'     => $user->getPassword(),
            ':role'         => $user->getRole(),
            ':createdAt'    => $user->getCreatedAt()->format('Y-m-d H:i:s')
        ]);

        return true;
    }

    public function delete(int $id)
    {

    }

    public function update(User $user)
    {
        
    }

    /**
     * @param $email
     * @param $password
     * @return bool
     */
    public function login($email, $password): bool
    {
        $sql = "SELECT password FROM users WHERE email = ?";
        $user = $this->executeRequest($sql, [$email]);
        if($user->rowCount() == 1) {
            $user = $user->fetch(PDO::FETCH_ASSOC);
            return password_verify($password, $user['password']);
        }

        return false;
    }
}