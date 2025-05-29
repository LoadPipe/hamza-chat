// Default configuration
const defaultConfig = {
    environment: 'production', // default
    websocket: {
        local: {
            protocol: 'ws',
            host: window.location.host,
            path: '/wss'
        },
        production: {
            protocol: 'wss',
            host: window.location.host,
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
    websocket: {
        ...defaultConfig.websocket,
        [serverConfig.environment || defaultConfig.environment]: {
            ...defaultConfig.websocket[serverConfig.environment || defaultConfig.environment]
        }
    }
};

// Helper function to get current WebSocket URL
export function getWebSocketUrl() {
    const env = config.environment;
    const wsConfig = config.websocket[env];
    const fullUrl = `${wsConfig.protocol}://${wsConfig.host}${wsConfig.path}`;
    // console.log('Full URL:', fullUrl);
    return fullUrl;
} 