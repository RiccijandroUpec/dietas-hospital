<?php

namespace Database\Seeders;

use App\Models\Paciente;
use Illuminate\Database\Seeder;

class RestorePacientesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Datos de pacientes del backup_final.sql (75 pacientes)
        $pacientes = [
            ['id' => 3, 'nombre' => 'Enrique', 'apellido' => 'Borja', 'cedula' => '1003313119', 'estado' => 'alta', 'edad' => 50, 'servicio_id' => 13, 'cama_id' => null, 'condicion' => 'diabetico,hiposodico'],
            ['id' => 4, 'nombre' => 'Emily', 'apellido' => 'Pupiales', 'cedula' => '1050795440', 'estado' => 'alta', 'edad' => 7, 'servicio_id' => 13, 'cama_id' => null, 'condicion' => 'normal'],
            ['id' => 5, 'nombre' => 'Jhon', 'apellido' => 'Vallejo', 'cedula' => '1050126307', 'estado' => 'alta', 'edad' => 11, 'servicio_id' => 13, 'cama_id' => null, 'condicion' => 'normal'],
            ['id' => 6, 'nombre' => 'Denis', 'apellido' => 'Carrera', 'cedula' => '1050032885', 'estado' => 'alta', 'edad' => 13, 'servicio_id' => 13, 'cama_id' => null, 'condicion' => 'normal'],
            ['id' => 7, 'nombre' => 'Samy', 'apellido' => 'Matango', 'cedula' => '1050975810', 'estado' => 'alta', 'edad' => 5, 'servicio_id' => 13, 'cama_id' => null, 'condicion' => 'normal'],
            ['id' => 8, 'nombre' => 'Isabella', 'apellido' => 'Sierra', 'cedula' => '1751726355', 'estado' => 'alta', 'edad' => 14, 'servicio_id' => 13, 'cama_id' => null, 'condicion' => 'normal'],
            ['id' => 9, 'nombre' => 'Daniela', 'apellido' => 'Reyes', 'cedula' => '1050641289', 'estado' => 'alta', 'edad' => 9, 'servicio_id' => 13, 'cama_id' => null, 'condicion' => 'normal'],
            ['id' => 10, 'nombre' => 'Fernando', 'apellido' => 'Farinango', 'cedula' => '1050338878', 'estado' => 'alta', 'edad' => 11, 'servicio_id' => 13, 'cama_id' => null, 'condicion' => 'normal'],
            ['id' => 11, 'nombre' => 'Lizbeth', 'apellido' => 'Salas', 'cedula' => '1050702214', 'estado' => 'alta', 'edad' => 8, 'servicio_id' => 13, 'cama_id' => null, 'condicion' => 'normal'],
            ['id' => 12, 'nombre' => 'Yuri', 'apellido' => 'Ramos', 'cedula' => '1005230402', 'estado' => 'alta', 'edad' => 12, 'servicio_id' => 13, 'cama_id' => null, 'condicion' => 'normal'],
            ['id' => 13, 'nombre' => 'Lucia', 'apellido' => 'Angulo', 'cedula' => '1002961421', 'estado' => 'hospitalizado', 'edad' => 45, 'servicio_id' => 12, 'cama_id' => null, 'condicion' => 'normal'],
            ['id' => 14, 'nombre' => 'Francisco', 'apellido' => 'Pabon', 'cedula' => '1051244737', 'estado' => 'hospitalizado', 'edad' => 1, 'servicio_id' => 10, 'cama_id' => null, 'condicion' => 'normal'],
            ['id' => 15, 'nombre' => 'Jordan', 'apellido' => 'Ormaza', 'cedula' => '1051375945', 'estado' => 'alta', 'edad' => 1, 'servicio_id' => 13, 'cama_id' => null, 'condicion' => 'normal'],
            ['id' => 16, 'nombre' => 'Jenifer', 'apellido' => 'Colimba', 'cedula' => '1050351038', 'estado' => 'hospitalizado', 'edad' => 15, 'servicio_id' => 7, 'cama_id' => 96, 'condicion' => 'normal'],
            ['id' => 17, 'nombre' => 'Nelson', 'apellido' => 'Moreno', 'cedula' => '1001601671', 'estado' => 'alta', 'edad' => 59, 'servicio_id' => 13, 'cama_id' => null, 'condicion' => 'diabetico'],
            ['id' => 18, 'nombre' => 'Luis', 'apellido' => 'Sanipatin', 'cedula' => '1001686904', 'estado' => 'alta', 'edad' => 59, 'servicio_id' => 13, 'cama_id' => null, 'condicion' => 'diabetico'],
            ['id' => 19, 'nombre' => 'Jose', 'apellido' => 'Paz', 'cedula' => '1079172257', 'estado' => 'alta', 'edad' => 85, 'servicio_id' => 13, 'cama_id' => null, 'condicion' => 'normal'],
            ['id' => 20, 'nombre' => 'Hernan', 'apellido' => 'Montesinos', 'cedula' => '1001361623', 'estado' => 'hospitalizado', 'edad' => 64, 'servicio_id' => 8, 'cama_id' => 135, 'condicion' => 'diabetico'],
            ['id' => 21, 'nombre' => 'Julio', 'apellido' => 'Picuasi', 'cedula' => '1001593431', 'estado' => 'alta', 'edad' => 58, 'servicio_id' => 13, 'cama_id' => null, 'condicion' => 'diabetico'],
            ['id' => 22, 'nombre' => 'Jorge', 'apellido' => 'Valles', 'cedula' => '1000881350', 'estado' => 'hospitalizado', 'edad' => 71, 'servicio_id' => 8, 'cama_id' => 137, 'condicion' => 'normal'],
            ['id' => 23, 'nombre' => 'Juan', 'apellido' => 'Fuentes', 'cedula' => '1000981314', 'estado' => 'hospitalizado', 'edad' => 68, 'servicio_id' => 8, 'cama_id' => 138, 'condicion' => 'diabetico'],
            ['id' => 24, 'nombre' => 'Byron', 'apellido' => 'Pozo', 'cedula' => '1001833571', 'estado' => 'hospitalizado', 'edad' => 56, 'servicio_id' => 8, 'cama_id' => 165, 'condicion' => 'normal'],
            ['id' => 25, 'nombre' => 'Segundo', 'apellido' => 'Santacruz', 'cedula' => '1000288363', 'estado' => 'hospitalizado', 'edad' => 76, 'servicio_id' => 8, 'cama_id' => 163, 'condicion' => 'hiposodico'],
            ['id' => 26, 'nombre' => 'Elizabeth', 'apellido' => 'Bustos', 'cedula' => '0406805768', 'estado' => 'hospitalizado', 'edad' => 56, 'servicio_id' => 8, 'cama_id' => 143, 'condicion' => 'hiposodico'],
            ['id' => 27, 'nombre' => 'Pablo', 'apellido' => 'Santander', 'cedula' => '0804441590', 'estado' => 'hospitalizado', 'edad' => 50, 'servicio_id' => 8, 'cama_id' => 148, 'condicion' => 'normal'],
            ['id' => 28, 'nombre' => 'Angel', 'apellido' => 'Robalino', 'cedula' => '1000002210', 'estado' => 'hospitalizado', 'edad' => 82, 'servicio_id' => 8, 'cama_id' => 152, 'condicion' => 'diabetico'],
            ['id' => 29, 'nombre' => 'Enma', 'apellido' => 'Benavides', 'cedula' => '0480488545', 'estado' => 'hospitalizado', 'edad' => 74, 'servicio_id' => 8, 'cama_id' => 155, 'condicion' => 'diabetico'],
            ['id' => 30, 'nombre' => 'Elizabeth', 'apellido' => 'Morales', 'cedula' => '1003097035', 'estado' => 'hospitalizado', 'edad' => 46, 'servicio_id' => 9, 'cama_id' => 168, 'condicion' => 'diabetico'],
            ['id' => 31, 'nombre' => 'Blanca', 'apellido' => 'Narvaez', 'cedula' => '1001195633', 'estado' => 'hospitalizado', 'edad' => 68, 'servicio_id' => 9, 'cama_id' => 169, 'condicion' => 'normal'],
            ['id' => 32, 'nombre' => 'Segundo', 'apellido' => 'Torres', 'cedula' => '1102087663', 'estado' => 'alta', 'edad' => 66, 'servicio_id' => 13, 'cama_id' => null, 'condicion' => 'diabetico'],
            ['id' => 33, 'nombre' => 'German', 'apellido' => 'Lozano', 'cedula' => '1722221197', 'estado' => 'hospitalizado', 'edad' => 32, 'servicio_id' => 4, 'cama_id' => 51, 'condicion' => 'normal'],
            ['id' => 34, 'nombre' => 'Washington', 'apellido' => 'Congo', 'cedula' => '1002335956', 'estado' => 'hospitalizado', 'edad' => 51, 'servicio_id' => 7, 'cama_id' => 109, 'condicion' => 'diabetico'],
            ['id' => 35, 'nombre' => 'Veronica', 'apellido' => 'Sapuyes', 'cedula' => '1003903927', 'estado' => 'hospitalizado', 'edad' => 37, 'servicio_id' => 4, 'cama_id' => 50, 'condicion' => 'normal'],
            ['id' => 36, 'nombre' => 'María', 'apellido' => 'Almeida', 'cedula' => '1001074770', 'estado' => 'hospitalizado', 'edad' => 72, 'servicio_id' => 8, 'cama_id' => 140, 'condicion' => 'normal'],
            ['id' => 37, 'nombre' => 'Andrew', 'apellido' => 'Guamialamag', 'cedula' => '1005072317', 'estado' => 'hospitalizado', 'edad' => 26, 'servicio_id' => 12, 'cama_id' => 232, 'condicion' => 'normal'],
            ['id' => 39, 'nombre' => 'Rosa', 'apellido' => 'Angulo', 'cedula' => '1004103204', 'estado' => 'hospitalizado', 'edad' => 32, 'servicio_id' => 8, 'cama_id' => 164, 'condicion' => 'normal'],
            ['id' => 40, 'nombre' => 'Hugo', 'apellido' => 'Mejía', 'cedula' => '1003182894', 'estado' => 'hospitalizado', 'edad' => 42, 'servicio_id' => 8, 'cama_id' => 149, 'condicion' => 'normal'],
            ['id' => 41, 'nombre' => 'Melany', 'apellido' => 'Simbaña', 'cedula' => '1004560320', 'estado' => 'hospitalizado', 'edad' => 23, 'servicio_id' => 8, 'cama_id' => 154, 'condicion' => 'normal'],
            ['id' => 42, 'nombre' => 'Enrique', 'apellido' => 'Borja', 'cedula' => '0400800975', 'estado' => 'hospitalizado', 'edad' => 59, 'servicio_id' => 7, 'cama_id' => 98, 'condicion' => 'diabetico,hiposodico'],
            ['id' => 43, 'nombre' => 'Alicia', 'apellido' => 'Gomez', 'cedula' => '1000516177', 'estado' => 'hospitalizado', 'edad' => 88, 'servicio_id' => 7, 'cama_id' => 101, 'condicion' => 'diabetico,hiposodico'],
            ['id' => 44, 'nombre' => 'Maria', 'apellido' => 'Lucero', 'cedula' => '0400419271', 'estado' => 'hospitalizado', 'edad' => 71, 'servicio_id' => 7, 'cama_id' => 133, 'condicion' => 'hiposodico'],
            ['id' => 45, 'nombre' => 'Romero', 'apellido' => 'Montiel', 'cedula' => '1000000000', 'estado' => 'hospitalizado', 'edad' => 8, 'servicio_id' => 10, 'cama_id' => null, 'condicion' => 'normal'],
            ['id' => 46, 'nombre' => 'Lenin', 'apellido' => 'Tadeo', 'cedula' => '1050724895', 'estado' => 'hospitalizado', 'edad' => 8, 'servicio_id' => 10, 'cama_id' => null, 'condicion' => 'normal'],
            ['id' => 47, 'nombre' => 'Mikaela', 'apellido' => 'Obando', 'cedula' => '1002003002', 'estado' => 'hospitalizado', 'edad' => 25, 'servicio_id' => 10, 'cama_id' => 207, 'condicion' => 'normal'],
            ['id' => 48, 'nombre' => 'Colon', 'apellido' => 'Cuamacas', 'cedula' => '0400211140', 'estado' => 'hospitalizado', 'edad' => 86, 'servicio_id' => 7, 'cama_id' => 119, 'condicion' => 'normal'],
            ['id' => 49, 'nombre' => 'Segundo', 'apellido' => 'Sanchez', 'cedula' => '120007464', 'estado' => 'hospitalizado', 'edad' => 81, 'servicio_id' => 8, 'cama_id' => 150, 'condicion' => 'normal'],
            ['id' => 50, 'nombre' => 'Oswaldo', 'apellido' => 'Campo', 'cedula' => '1003729421', 'estado' => 'hospitalizado', 'edad' => 37, 'servicio_id' => 8, 'cama_id' => 146, 'condicion' => 'normal'],
            ['id' => 51, 'nombre' => 'Emily', 'apellido' => 'Pilamanga', 'cedula' => '1050492857', 'estado' => 'hospitalizado', 'edad' => 11, 'servicio_id' => 10, 'cama_id' => null, 'condicion' => 'normal'],
            ['id' => 52, 'nombre' => 'Drewn', 'apellido' => 'Vavenzul', 'cedula' => '1002003004', 'estado' => 'hospitalizado', 'edad' => 10, 'servicio_id' => 10, 'cama_id' => 196, 'condicion' => 'normal'],
            ['id' => 53, 'nombre' => 'Gisela', 'apellido' => 'Moreno', 'cedula' => '1050714623', 'estado' => 'hospitalizado', 'edad' => 8, 'servicio_id' => 10, 'cama_id' => null, 'condicion' => 'normal'],
            ['id' => 54, 'nombre' => 'Martha', 'apellido' => 'Alvarez', 'cedula' => '00000000', 'estado' => 'hospitalizado', 'edad' => 56, 'servicio_id' => 8, 'cama_id' => 158, 'condicion' => 'diabetico'],
            ['id' => 55, 'nombre' => 'Ian', 'apellido' => 'Carcelón', 'cedula' => '1050891579', 'estado' => 'hospitalizado', 'edad' => 6, 'servicio_id' => 10, 'cama_id' => null, 'condicion' => 'normal'],
            ['id' => 56, 'nombre' => 'Darwin', 'apellido' => 'Arias', 'cedula' => '1051284493', 'estado' => 'hospitalizado', 'edad' => 1, 'servicio_id' => 10, 'cama_id' => 202, 'condicion' => 'normal'],
            ['id' => 57, 'nombre' => 'Lucas', 'apellido' => 'Anrango', 'cedula' => '1051268223', 'estado' => 'hospitalizado', 'edad' => 1, 'servicio_id' => 10, 'cama_id' => 203, 'condicion' => 'normal'],
            ['id' => 58, 'nombre' => 'Ian', 'apellido' => 'Campues', 'cedula' => '1051270708', 'estado' => 'hospitalizado', 'edad' => 1, 'servicio_id' => 10, 'cama_id' => null, 'condicion' => 'normal'],
            ['id' => 59, 'nombre' => 'Ailani', 'apellido' => 'Mueses', 'cedula' => '105134089', 'estado' => 'hospitalizado', 'edad' => 1, 'servicio_id' => 10, 'cama_id' => 206, 'condicion' => 'normal'],
            ['id' => 60, 'nombre' => 'Junior', 'apellido' => 'Paredes', 'cedula' => '1051150744', 'estado' => 'hospitalizado', 'edad' => 3, 'servicio_id' => 10, 'cama_id' => 208, 'condicion' => 'normal'],
            ['id' => 61, 'nombre' => 'Mercedes', 'apellido' => 'Ponce', 'cedula' => '1000183150', 'estado' => 'hospitalizado', 'edad' => 85, 'servicio_id' => 7, 'cama_id' => 99, 'condicion' => 'normal'],
            ['id' => 62, 'nombre' => 'Richard', 'apellido' => 'Rodriguez', 'cedula' => '0450022686', 'estado' => 'hospitalizado', 'edad' => 26, 'servicio_id' => 10, 'cama_id' => null, 'condicion' => 'normal'],
            ['id' => 63, 'nombre' => 'JUDITH', 'apellido' => 'ERAS', 'cedula' => '0400785960', 'estado' => 'hospitalizado', 'edad' => 59, 'servicio_id' => 7, 'cama_id' => 104, 'condicion' => 'diabetico'],
            ['id' => 64, 'nombre' => 'Nelson', 'apellido' => 'Bolaños', 'cedula' => '1004077952', 'estado' => 'hospitalizado', 'edad' => 55, 'servicio_id' => 8, 'cama_id' => 136, 'condicion' => 'diabetico'],
            ['id' => 65, 'nombre' => 'Junior', 'apellido' => 'Caicedo', 'cedula' => '0851282046', 'estado' => 'hospitalizado', 'edad' => 9, 'servicio_id' => 10, 'cama_id' => 189, 'condicion' => 'normal'],
            ['id' => 66, 'nombre' => 'Gemma', 'apellido' => 'Alvarez', 'cedula' => '1050836954', 'estado' => 'hospitalizado', 'edad' => 7, 'servicio_id' => 10, 'cama_id' => 192, 'condicion' => 'normal'],
            ['id' => 67, 'nombre' => 'Diego', 'apellido' => 'Tuquerres', 'cedula' => '1754893648', 'estado' => 'hospitalizado', 'edad' => 12, 'servicio_id' => 10, 'cama_id' => 193, 'condicion' => 'normal'],
            ['id' => 68, 'nombre' => 'Sebastian', 'apellido' => 'Galeano', 'cedula' => '1050561784', 'estado' => 'hospitalizado', 'edad' => 10, 'servicio_id' => 10, 'cama_id' => 197, 'condicion' => 'normal'],
            ['id' => 69, 'nombre' => 'Liam', 'apellido' => 'Muñoz', 'cedula' => '1050930765', 'estado' => 'hospitalizado', 'edad' => 6, 'servicio_id' => 10, 'cama_id' => 199, 'condicion' => 'normal'],
            ['id' => 70, 'nombre' => 'Meybel', 'apellido' => 'Benalcazar', 'cedula' => '1050825122', 'estado' => 'hospitalizado', 'edad' => 7, 'servicio_id' => 10, 'cama_id' => 201, 'condicion' => 'normal'],
            ['id' => 71, 'nombre' => 'Sky', 'apellido' => 'Lopez', 'cedula' => '1762632808', 'estado' => 'hospitalizado', 'edad' => 10, 'servicio_id' => 10, 'cama_id' => 210, 'condicion' => 'normal'],
            ['id' => 72, 'nombre' => 'Javier', 'apellido' => 'Oñate', 'cedula' => '1308217924', 'estado' => 'hospitalizado', 'edad' => 41, 'servicio_id' => 8, 'cama_id' => null, 'condicion' => 'diabetico'],
            ['id' => 73, 'nombre' => 'Olga', 'apellido' => 'Tulcín', 'cedula' => '1002227567', 'estado' => 'hospitalizado', 'edad' => 50, 'servicio_id' => 7, 'cama_id' => null, 'condicion' => 'normal'],
            ['id' => 74, 'nombre' => 'Rosabel', 'apellido' => 'Dovale', 'cedula' => '251229248', 'estado' => 'hospitalizado', 'edad' => 34, 'servicio_id' => 4, 'cama_id' => 48, 'condicion' => 'diabetico'],
            ['id' => 75, 'nombre' => 'Néstor', 'apellido' => 'Félix', 'cedula' => '1001088044', 'estado' => 'hospitalizado', 'edad' => 67, 'servicio_id' => 4, 'cama_id' => null, 'condicion' => 'diabetico'],
            ['id' => 76, 'nombre' => 'Ximena', 'apellido' => 'Heredia', 'cedula' => '0401192943', 'estado' => 'hospitalizado', 'edad' => 48, 'servicio_id' => 4, 'cama_id' => null, 'condicion' => 'diabetico'],
            ['id' => 77, 'nombre' => 'Dayana', 'apellido' => 'Narvaez', 'cedula' => '1004491138', 'estado' => 'alta', 'edad' => 28, 'servicio_id' => 13, 'cama_id' => null, 'condicion' => 'normal'],
        ];

        $insertados = 0;
        $omitidos = 0;

        foreach ($pacientes as $data) {
            // Verificar que el servicio existe antes de insertar
            $servicio = \App\Models\Servicio::find($data['servicio_id']);
            
            if (!$servicio) {
                $this->command->warn("⚠ Servicio ID {$data['servicio_id']} no existe. Omitiendo paciente {$data['nombre']} {$data['apellido']}");
                $omitidos++;
                continue;
            }

            // Verificar que la cama existe si está asignada
            if ($data['cama_id']) {
                $cama = \App\Models\Cama::find($data['cama_id']);
                if (!$cama) {
                    $this->command->warn("⚠ Cama ID {$data['cama_id']} no existe. Asignando null a paciente {$data['nombre']} {$data['apellido']}");
                    $data['cama_id'] = null;
                }
            }

            Paciente::updateOrCreate(
                ['cedula' => $data['cedula']],
                [
                    'nombre' => $data['nombre'],
                    'apellido' => $data['apellido'],
                    'estado' => $data['estado'],
                    'edad' => $data['edad'],
                    'servicio_id' => $data['servicio_id'],
                    'cama_id' => $data['cama_id'],
                    'condicion' => $data['condicion'],
                ]
            );
            $insertados++;
        }

        $this->command->info("✓ {$insertados} pacientes restaurados");
        if ($omitidos > 0) {
            $this->command->warn("⚠ {$omitidos} pacientes omitidos (servicio no existe)");
        }
    }
}
