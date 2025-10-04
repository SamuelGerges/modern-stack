
export default function validateBody(req, res, next) {
    const { user_id, task_id, message, timestamp } = req.body;
    if (!user_id || !task_id || !message || !timestamp) {
        return res.status(422).json({ error: { code: 'VALIDATION_ERROR', message: 'Missing fields' } });
    }
    next();
}