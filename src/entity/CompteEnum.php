<?php

namespace App\Entity;


enum CompteEnum:string
{
    case Principal = 'principal';
    case Secondaire = 'secondaire';
}