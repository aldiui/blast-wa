import { makeWASocket, useMultiFileAuthState } from '@whiskeysockets/baileys';
import qrCode from 'qrcode-terminal';
import NumberHelper from '../helpers/NumberHelper.js';

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
            return reject('Socket not connected');
        }

        sock.ev.on('connection.update', (update) => {
            const { qr } = update;
            if (qr) {
                qrCode.toDataURL(qr, (url) => {
                    return resolve(url);
                });
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
        console.log('Log : sendBulkMessage');

        for (const { number, message } of bulk) {
            await sendMessage(number, message);
        }

        return true;
    } catch (error) {
        console.log(error);
    }
};
