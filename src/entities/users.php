<?php
class users {
    //Properties
    public ?int $id;
    public string $email;
    public string $password;
    public string $role;

    //Methods
    public function __construct($email, $password, $role, int $id = null) {
        $this->email = $email;
        $this->password = $password;
        $this->role = $role;
        $this->id = $id;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }



    /**
     * Get the value of email
     */
    public function getEmail(): string
    {
            return $this->email;
    }

    /**
     * Set the value of email
     *
     * @return  self
     */
    public function setEmail($email)
    {
            $this->email = $email;

            return $this;
    }

    /**
     * Get the value of password
     */
    public function getPassword(): string
    {
            return $this->password;
    }

    /**
     * Set the value of password
     *
     * @return  self
     */
    public function setPassword($password)
    {
            $this->password = $password;

            return $this;
    }

    /**
     * Get the value of role
     */
    public function getRole(): string
    {
            return $this->role;
    }

    /**
     * Set the value of role
     *
     * @return  self
     */
    public function setRole($role)
    {
            $this->role = $role;

            return $this;
    }

    public function htmlEncode(): array
    {
        return [
            "id" => $this->id,
            "email" => htmlentities($this->email),
            "role" => htmlentities($this->role),
        ];
    }

    public function toJson(): string
    {
        return json_encode($this->htmlEncode(), JSON_PRETTY_PRINT);
    }
}