<?php
class orders {
    public int $id;
    public string $status;
    public $date;
    public int $user;
    public array $orderlines = array();

    function __construct($id, $status, $date, $user, $orderlines) {
        $this->id = $id;
        $this->status = $status;
        $this->date = $date;
        $this->user = $user;
        $this->orderlines = $orderlines;
    }

    /**
     * Get the value of id
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set the value of id
     *
     * @return  self
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Get the value of status
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set the value of status
     *
     * @return  self
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get the value of date
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Set the value of date
     *
     * @return  self
     */
    public function setDate($date)
    {
        $this->date = $date;

        return $this;
    }

    /**
     * Get the value of user_email
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set the value of user_email
     *
     * @return  self
     */
    public function setUser($user)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get the value of orderlines
     */
    public function getOrderlines()
    {
        return $this->orderlines;
    }

    /**
     * Set the value of orderlines
     *
     * @return  self
     */
    public function setOrderlines($orderlines)
    {
        $this->orderlines = $orderlines;

        return $this;
    }

    public function htmlEncode(): array
    {
        return [
            "id" => $this->id,
            "status" => htmlentities($this->status),
            "date" => htmlentities($this->date),
            "user" => $this->user,
            "orderlines" => array_map(fn(orderlines $ol) => $ol->htmlEncode(), $this->orderlines),
            // seems overly complicated for a thing that does basically nothing.
        ];
    }

    public function toJson(): string
    {
        return json_encode($this->htmlEncode(), JSON_PRETTY_PRINT);
    }
}