<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\TipoDieta;
use App\Models\SubtipoDieta;
use App\Models\Dieta;

class DietaSeeder extends Seeder
{
    public function run(): void
    {
        // Tipos de dieta principales
        $tipoNormal = TipoDieta::firstOrCreate(
            ['nombre' => 'Normal'],
            ['descripcion' => 'Dietas normales sin restricciones especiales']
        );

        $tipoTerapeutica = TipoDieta::firstOrCreate(
            ['nombre' => 'Terapéutica'],
            ['descripcion' => 'Dietas con fines terapéuticos específicos']
        );

        $tipoEspecial = TipoDieta::firstOrCreate(
            ['nombre' => 'Especial'],
            ['descripcion' => 'Dietas con características especiales o restricciones']
        );

        $tipoModificada = TipoDieta::firstOrCreate(
            ['nombre' => 'Modificada en consistencia'],
            ['descripcion' => 'Dietas con modificación de textura']
        );

        $tipoNPO = TipoDieta::firstOrCreate(
            ['nombre' => 'NPO'],
            ['descripcion' => 'Nada por vía oral']
        );

        // Subtipos para Normal
        $subNormalCompleta = SubtipoDieta::firstOrCreate(
            ['nombre' => 'Completa', 'tipo_dieta_id' => $tipoNormal->id],
            ['descripcion' => 'Dieta normal completa']
        );

        // Subtipos para Terapéutica
        $subDiabetica = SubtipoDieta::firstOrCreate(
            ['nombre' => 'Diabética', 'tipo_dieta_id' => $tipoTerapeutica->id],
            ['descripcion' => 'Para pacientes con diabetes']
        );

        $subHiposodica = SubtipoDieta::firstOrCreate(
            ['nombre' => 'Hiposódica', 'tipo_dieta_id' => $tipoTerapeutica->id],
            ['descripcion' => 'Baja en sodio']
        );

        $subRenal = SubtipoDieta::firstOrCreate(
            ['nombre' => 'Renal', 'tipo_dieta_id' => $tipoTerapeutica->id],
            ['descripcion' => 'Para enfermedad renal']
        );

        $subHipocalorica = SubtipoDieta::firstOrCreate(
            ['nombre' => 'Hipocalórica', 'tipo_dieta_id' => $tipoTerapeutica->id],
            ['descripcion' => 'Baja en calorías']
        );

        // Subtipos para Especial
        $subVegetariana = SubtipoDieta::firstOrCreate(
            ['nombre' => 'Vegetariana', 'tipo_dieta_id' => $tipoEspecial->id],
            ['descripcion' => 'Sin carnes']
        );

        $subSinGluten = SubtipoDieta::firstOrCreate(
            ['nombre' => 'Sin gluten', 'tipo_dieta_id' => $tipoEspecial->id],
            ['descripcion' => 'Para celíacos']
        );

        // Subtipos para Modificada en consistencia
        $subBlanda = SubtipoDieta::firstOrCreate(
            ['nombre' => 'Blanda', 'tipo_dieta_id' => $tipoModificada->id],
            ['descripcion' => 'Fácil digestión']
        );

        $subLiquida = SubtipoDieta::firstOrCreate(
            ['nombre' => 'Líquida', 'tipo_dieta_id' => $tipoModificada->id],
            ['descripcion' => 'Solo líquidos']
        );

        $subPure = SubtipoDieta::firstOrCreate(
            ['nombre' => 'Puré', 'tipo_dieta_id' => $tipoModificada->id],
            ['descripcion' => 'Textura de puré']
        );

        // Subtipos para NPO
        $subNPO = SubtipoDieta::firstOrCreate(
            ['nombre' => 'NPO', 'tipo_dieta_id' => $tipoNPO->id],
            ['descripcion' => 'Nada por vía oral']
        );

        // Dietas específicas
        // Normal
        Dieta::firstOrCreate(
            ['nombre' => 'Normal', 'tipo_dieta_id' => $tipoNormal->id, 'subtipo_dieta_id' => $subNormalCompleta->id],
            ['descripcion' => 'Dieta normal sin restricciones']
        );

        // Diabéticas
        Dieta::firstOrCreate(
            ['nombre' => 'Diabética 1500 cal', 'tipo_dieta_id' => $tipoTerapeutica->id, 'subtipo_dieta_id' => $subDiabetica->id],
            ['descripcion' => 'Dieta para diabéticos de 1500 calorías']
        );
        Dieta::firstOrCreate(
            ['nombre' => 'Diabética 1800 cal', 'tipo_dieta_id' => $tipoTerapeutica->id, 'subtipo_dieta_id' => $subDiabetica->id],
            ['descripcion' => 'Dieta para diabéticos de 1800 calorías']
        );
        Dieta::firstOrCreate(
            ['nombre' => 'Diabética 2000 cal', 'tipo_dieta_id' => $tipoTerapeutica->id, 'subtipo_dieta_id' => $subDiabetica->id],
            ['descripcion' => 'Dieta para diabéticos de 2000 calorías']
        );

        // Hiposódicas
        Dieta::firstOrCreate(
            ['nombre' => 'Hiposódica estricta', 'tipo_dieta_id' => $tipoTerapeutica->id, 'subtipo_dieta_id' => $subHiposodica->id],
            ['descripcion' => 'Dieta baja en sodio estricta']
        );
        Dieta::firstOrCreate(
            ['nombre' => 'Hiposódica moderada', 'tipo_dieta_id' => $tipoTerapeutica->id, 'subtipo_dieta_id' => $subHiposodica->id],
            ['descripcion' => 'Dieta baja en sodio moderada']
        );

        // Renales
        Dieta::firstOrCreate(
            ['nombre' => 'Renal sin diálisis', 'tipo_dieta_id' => $tipoTerapeutica->id, 'subtipo_dieta_id' => $subRenal->id],
            ['descripcion' => 'Para pacientes renales sin diálisis']
        );
        Dieta::firstOrCreate(
            ['nombre' => 'Renal con diálisis', 'tipo_dieta_id' => $tipoTerapeutica->id, 'subtipo_dieta_id' => $subRenal->id],
            ['descripcion' => 'Para pacientes renales con diálisis']
        );

        // Hipocalóricas
        Dieta::firstOrCreate(
            ['nombre' => 'Hipocalórica 1200 cal', 'tipo_dieta_id' => $tipoTerapeutica->id, 'subtipo_dieta_id' => $subHipocalorica->id],
            ['descripcion' => 'Dieta baja en calorías de 1200 cal']
        );

        // Blandas
        Dieta::firstOrCreate(
            ['nombre' => 'Blanda', 'tipo_dieta_id' => $tipoModificada->id, 'subtipo_dieta_id' => $subBlanda->id],
            ['descripcion' => 'Dieta de fácil digestión']
        );

        // Líquidas
        Dieta::firstOrCreate(
            ['nombre' => 'Líquida clara', 'tipo_dieta_id' => $tipoModificada->id, 'subtipo_dieta_id' => $subLiquida->id],
            ['descripcion' => 'Solo líquidos transparentes']
        );
        Dieta::firstOrCreate(
            ['nombre' => 'Líquida completa', 'tipo_dieta_id' => $tipoModificada->id, 'subtipo_dieta_id' => $subLiquida->id],
            ['descripcion' => 'Líquidos más espesos']
        );

        // Puré
        Dieta::firstOrCreate(
            ['nombre' => 'Puré', 'tipo_dieta_id' => $tipoModificada->id, 'subtipo_dieta_id' => $subPure->id],
            ['descripcion' => 'Alimentos en textura de puré']
        );

        // Vegetariana
        Dieta::firstOrCreate(
            ['nombre' => 'Vegetariana', 'tipo_dieta_id' => $tipoEspecial->id, 'subtipo_dieta_id' => $subVegetariana->id],
            ['descripcion' => 'Sin carnes']
        );

        // Sin gluten
        Dieta::firstOrCreate(
            ['nombre' => 'Sin gluten', 'tipo_dieta_id' => $tipoEspecial->id, 'subtipo_dieta_id' => $subSinGluten->id],
            ['descripcion' => 'Para celíacos']
        );

        // NPO
        Dieta::firstOrCreate(
            ['nombre' => 'NPO', 'tipo_dieta_id' => $tipoNPO->id, 'subtipo_dieta_id' => $subNPO->id],
            ['descripcion' => 'Nada por vía oral']
        );
        Dieta::firstOrCreate(
            ['nombre' => 'Nada por vía oral', 'tipo_dieta_id' => $tipoNPO->id, 'subtipo_dieta_id' => $subNPO->id],
            ['descripcion' => 'NPO']
        );
    }
}
