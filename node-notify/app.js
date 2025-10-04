import express from 'express';
import notifyRoutes from './src/routes/notify.js';


const app = express();


// Capture raw body bytes for signature verification. We store the raw text on req.rawBody
app.use(express.json({
    verify: (req, res, buf, encoding) => {
        if (buf && buf.length) {
            req.rawBody = buf.toString(encoding || 'utf8');
        }
    },
}));


// mount routes
app.use('/', notifyRoutes);
// health
app.get('/health', (_req, res) => res.json({ data: 'OK' }));


export default app;