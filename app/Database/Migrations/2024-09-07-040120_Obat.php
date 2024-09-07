<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class Obat extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id_obat' => [
                'type' => 'BIGINT',
                'constraint' => 24,
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'id_supplier' => [
                'type' => 'BIGINT',
                'constraint' => 24,
                'unsigned' => true
            ],
            'nama_obat' => [
                'type' => 'VARCHAR',
                'constraint' => 256,
            ],
            'kategori_obat' => [
                'type' => 'VARCHAR',
                'constraint' => 256,
            ],
            'bentuk_obat' => [
                'type' => 'VARCHAR',
                'constraint' => 128,
            ],
            'harga_obat' => [
                'type' => 'BIGINT',
                'constraint' => 24,
            ],
        ]);
        $this->forge->addKey('id_obat', true);
        $this->forge->addForeignKey('id_supplier', 'supplier', 'id_supplier', 'CASCADE', 'CASCADE');
        $this->forge->createTable('obat');
    }

    public function down()
    {
        $this->forge->dropTable('obat');
    }
}
