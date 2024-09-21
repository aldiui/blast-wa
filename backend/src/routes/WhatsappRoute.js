import express from 'express';
import { generateQRCode, logout, sendNewBulkMessage, sendNewMessage } from '../controllers/WhatsappController.js';

const route = express.Router();

route.get('/qrcode', generateQRCode);
route.post('/message/send', sendNewMessage);
route.post('/message/bulk', sendNewBulkMessage);
route.get('/logout', logout);

export default route;
