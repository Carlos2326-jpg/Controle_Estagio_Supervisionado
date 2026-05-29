<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
  protected function schedule(Schedule $schedule)
  {
    // Gerar alertas diariamente às 8h e 14h
    $schedule->command('alertas:gerar')
      ->twiceDaily(8, 14)
      ->withoutOverlapping()
      ->appendOutputTo(storage_path('logs/alertas.log'));

    // Verificar contratos vencidos a cada hora
    $schedule->command('contratos:verificar-vencidos')
      ->hourly();

    // Limpar logs antigos semanalmente
    $schedule->command('logs:limpar')
      ->weekly();
  }
}
