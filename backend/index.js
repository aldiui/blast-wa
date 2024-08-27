import express from 'express';
import routes from './src/routes/index.js';
import { connectWhatsApp } from './src/services/WhatsappService.js';
import dotenv from 'dotenv';
import cors from 'cors';

dotenv.config();
const app = express();
const port = process.env.PORT || 3000;

app.use(cors());
app.use(express.json());
app.use(express.urlencoded({ extended: true }));

await connectWhatsApp();

app.get('/', (req, res) => res.send('Hello World!'));
app.use('/api', routes);

app.listen(port, () => console.log('Server up and running on http://localhost:' + port));
