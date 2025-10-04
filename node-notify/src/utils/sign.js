import crypto from 'crypto';


export function sign(raw, secret = process.env.WEBHOOK_SECRET || 'changeme') {
    return 'sha256=' + crypto.createHmac('sha256', secret).update(raw).digest('hex');
}