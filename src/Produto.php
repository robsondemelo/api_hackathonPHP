<?php

declare(strict_types=1);

namespace Alfa;

#[Table(name:'produto')]
class Produto extends Entity
{
    #[PrimaryKey]
    #[Column]
    public int $id;
    #[Column]
    public string $produto;
    #[Column]
    public string $foto;
    #[Column]
    public string $descricao;
    #[Column]
    public string $valor;
    #[Column]
    public int $categoria_id;
    #[Column]
    public int $empresa_id;
    
}