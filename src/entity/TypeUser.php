<?php

namespace App\Entity;

enum TypeUser: string
{
    case CLIENT = 'client';
    case SERVICECOMMERCIAL = 'service commercial';
}