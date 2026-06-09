<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Curso;
use App\Models\Coordenador;
use App\Models\Empresa;
use App\Models\Instituicao;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        echo "\n🌱 Iniciando Seeder do Sistema de Estágios...\n";

        // ==========================================
        // 1. CRIAR INSTITUIÇÃO
        // ==========================================
        echo "📚 Criando Instituição...\n";
        
        $instituicao = Instituicao::create([
            'nome_instituicao' => 'Universidade Tecnológica do Paraná',
            'sigla' => 'UTP',
            'cnpj' => '12345678000199',
            'endereco' => 'Av. das Universidades, 1000',
            'cidade' => 'Curitiba',
            'estado' => 'PR',
            'telefone' => '(41) 3333-4444',
            'email_contato' => 'contato@utp.edu.br',
            'site' => 'https://www.utp.edu.br',
            'ativa' => true,
        ]);

        // ==========================================
        // 2. CRIAR CURSOS
        // ==========================================
        echo "📖 Criando Cursos...\n";
        
        $curso1 = Curso::create([
            'id_instituicao' => $instituicao->id,
            'nome' => 'Sistemas de Informação',
            'codigo' => 'SI001',
            'carga_horaria_estagio' => 300,
            'modalidade' => 'Presencial',
            'ativo' => true,
        ]);

        $curso2 = Curso::create([
            'id_instituicao' => $instituicao->id,
            'nome' => 'Análise e Desenvolvimento de Sistemas',
            'codigo' => 'ADS002',
            'carga_horaria_estagio' => 300,
            'modalidade' => 'Presencial',
            'ativo' => true,
        ]);

        // ==========================================
        // 3. CRIAR USUÁRIO ADMIN
        // ==========================================
        echo "👑 Criando Administrador...\n";

        User::create([
            'name' => 'Administrador do Sistema',
            'email' => 'admin@sistema.com',
            'password' => Hash::make('admin123'),
            'role' => 'admin',
        ]);

        // ==========================================
        // 4. CRIAR COORDENADOR
        // ==========================================
        echo "👨‍🏫 Criando Coordenador...\n";

        $coordUser = User::create([
            'name' => 'Dr. Carlos Alberto',
            'email' => 'carlos.alberto@coordenador.com',
            'password' => Hash::make('12345678'),
            'role' => 'coordenador',
        ]);

        Coordenador::create([
            'user_id' => $coordUser->id,
            'curso_id' => $curso1->id,
            'matricula_institucional' => 'COORD001',
            'telefone' => '(41) 98888-1111',
            'data_inicio_funcao' => '2023-01-10',
            'instituicao_id' => $instituicao->id,
            'status' => 'ativo',
        ]);

        // ==========================================
        // 5. CRIAR EMPRESAS FICTÍCIAS
        // ==========================================
        echo "🏢 Criando Empresas...\n";

        Empresa::create([
            'razao_social' => 'Tech Solutions Ltda',
            'nome_fantasia' => 'Tech Solutions',
            'cnpj' => '11222333000181',
            'email' => 'contato@techsolutions.com',
            'telefone' => '(41) 3333-1111',
            'cep' => '80000-000',
            'logradouro' => 'Av. Paulista',
            'numero' => '1000',
            'bairro' => 'Centro',
            'cidade' => 'Curitiba',
            'estado' => 'PR',
            'ramo_atividade' => 'Desenvolvimento de Software',
            'status' => 'ativa',
        ]);

        Empresa::create([
            'razao_social' => 'DataCloud S/A',
            'nome_fantasia' => 'DataCloud',
            'cnpj' => '33444555000199',
            'email' => 'contato@datacloud.com',
            'telefone' => '(41) 3333-2222',
            'cep' => '81000-000',
            'logradouro' => 'Rua da Tecnologia',
            'numero' => '500',
            'bairro' => 'Tecnoparque',
            'cidade' => 'Curitiba',
            'estado' => 'PR',
            'ramo_atividade' => 'Cloud Computing',
            'status' => 'ativa',
        ]);

        echo "\n✅ Seeder concluído com sucesso!\n";
        echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
        echo "📋 CREDENCIAIS PARA TESTE:\n";
        echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
        echo "👑 ADMIN:        admin@sistema.com / admin123\n";
        echo "👨‍🏫 COORDENADOR:  carlos.alberto@coordenador.com / 12345678\n";
        echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
        echo "📝 OBS: Alunos devem ser cadastrados pelo COORDENADOR\n";
        echo "🏢 Empresas podem se cadastrar pelo formulário de registro\n";
        echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
    }
}