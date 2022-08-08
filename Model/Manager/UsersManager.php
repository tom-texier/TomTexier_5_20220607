<?php

namespace App\Model\Manager;

use App\Model\Entity\User;
use PDO;
use Texier\Framework\Model;

class UsersManager extends Model
{
    public function getList(): array
    {
        $sql = "SELECT id, username, email, role, createdAt
                FROM users
                ORDER BY createdAt DESC";

        $result = $this->executeRequest($sql);
        $users = [];

        while ($row = $result->fetch(\PDO::FETCH_ASSOC)) {
            $users[] = new User($row);
        }

        return $users;
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
        if($result = $this->userExist($user)) {
            return $result;
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
        $sql = "DELETE FROM users WHERE id = ?";

        return $this->executeRequest($sql, [$id]);
    }

    public function update(User $user)
    {
        $sql = "UPDATE users
                SET username = :username, email = :email, role = :role
                WHERE id = :id";

        return $this->executeRequest($sql, [
            ':username' => $user->getUsername(),
            ':email' => $user->getEmail(),
            ':role' => $user->getRole(),
            ':id' => $user->getId()
        ]);
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

    public function count()
    {
        $sql = "SELECT COUNT(*) as numberUsers FROM users";
        $result = $this->executeRequest($sql)->fetch();

        return $result['numberUsers'];
    }

    private function userExist(User $user)
    {
        $result = $this->get($user->getUsername());
        if($result)
            return ['error' => 'Un utilisateur existe déjà avec ce nom d\'utilisateur.'];

        $result = $this->get($user->getEmail());
        if($result)
            return ['error' => 'Un utilisateur existe déjà avec cette adresse email.'];

        return false;
    }
}