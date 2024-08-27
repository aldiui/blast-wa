import { makeWASocket, useMultiFileAuthState } from '@whiskeysockets/baileys';
import qrCode from 'qrcode-terminal';
import NumberHelper from '../helpers/NumberHelper.js';
import queue from '../config/QueueConfig.js';
import { errorResponse } from '../helpers/ResponseHelper.js';

let sock;

export const connectWhatsApp = async () => {
    const { state, saveCreds } = await useMultiFileAuthState('./src/cache');
    sock = makeWASocket({
        printQRInTerminal: true,
        auth: state,
    });

    sock.ev.on('connection.update', (update) => {
        const { qr, connection } = update;

        if (qr) {
            qrCode.generate(qr, { small: true });
        }

        if (connection === 'close') {
            connectWhatsApp();
        }
    });

    sock.ev.on('creds.update', saveCreds);

    return sock;
};

export const getQRCode = () => {
    return new Promise((resolve, reject) => {
        if (!sock) {
            reject('Socket not initialized');
        }

        sock.ev.on('connection.update', (update) => {
            const { qr } = update;
            if (qr) {
                qrCode.generate(qr, { small: true }, (qrcode) => {
                    resolve(qrcode);
                });
            }
        });
    });
};

export const sendMessage = async (number, message) => {
    try {
        const numberFormatted = NumberHelper(number);
        const jid = `${numberFormatted}@s.whatsapp.net`;
        const status = await sock.sendMessage(jid, { text: message });
        return status;
    } catch (error) {
        return errorResponse(res, 500, 'Internal Server Error', error);
    }
};

const delay = (ms) => new Promise((resolve) => setTimeout(resolve, ms));

export const sendBulkMessage = async (bulk) => {
    try {
        for (const { number, message } of bulk) {
            await sendMessage(number, message);
        }

        return true;
    } catch (error) {
        console.log(error);
        return errorResponse(res, 500, 'Internal Server Error', error);
    }
};
