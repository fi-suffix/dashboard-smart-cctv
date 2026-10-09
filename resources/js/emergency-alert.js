/**
 * Emergency Alert System
 * Handles browser audio alerts and real-time notifications
 */

class EmergencyAlertSystem {
    constructor() {
        this.audio = null;
        this.isPlaying = false;
        this.lastAlert = null;
        this.pollInterval = null;
        this.checkInterval = 30000; // 30 seconds
        this.init();
    }

    init() {
        // Create audio element
        this.audio = new Audio('/audio/alert.wav');
        this.audio.loop = false;
        this.audio.volume = 1.0;

        // Load audio
        this.audio.load();

        // Start polling for new alerts
        this.startPolling();

        // Listen for visibility change
        document.addEventListener('visibilitychange', () => {
            if (document.visibilityState === 'visible') {
                this.checkForAlerts();
            }
        });
    }

    startPolling() {
        // Check immediately
        this.checkForAlerts();

        // Then poll periodically
        this.pollInterval = setInterval(() => {
            this.checkForAlerts();
        }, this.checkInterval);
    }

    stopPolling() {
        if (this.pollInterval) {
            clearInterval(this.pollInterval);
            this.pollInterval = null;
        }
    }

    async checkForAlerts() {
        try {
            const response = await fetch('/api/internal/emergency-events/latest?minutes=5&limit=1');
            if (!response.ok) return;

            const data = await response.json();

            if (data.events && data.events.length > 0) {
                const event = data.events[0];

                // Check if this is a new alert
                if (this.lastAlert !== event.event_uuid) {
                    this.lastAlert = event.event_uuid;

                    // Only play sound if it's a critical, active event
                    if (event.status === 'active' && event.severity === 'critical') {
                        this.playAlert();
                        this.showNotification(event);
                    }
                }
            }
        } catch (error) {
            console.error('Failed to check for emergency alerts:', error);
        }
    }

    playAlert() {
        if (this.isPlaying) return;

        this.isPlaying = true;

        try {
            // Reset and play
            this.audio.currentTime = 0;

            // Play 3 times with delay
            this.playMultiple(3);
        } catch (error) {
            console.error('Failed to play alert sound:', error);
            this.isPlaying = false;
        }
    }

    playMultiple(times) {
        let count = 0;
        const playOnce = () => {
            if (count >= times) {
                this.isPlaying = false;
                return;
            }

            this.audio.play().then(() => {
                count++;
                setTimeout(playOnce, 2500); // 2.5s between plays
            }).catch(() => {
                this.isPlaying = false;
            });
        };

        playOnce();
    }

    showNotification(event) {
        // Use browser notification API if available
        if ('Notification' in window) {
            if (Notification.permission === 'granted') {
                this.createNotification(event);
            } else if (Notification.permission !== 'denied') {
                Notification.requestPermission().then(permission => {
                    if (permission === 'granted') {
                        this.createNotification(event);
                    }
                });
            }
        }
    }

    createNotification(event) {
        const notification = new Notification('🚨 Emergency Alert', {
            body: `${event.type_label} detected on ${event.camera?.name || 'Camera ' + event.camera_id}`,
            icon: '/favicon.ico',
            tag: event.event_uuid,
            requireInteraction: true,
        });

        notification.onclick = () => {
            window.focus();
            window.location.href = `/dashboard/emergency/${event.id}`;
            notification.close();
        };

        // Auto close after 30 seconds
        setTimeout(() => notification.close(), 30000);
    }

    stopAlert() {
        if (this.audio) {
            this.audio.pause();
            this.audio.currentTime = 0;
        }
        this.isPlaying = false;
    }

    testSound() {
        this.playAlert();
    }
}

// Initialize on page load
document.addEventListener('DOMContentLoaded', () => {
    // Only initialize on authenticated pages
    if (document.body.classList.contains('authenticated')) {
        window.emergencyAlert = new EmergencyAlertSystem();
    }
});

// Expose for manual testing
window.testEmergencyAlert = () => {
    if (window.emergencyAlert) {
        window.emergencyAlert.testSound();
    }
};
