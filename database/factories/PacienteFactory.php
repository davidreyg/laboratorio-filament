<?php

namespace Database\Factories;

use App\Models\TipoDocumento;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Paciente>
 */
class PacienteFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            // Add your model fields here
            'nombres' => $this->faker->firstName(),
            'apellido_paterno' => $this->faker->lastName(),
            'apellido_materno' => $this->faker->lastName(),
            'numero_documento' => $this->faker->randomNumber(9, true),
            'fecha_nacimiento' => now(),
            'edad' => $this->faker->numberBetween(10, 80),
            'sexo' => $this->faker->randomElement(['Masculino', 'Femenino']),
            'direccion' => $this->faker->streetAddress(),
            'telefono' => $this->faker->randomNumber(9, true),
            'historia_clinica' => $this->faker->randomNumber(6, true),
            'tipo_documento_id' => TipoDocumento::inRandomOrder()->value('id'),
        ];
    }
}
