<?php
namespace Api\SubwayRoutes\DTO;

class UserDTO
{
    public $id;
    public $username;
    public $email;
    public $phone;
    public $address;
    public $date_of_birth;
    public $gender;
    public $nationality;
    public $languages;

    public function __construct($user)
    {
        $this->id = $user->getId();
        $this->username = $user->getUsername();
        $this->email = $user->getEmail();
        $this->phone = $user->getPhone();
        $this->address = [
            'street' => $user->getStreet(),
            'city' => $user->getCity(),
            'country' => $user->getCountry(),
            'postal_code' => $user->getPostalCode(),
            'state' => $user->getState(),
        ];
        $this->date_of_birth = $user->getDateOfBirth()->format('Y-m-d');
        $this->gender = $user->getGender();
        $this->nationality = $user->getNationality();
        $this->languages = $user->getLanguages();
    }

    public function toArray()
    {
        return get_object_vars($this);
    }
}