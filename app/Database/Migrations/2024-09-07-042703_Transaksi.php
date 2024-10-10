<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class Transaksi extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id_transaksi' => [
                'type' => 'BIGINT',
                'constraint' => 24,
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'kasir' => [
                'type' => 'VARCHAR',
                'constraint' => 256,
            ],
            'id_pasien' => [
                'type' => 'BIGINT',
                'constraint' => 24,
                'unsigned' => true
            ],
            'no_kwitansi' => [
                'type' => 'VARCHAR',
                'constraint' => 128,
            ],
            'tgl_transaksi' => [
                'type' => 'DATETIME',
            ],
            'total_pembayaran' => [
                'type' => 'INT',
                'constraint' => 24,
            ],
            'terima_uang' => [
                'type' => 'INT',
                'constraint' => 24,
            ],
            'uang_kembali' => [
                'type' => 'INT',
                'constraint' => 24,
            ],
            'metode_pembayaran' => [
                'type' => 'VARCHAR',
                'constraint' => 128,
            ],
            'lunas' => [
                'type' => 'BOOLEAN'
            ],
        ]);
        $this->forge->addKey('id_transaksi', true);
        $this->forge->addForeignKey('id_pasien', 'pasien', 'id_pasien', 'CASCADE', 'NO ACTION');
        $this->forge->createTable('transaksi');
    }

    public function down()
    {
        $this->forge->dropTable('transaksi');
    }
}
