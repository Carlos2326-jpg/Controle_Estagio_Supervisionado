<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\AlertaService;

class GerarAlertasVencimento extends Command
{
    protected $signature = 'alertas:gerar {--force : Forçar execução mesmo fora do horário}';
    protected $description = 'Gerar alertas automáticos de vencimento de contratos e pendências';
    
    protected $alertaService;
    
    public function __construct(AlertaService $alertaService)
    {
        parent::__construct();
        $this->alertaService = $alertaService;
    }
    
    public function handle()
    {
        $this->info('Iniciando geração de alertas...');
        
        // Verificar se deve executar (apenas em horário comercial se não for force)
        if (!$this->option('force') && !$this->isHorarioComercial()) {
            $this->warn('Execução fora do horário comercial. Use --force para executar.');
            return 0;
        }
        
        $startTime = microtime(true);
        
        try {
            // Gerar alertas de vencimento de contratos
            $this->info('Gerando alertas de vencimento de contratos...');
            $this->alertaService->gerarAlertasVencimentoContratos();
            
            // Gerar alertas de avaliações pendentes
            $this->info('Gerando alertas de avaliações pendentes...');
            $this->alertaService->gerarAlertasAvaliacoesPendentes();
            
            // Limpar alertas antigos
            $this->info('Limpando alertas antigos...');
            $this->alertaService->limparAlertasAntigos();
            
            $duration = round(microtime(true) - $startTime, 2);
            $this->info("Alertas gerados com sucesso em {$duration} segundos!");
            
            return 0;
            
        } catch (\Exception $e) {
            $this->error("Erro ao gerar alertas: " . $e->getMessage());
            return 1;
        }
    }
    
    private function isHorarioComercial()
    {
        $hora = now()->format('H');
        $dia = now()->dayOfWeek;
        
        // Segunda a Sexta, entre 8h e 18h
        return ($dia >= 1 && $dia <= 5) && ($hora >= 8 && $hora <= 18);
    }
}