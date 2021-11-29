<?php

declare(strict_types=1);

namespace Alfa;

#[Table(name:'empresa')]
class Empresa extends Entity
{
    #[PrimaryKey]
    #[Column]
    public int $id;
    #[Column]
    public string $empresa;
    #[Column]
    public string $whatsapp;
    
}