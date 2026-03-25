<?php

namespace Database\Seeders;

use App\Models\Bounty;
use App\Models\Submission;
use App\Models\User;
use App\Models\WalletTransaction;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $user1 = User::factory()->create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'balance' => 500.00,
        ]);

        $user2 = User::factory()->create([
            'name' => 'Student User',
            'email' => 'student@example.com',
            'balance' => 100.00,
        ]);

        $user3 = User::factory()->create([
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'balance' => 250.00,
        ]);

        $bounty1 = Bounty::factory()->create([
            'title' => 'Ajuda com Cálculo Diferencial',
            'description' => 'Preciso de ajuda para resolver exercícios de cálculo diferencial, especificamente sobre derivadas e integrais. Os exercícios estão em anexo.',
            'subject' => 'Matemática',
            'price' => 50.00,
            'user_id' => $user1->id,
        ]);

        $bounty2 = Bounty::factory()->create([
            'title' => 'Explicação de Estruturas de Dados',
            'description' => 'Procuro alguém que me explique árvores binárias, listas encadeadas e grafos. Preciso entender os conceitos básicos e como implementar em Python.',
            'subject' => 'Programação',
            'price' => 75.00,
            'user_id' => $user2->id,
        ]);

        $bounty3 = Bounty::factory()->create([
            'title' => 'Revisão de Física Quântica',
            'description' => 'Preciso de uma revisão completa sobre mecânica quântica para minha prova. Vou precisar de ajuda com princípio da incerteza, equação de Schrödinger e tunelamento quântico.',
            'subject' => 'Física',
            'price' => 100.00,
            'user_id' => $user3->id,
        ]);

        $bounty4 = Bounty::factory()->inProgress()->create([
            'title' => 'Redação sobre Globalização',
            'description' => 'Preciso de ajuda para escrever uma redação dissertativa sobre os impactos da globalização na economia brasileira.',
            'subject' => 'Português',
            'price' => 30.00,
            'user_id' => $user1->id,
        ]);

        $bounty5 = Bounty::factory()->completed()->create([
            'title' => 'Resolução de Equações Químicas',
            'description' => 'Preciso resolver 20 equações químicas de balanceamento para meu dever de casa.',
            'subject' => 'Química',
            'price' => 40.00,
            'user_id' => $user2->id,
        ]);

        Submission::factory()->create([
            'content' => 'Posso te ajudar com esses exercícios. Tenho experiência em cálculo diferencial e integral.',
            'bounty_id' => $bounty1->id,
            'user_id' => $user3->id,
            'status' => 'PENDING',
        ]);

        Submission::factory()->create([
            'content' => 'Olá! Sou professor de programação e posso te ajudar com estruturas de dados.',
            'bounty_id' => $bounty2->id,
            'user_id' => $user1->id,
            'status' => 'PENDING',
        ]);

        WalletTransaction::factory()->deposit()->create([
            'user_id' => $user1->id,
            'description' => 'Depósito inicial',
        ]);

        WalletTransaction::factory()->earning()->create([
            'user_id' => $user2->id,
            'description' => 'Ganho por submissão aceita',
        ]);
    }
}
