// Default configuration
const defaultConfig = {
    environment: 'local',
    websocket: {
        local: {
            protocol: 'ws',
            host: '172.24.0.1',
            path: ':4444'
        },
        production: {
            protocol: 'wss',
            host: 'your-production-domain.com',
            path: '/wss'
        }
    }
};

// Fetch server configuration
let serverConfig = {};
try {
    const response = await fetch('/etc/config.json');
    serverConfig = await response.json();
} catch (error) {
    console.warn('Failed to load server config, using defaults:', error);
}

// Merge configurations
export const config = {
    ...defaultConfig,
    environment: serverConfig.environment || defaultConfig.environment,
    websocket: defaultConfig.websocket
};

// Helper function to get current WebSocket URL
export function getWebSocketUrl() {
    const env = config.environment;
    const wsConfig = config.websocket[env];
    const fullUrl = `${wsConfig.protocol}://${wsConfig.host}${wsConfig.path}`;
    console.log('Full URL:', fullUrl);
    return fullUrl;
} 