import { createHmac } from 'crypto';

export function signBody(rawBuffer) {
  const secret = process.env.WEBHOOK_SECRET || 'changeme';
  const mac = createHmac('sha256', secret).update(rawBuffer).digest('hex');
  return `sha256=${mac}`;
}