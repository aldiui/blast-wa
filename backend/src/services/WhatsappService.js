import { makeWASocket, useMultiFileAuthState, DisconnectReason } from '@whiskeysockets/baileys';
import NumberHelper from '../helpers/NumberHelper.js';
import qrImage from 'qr-image';
import fs from 'fs';
import Boom from '@hapi/boom';

let sock;

export const connectWhatsApp = async () => {
    const { state, saveCreds } = await useMultiFileAuthState('./src/cache');
    sock = makeWASocket({
        auth: state,
        printQRInTerminal: true,
    });

    sock.ev.on('connection.update', (update) => {
        const { connection, lastDisconnect } = update;

        if (connection === 'close') {
            const reason = Boom.isBoom(lastDisconnect?.error)
                ? lastDisconnect?.error.output.statusCode
                : Boom.boomify(lastDisconnect?.error)?.output?.statusCode;

            if (reason === DisconnectReason.loggedOut) {
                console.log('Logged out from WhatsApp');
            } else {
                connectWhatsApp();
            }
        }
    });

    sock.ev.on('creds.update', saveCreds);

    return sock;
};

export const getQRCode = () => {
    return new Promise((resolve, reject) => {
        const cacheDir = './src/cache';
        const sessionExists = fs.existsSync(cacheDir) && fs.readdirSync(cacheDir).length > 0;

        if (sessionExists) {
            return resolve('You are already logged in');
        }

        if (!sock) {
            return reject('Socket not connected');
        }

        sock.ev.on('connection.update', (update) => {
            const { qr } = update;
            if (qr) {
                console.log('QR Code updated');
                const imageBuffer = qrImage.imageSync(qr, { type: 'png', scale: 1 });
                const base64Image = imageBuffer.toString('base64');

                return resolve(base64Image);
            }
        });
    });
};

export const sendMessage = async (number, message) => {
    try {
        console.log('Log : sendMessage');
        const numberFormatted = NumberHelper(number);
        const jid = `${numberFormatted}@s.whatsapp.net`;
        const status = await sock.sendMessage(jid, { text: message });
        return status;
    } catch (error) {
        console.log(error);
    }
};

export const sendBulkMessage = async (bulk) => {
    try {
        console.log('Log : sendBulkMessage Cuy');

        for (const { number, message } of bulk) {
            await sendMessage(number, message);
        }

        return true;
    } catch (error) {
        console.log(error);
    }
};

export const logoutWhatsApp = async () => {
    try {
        const cacheDir = './src/cache';

        if (fs.existsSync(cacheDir)) {
            const files = fs.readdirSync(cacheDir);

            files.forEach((file) => {
                const filePath = `${cacheDir}/${file}`;
                fs.unlinkSync(filePath); // Delete each file
            });

            console.log('Session files cleared');
        } else {
            console.log('Cache directory does not exist');
        }

        return true;
    } catch (error) {
        console.log('Error during logout:', error);
        return false;
    }
};
