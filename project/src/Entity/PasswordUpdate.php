<?php

namespace App\Entity;

use Symfony\Component\Validator\Constraints as Assert;

class PasswordUpdate
{
    /**
     * @Assert\Length(min=8, minMessage="Votre mot de passe doit faire au minimum 8 caractères")
     * @var string
     */
    private $newPassword;

    /**
     * @Assert\EqualTo(propertyPath="newPassword", message="Les mots de passe ne correspondent pas")
     * @var string
     */
    private $confirmPassword;

    public function getNewPassword(): ?string
    {
        return $this->newPassword;
    }

    public function setNewPassword(string $newPassword): self
    {
        $this->newPassword = $newPassword;

        return $this;
    }

    public function getConfirmPassword(): ?string
    {
        return $this->confirmPassword;
    }

    public function setConfirmPassword(string $confirmPassword): self
    {
        $this->confirmPassword = $confirmPassword;

        return $this;
    }
}
