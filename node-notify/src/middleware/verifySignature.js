import crypto from 'crypto';


export default function verifySignature(req, res, next) {
    // raw bytes captured by express.json verify handler in app.js
    const raw = req.rawBody || JSON.stringify(req.body || {});
    const incoming = req.header('X-Signature') || req.header('x-signature') || '';
    const secret = process.env.WEBHOOK_SECRET || 'changeme';

    const ours = 'sha256=' + crypto.createHmac('sha256', secret).update(raw).digest('hex');

    try {
        const a = Buffer.from(ours);
        const b = Buffer.from(incoming);

        // timingSafeEqual throws if lengths are different, so check first
        if (a.length !== b.length) {
            return res.status(401).json({ error: { code: 'INVALID_SIGNATURE', message: 'Bad signature' } });
        }

        if (!crypto.timingSafeEqual(a, b)) {
            return res.status(401).json({ error: { code: 'INVALID_SIGNATURE', message: 'Bad signature' } });
        }

        return next();
    } catch (err) {
        return res.status(401).json({ error: { code: 'INVALID_SIGNATURE', message: 'Bad signature' } });
    }
}