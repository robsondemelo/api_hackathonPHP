<?php
declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class Produto extends AbstractMigration
{
    /**
     * Change Method.
     *
     * Write your reversible migrations using this method.
     *
     * More information on writing migrations is available here:
     * https://book.cakephp.org/phinx/0/en/migrations.html#the-change-method
     *
     * Remember to call "create()" or "update()" and NOT "save()" when working
     * with the Table class.
     */
    public function change(): void
    {
        $table = $this->table('produto');
        $table->AddColumn('produto','string', ['limit' =>100])
              ->AddColumn('foto','string', ['limit' =>255])
              ->AddColumn('descricao','text')
              ->AddColumn('valor','double')
              ->AddColumn('categoria_id','integer')
              ->AddColumn('empresa_id','integer')
              ->addForeignKey('categoria_id','categoria', 'id')
              ->addForeignKey('empresa_id','empresa', 'id')
              ->create();

    }
}
