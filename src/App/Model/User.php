<?php
namespace Harmony\App\Model;

use Harmony\Resources\Model;

class User implements Model
{
    private int $id;
    private string $name;
    private string $login;
    private string $password;
    private $created_at;

    public function __construct() {
        $num_args = func_num_args();
        $args = func_get_args();
        switch($num_args) {
            case 4:
                print("4 args");
                break;
            case 5:
                call_user_func_array([$this, "__construct2"], $args);
                break;
        }
    }

    private function __construct1(string $name, string $login, string $password, string $date) {
        $this->setId(0);
        $this->setName($name);
        $this->setLogin($login);
        $this->setPassword($password);
        $this->setCreatedAt($date);
    }

    private function __construct2(int $id, string $name, string $login, string $password, string $date) {
        $this->setId($id);
        $this->setName($name);
        $this->setLogin($login);
        $this->setPassword($password);
        $this->setCreatedAt($date);
    }

    /**
     * @return int
     */
    public function getId(): int {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId(int $id): void {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getName(): string {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName(string $name): void {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getLogin(): string {
        return $this->login;
    }

    /**
     * @param string $login
     */
    public function setLogin(string $login): void {
        $this->login = $login;
    }

    /**
     * @return string
     */
    public function getPassword(): string {
        return $this->password;
    }

    /**
     * @param string $password
     */
    public function setPassword(string $password): void {
        $this->password = $password;
    }

    /**
     * @return \DateTime
     */
    public function getCreatedAt(): \DateTime {
        return $this->created_at;
    }

    public function setCreatedAt(string $created_at="0000-00-00 00:00:00"): void {
        $date = \DateTime::createFromFormat("Y-m-d H:i:s", $created_at);
        $this->created_at = $date;
    }
}