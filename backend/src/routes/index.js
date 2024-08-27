import express from 'express';
import whatsappRoute from './WhatsappRoute.js';

const route = express.Router();

route.use('/', whatsappRoute);

export default route;
