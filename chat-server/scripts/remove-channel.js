const readline = require('readline');
const mysql = require('mysql');

const config = require('../config.json');
const { exit } = require('process');

const sql = mysql.createPool({
    host: config.sqlHost,
    user: config.sqlUser,
    password: config.sqlPass,
    database: config.sqlDatabase,
    port: config.sqlPort,
    charset: 'utf8mb4',
});

const rl = readline.createInterface({
    input: process.stdin,
    output: process.stdout,
});

async function db(query, values = []) {
    let result = new Promise((resolve) => {
        sql.query(query, values, (e, r, f) => {
            try {
                resolve(JSON.parse(JSON.stringify(r)));
            } catch {
                console.log(e);
                exit();
            }
        });
    });
    return await result;
}

rl.question('Enter channel name: ', async (channelName) => {
    try {
        console.log(`Deleting channel with name ${channelName}`);
        await db(`REMOVE FROM domains WHERE name = '${channelName}'`);
        console.log(`Removed channel: ${channelName}`);

        rl.close();
        exit(0);
    } catch (e) {
        console.error(e);
        exit();
    }
});
