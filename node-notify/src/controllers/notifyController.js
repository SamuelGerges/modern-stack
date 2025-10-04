import notificationService from '../services/notifications.js';


export const notify = (req, res) => {
    const { user_id, task_id, message, timestamp } = req.body;
    const note = { user_id, task_id, message, timestamp };

    notificationService.add(note);
    console.log(JSON.stringify({ event: 'task.completed', ...note }));

    return res.json({ data: { received: true } });
};


export const listNotifications = (req, res) => {
    return res.json({ data: notificationService.getAll() });
};