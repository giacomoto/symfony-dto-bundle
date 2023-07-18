# Giacomoto DTO Bundle
Dto Bundle uses JSMSerializer Bundle

## Install
```
composer require giacomoto/symfony-dto
```

## Usage
Create a Dto and DtoTransformer Class <br>

Example for User Entity:

User Entity must implements ```IDtoTransformer```

Create file UserDto ex: ```Dto/DtoUser.php```<br>
```php
<?php

namespace App\Dto;

use JMS\Serializer\Annotation as Serializer;
use Giacomoto\Bundle\GiacomotoDtoBundle\Interface\IDtoTransformer;

class UserDto implements IDtoTransformer
{
    /**
     * @Serializer\Type("int")
     * @Serializer\Groups({"User"})
     */
    public int $id;

    /**
     * @Serializer\Type("string")
     * @Serializer\Groups({"User"})
     */
    public string $email;

    /**
     * @Serializer\Type("DateTimeImmutable<'timestamp'>")
     * @Serializer\Groups({"User"})
     */
    public \DateTimeImmutable $createdAt;
}
```
Create file UserDtoTransformer ex: ```Dto/DtoUser.php```<br>
```php
<?php

namespace App\Dto\Transformer;

use App\Dto\UserDto;
use App\Entity\User;
use Giacomoto\Bundle\GiacomotoDtoBundle\Exception\DtoUnexpectedTypeException;
use Giacomoto\Bundle\GiacomotoDtoBundle\Interface\IDtoTransformer;
use Giacomoto\Bundle\GiacomotoDtoBundle\Transformer\DtoTransformer;

class UserDtoTransformer extends DtoTransformer {

    /**
     * @param IDtoTransformer $entity
     * @return UserDto
     */
    public function transformFromObject(IDtoTransformer $entity): UserDto
    {
        if (!$entity instanceof User) {
            throw new DtoUnexpectedTypeException('Expected type of User but got ' . get_class($entity));
        }

        $dto = new UserDto();

        $dto->id = $entity->getId();
        $dto->email = $entity->getEmail();

        $dto->lastName = $entity->getLastName();
        $dto->firstName = $entity->getFirstName();

        $dto->createdAt = $entity->getCreatedAt();
        $dto->updatedAt = $entity->getUpdatedAt();

        return $dto;
    }
}
```
Create Dto and pass it to serializer
Ex: ```Controller/UserController.php```
```php
<?php

namespace App\Controller;

use App\Validation\User\CreateUserConstraint;
use Giacomoto\Bundle\GiacomotoValidationBundle\Exception\ValidationException;
use Giacomoto\Bundle\GiacomotoValidationBundle\Service\ValidationService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class AuthController extends AbstractController
{
    ...

    public function getUser(
        User               $user,
        UserDtoTransformer $userDtoTransformer,
    ): JsonResponse
    {
        ...
        
        // create dto
        $userDto = $userDtoTransformer->transformFromObject($user)
        
        // create serializationGroups
        $serializationGroups = [ "User" ];

        return new JsonResponse(json_decode(
            $this->serializerService
                ->setGroups($serializationGroups)
                ->serialize(["data" => $userDto, '_meta' => $metadata])
            , true), $status, $metadata);
        
    }
}
```
