class NotificationSystem {
    constructor() {
        this.pollInterval = null;
        this.lastCheck = null;
        this.init();
    }
    
    init() {
        this.startPolling();
        this.setupEventListeners();
        this.updateNotificationBadge();
    }
    
    startPolling() {
        // Verificar novos alertas a cada 30 segundos
        this.pollInterval = setInterval(() => {
            this.checkNewAlerts();
        }, 30000);
    }
    
    async checkNewAlerts() {
        try {
            const response = await fetch('/alertas/api/nao-lidos');
            const data = await response.json();
            
            if (data.total > 0) {
                this.showNotifications(data.alertas);
                this.updateNotificationBadge(data.total);
                
                // Enviar notificação do navegador se permitido
                if (Notification.permission === 'granted') {
                    new Notification('Novos Alertas', {
                        body: `Você tem ${data.total} novo(s) alerta(s)`,
                        icon: '/favicon.ico'
                    });
                }
            }
        } catch (error) {
            console.error('Erro ao buscar alertas:', error);
        }
    }
    
    showNotifications(alertas) {
        const container = document.getElementById('notifications-dropdown');
        if (!container) return;
        
        let html = '';
        alertas.forEach(alerta => {
            html += `
                <div class="dropdown-item notification-item ${alerta.cor}">
                    <div class="notification-icon">${alerta.icone}</div>
                    <div class="notification-content">
                        <div class="notification-title">${alerta.tipo}</div>
                        <div class="notification-message">${alerta.mensagem}</div>
                        <div class="notification-time">${alerta.data}</div>
                    </div>
                </div>
            `;
        });
        
        container.innerHTML = html;
    }
    
    updateNotificationBadge(count) {
        const badge = document.getElementById('notification-badge');
        if (badge) {
            if (count > 0) {
                badge.textContent = count > 99 ? '99+' : count;
                badge.style.display = 'inline-block';
            } else {
                badge.style.display = 'none';
            }
        }
    }
    
    setupEventListeners() {
        // Solicitar permissão para notificações
        if ('Notification' in window && Notification.permission === 'default') {
            Notification.requestPermission();
        }
        
        // Marcar alertas como lidos ao clicar
        document.addEventListener('click', (e) => {
            if (e.target.closest('.notification-item')) {
                this.markAsRead(e.target.closest('.notification-item').dataset.id);
            }
        });
    }
    
    async markAsRead(alertaId) {
        try {
            await fetch(`/alertas/${alertaId}/marcar-lido`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            });
        } catch (error) {
            console.error('Erro ao marcar alerta como lido:', error);
        }
    }
}

// Inicializar quando o DOM estiver pronto
document.addEventListener('DOMContentLoaded', () => {
    window.notificationSystem = new NotificationSystem();
});