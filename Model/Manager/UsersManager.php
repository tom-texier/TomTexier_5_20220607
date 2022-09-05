<?php

namespace App\Model\Manager;

use App\Model\Entity\User;
use PDO;
use Texier\Framework\Model;

class UsersManager extends Model
{
    /**
     * Retourne la liste de tous les utilisateurs
     * @return User[]
     * @throws \Exception
     */
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
     * Retourne un utilisateur par son ID, son email ou son nom d'utilisateur
     * @param int|string $id Id, email ou nom d'utilisateur
     * @return User|false
     * @throws \Exception
     */
    public function get($id)
    {
        $sql = "SELECT * FROM users WHERE id = :id OR username = :username OR email = :email";

        $user = $this->executeRequest($sql, [
            ':id' => $id,
            ':username' => $id,
            ':email' => $id,
        ]);

        if ($user->rowCount() == 1) {
            $datas = $user->fetch(PDO::FETCH_ASSOC);
            $user = new User($datas);

            return $user;
        }

        return false;
    }

    /**
     * Ajouter un utilisateur
     * @param User $user
     * @return false|\PDOStatement|string[]
     * @throws \Exception
     */
    public function add(User $user)
    {
        if ($result = $this->userExist($user)) {
            return $result;
        }

        $sql = "INSERT INTO users (username, email, password, role, createdAt) VALUES (:username, :email, :password, :role, :createdAt)";

        return $this->executeRequest($sql, [
            ':username' => $user->getUsername(),
            ':email' => $user->getEmail(),
            ':password' => $user->getPassword(),
            ':role' => $user->getRole(),
            ':createdAt' => $user->getCreatedAt()->format('Y-m-d H:i:s')
        ]);
    }

    /**
     * Supprimer un utilisateur
     * @param int $id
     * @return false|\PDOStatement
     * @throws \Exception
     */
    public function delete(int $id)
    {
        $sql = "DELETE FROM users WHERE id = ?";

        return $this->executeRequest($sql, [$id]);
    }

    /**
     * Mettre à jour un utilisateur
     * @param User $user
     * @return false|\PDOStatement|string[]
     * @throws \Exception
     */
    public function update(User $user)
    {
        if ($result = $this->userExist($user, true)) {
            return $result;
        }

        $sql = "UPDATE users
                SET username = :username, email = :email, role = :role, password = :password
                WHERE id = :id";

        return $this->executeRequest($sql, [
            ':username' => $user->getUsername(),
            ':email' => $user->getEmail(),
            ':role' => $user->getRole(),
            ':password' => $user->getPassword(),
            ':id' => $user->getId()
        ]);
    }

    /**
     * Vérifie si l'utilisateur existe et qu'il renseigne les bons identifiants
     * @param $email
     * @param $password
     * @return bool
     * @throws \Exception
     */
    public function login($email, $password): bool
    {
        $sql = "SELECT password FROM users WHERE email = ?";
        $user = $this->executeRequest($sql, [$email]);
        if ($user->rowCount() == 1) {
            $user = $user->fetch(PDO::FETCH_ASSOC);
            return password_verify($password, $user['password']);
        }

        return false;
    }

    /**
     * Retourne le nombre d'utilisateurs
     * @return mixed
     * @throws \Exception
     */
    public function count()
    {
        $sql = "SELECT COUNT(*) as numberUsers FROM users";
        $result = $this->executeRequest($sql)->fetch();

        return $result['numberUsers'];
    }

    /**
     * Détermine si un utilisateur existe
     * @param User $user Utilisateur à tester
     * @param bool $strict Indiquer faux pour vérifier seulement l'email et le nom d'utilisateur. Vrai pour vérifier en excluant l'identifiant.
     * @return false|string[]
     * @throws \Exception
     */
    private function userExist(User $user, bool $strict = false)
    {
        if ($strict) {
            $sql = "SELECT * FROM users WHERE id != :id AND username = :username";

            $result = $this->executeRequest($sql, [
                ':id' => $user->getId(),
                ':username' => $user->getUsername(),
            ]);

            if ($result->rowCount() != 0)
                return ['error' => 'Un utilisateur existe déjà avec ce nom d\'utilisateur.'];

            $sql = "SELECT * FROM users WHERE id != :id AND email = :email";

            $result = $this->executeRequest($sql, [
                ':id' => $user->getId(),
                ':email' => $user->getEmail()
            ]);

            if ($result->rowCount() != 0)
                return ['error' => 'Un utilisateur existe déjà avec cette adresse email.'];

        } else {
            $result = $this->get($user->getUsername());
            if ($result)
                return ['error' => 'Un utilisateur existe déjà avec ce nom d\'utilisateur.'];

            $result = $this->get($user->getEmail());
            if ($result)
                return ['error' => 'Un utilisateur existe déjà avec cette adresse email.'];
        }

        return false;
    }
}
