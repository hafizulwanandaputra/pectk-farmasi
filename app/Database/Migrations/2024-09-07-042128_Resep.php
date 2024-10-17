<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class Resep extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id_resep' => [
                'type' => 'BIGINT',
                'constraint' => 24,
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'nomor_registrasi' => [
                'type' => 'VARCHAR',
                'constraint' => 256,
            ],
            'no_rm' => [
                'type' => 'VARCHAR',
                'constraint' => 24,
            ],
            'nama_pasien' => [
                'type' => 'VARCHAR',
                'constraint' => 256,
            ],
            'alamat' => [
                'type' => 'VARCHAR',
                'constraint' => 512,
            ],
            'telpon' => [
                'type' => 'VARCHAR',
                'constraint' => 24,
            ],
            'jenis_kelamin' => [
                'type' => 'VARCHAR',
                'constraint' => 1,
            ],
            'tempat_lahir' => [
                'type' => 'VARCHAR',
                'constraint' => 256,
            ],
            'tanggal_lahir' => [
                'type' => 'DATE',
            ],
            'dokter' => [
                'type' => 'VARCHAR',
                'constraint' => 256,
            ],
            'tanggal_resep' => [
                'type' => 'DATETIME'
            ],
            'jumlah_resep' => [
                'type' => 'INT',
                'constraint' => 24,
            ],
            'total_biaya' => [
                'type' => 'INT',
                'constraint' => 24,
            ],
            'status' => [
                'type' => 'BOOLEAN'
            ],
        ]);
        $this->forge->addKey('id_resep', true);
        $this->forge->createTable('resep');
    }

    public function down()
    {
        $this->forge->dropTable('resep');
    }
}
