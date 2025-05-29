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

rl.question('Enter domain name: ', async (name) => {
    try {
        let domainId = await db('SELECT id FROM domains WHERE domain = ?', [
            name.trim(),
        ]);
        if (!domainId.length) {
            console.log('Domain not found.');
            rl.close();
            exit(1);
        }

        console.log(`Removing domain with name ${name}`);

        //remove the domain
        await db(
            `UPDATE domains SET deleted=1 WHERE domain='${name}' and admin=0`
        );
        console.log(`Removed domains: ${name}`);

        rl.close();
        exit(0);
    } catch (e) {
        console.error(e);
        rl.close();
        exit();
    }
});
