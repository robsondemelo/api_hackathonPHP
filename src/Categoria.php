<?php

declare(strict_types=1);

namespace Alfa;

#[Table(name:'categoria')]
class Categoria extends Entity
{
    #[PrimaryKey]
    #[Column]
    public int $id;
    #[Column]
    public string $categoria;
    
}